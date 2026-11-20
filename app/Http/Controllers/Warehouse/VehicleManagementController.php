<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Vehicle;
use App\Models\VehicleFuelLog;
use App\Models\VehicleDailyExpense;
use App\Models\VehicleRepairLog;
use App\Models\DriverWarehouse;
use App\Models\Admin;
use Yajra\Datatables\Datatables;

class VehicleManagementController extends Controller
{
    /**
     * Get drivers strictly assigned to this warehouse
     */
    protected function getAssignedDrivers()
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        return DriverWarehouse::join('admins', 'driver_warehouse.driver_id', '=', 'admins.id')
            ->leftJoin('vehicle', 'admins.vehicle_id', '=', 'vehicle.id')
            ->where('driver_warehouse.warehouse_id', $warehouse_id)
            ->where('admins.is_hidden', 0)
            ->orderBy('admins.name', 'asc')
            ->select(
                'admins.id',
                'admins.name',
                'admins.phone',
                'admins.vehicle_id',
                'vehicle.vehicle_name',
                'vehicle.vehicle_no',
                'vehicle.fuel_type',
                'vehicle.current_odometer'
            )
            ->get();
    }

    /**
     * Get all active vehicles
     */
    protected function getActiveVehicles()
    {
        return Vehicle::where('status', 1)->orderBy('vehicle_no', 'asc')->get();
    }

    /**
     * AJAX: Get vehicle details assigned to a driver
     */
    public function getDriverVehicle($driver_id)
    {
        $driver = Admin::with('vehicle')->find($driver_id);
        if (!$driver) {
            return response()->json(['status' => false, 'message' => 'Driver not found']);
        }

        $vehicle = $driver->vehicle;
        if (!$vehicle) {
            $vehicle = Vehicle::where('assigned_driver_id', $driver_id)->first();
        }

        if (!$vehicle) {
            return response()->json(['status' => false, 'vehicle' => null]);
        }

        // Get latest odometer reading from vehicle or logs
        $lastFuel = VehicleFuelLog::where('vehicle_id', $vehicle->id)->orderBy('odometer_reading', 'desc')->first();
        $lastRepair = VehicleRepairLog::where('vehicle_id', $vehicle->id)->orderBy('odometer_at_repair', 'desc')->first();
        $highestOdo = max(
            (float)($vehicle->current_odometer ?? 0),
            (float)($lastFuel->odometer_reading ?? 0),
            (float)($lastRepair->odometer_at_repair ?? 0)
        );

        return response()->json([
            'status' => true,
            'vehicle' => [
                'id' => $vehicle->id,
                'vehicle_name' => $vehicle->vehicle_name,
                'vehicle_no' => $vehicle->vehicle_no,
                'fuel_type' => $vehicle->fuel_type ?? 'Diesel',
                'current_odometer' => $highestOdo,
            ]
        ]);
    }

    /**
     * AJAX: Get latest odometer reading of a vehicle
     */
    public function getVehicleOdometer($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        if (!$vehicle) {
            return response()->json(['status' => false, 'message' => 'Vehicle not found']);
        }

        $lastFuel = VehicleFuelLog::where('vehicle_id', $vehicle->id)->orderBy('odometer_reading', 'desc')->first();
        $lastRepair = VehicleRepairLog::where('vehicle_id', $vehicle->id)->orderBy('odometer_at_repair', 'desc')->first();
        $highestOdo = max(
            (float)($vehicle->current_odometer ?? 0),
            (float)($lastFuel->odometer_reading ?? 0),
            (float)($lastRepair->odometer_at_repair ?? 0)
        );

        return response()->json([
            'status' => true,
            'current_odometer' => $highestOdo,
            'fuel_type' => $vehicle->fuel_type ?? 'Diesel',
        ]);
    }

    // =========================================================================
    // 1. FUEL LOGS (FUEL TRACKING)
    // =========================================================================

    /**
     * Fuel Logs List & Summary View
     */
    public function fuelLogs(Request $request)
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        $driverIds = $drivers->pluck('id')->toArray();

        // Calculate KPI summary for warehouse drivers
        $fuelQuery = VehicleFuelLog::whereIn('driver_id', $driverIds);
        $totalLiters = (clone $fuelQuery)->sum('quantity_liters');
        $totalCost = (clone $fuelQuery)->sum('total_amount');
        $thisMonthCost = (clone $fuelQuery)->whereMonth('fuel_date', date('m'))->whereYear('fuel_date', date('Y'))->sum('total_amount');
        $totalEntries = (clone $fuelQuery)->count();

        return view('warehouse.vehicle.fuel_logs.index', compact(
            'drivers', 'vehicles', 'totalLiters', 'totalCost', 'thisMonthCost', 'totalEntries'
        ));
    }

    /**
     * Fuel Logs Yajra DataTable
     */
    public function getFuelLogsDatatable(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $driverIds = DriverWarehouse::where('warehouse_id', $warehouse_id)->pluck('driver_id')->toArray();

        $query = VehicleFuelLog::with(['vehicle', 'driver', 'approver'])
            ->whereIn('driver_id', $driverIds);

        // Filter: Driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // Filter: Vehicle
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter: Fuel Type
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        // Filter: Approval Status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter: Date Range
        if ($request->filled('from_date')) {
            $query->whereDate('fuel_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('fuel_date', '<=', $request->to_date);
        }

        $query->orderBy('fuel_date', 'desc')->orderBy('id', 'desc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('fuel_date', function ($row) {
                return '<span class="fw-semibold">' . date('d M, Y', strtotime($row->fuel_date)) . '</span>';
            })
            ->addColumn('driver_name', function ($row) {
                $driverName = $row->driver ? e($row->driver->name) : '<span class="text-muted">N/A</span>';
                $phone = $row->driver && $row->driver->phone ? '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($row->driver->phone) . '</small>' : '';
                return $driverName . $phone;
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) {
                    return '<span class="text-muted">N/A</span>';
                }
                return '<div class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</div>' .
                       '<small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
            })
            ->editColumn('fuel_type', function ($row) {
                $badgeClass = match(strtolower($row->fuel_type ?? '')) {
                    'diesel' => 'bg-info text-dark',
                    'petrol' => 'bg-warning text-dark',
                    'cng' => 'bg-success text-white',
                    'electric' => 'bg-primary text-white',
                    default => 'bg-secondary text-white'
                };
                return '<span class="badge ' . $badgeClass . '">' . e($row->fuel_type) . '</span>';
            })
            ->editColumn('quantity_liters', function ($row) {
                return '<span class="fw-bold">' . number_format($row->quantity_liters, 2) . ' L</span>';
            })
            ->editColumn('rate_per_liter', function ($row) {
                return '₹' . number_format($row->rate_per_liter, 2);
            })
            ->editColumn('total_amount', function ($row) {
                return '<span class="fw-bold text-success">₹' . number_format($row->total_amount, 2) . '</span>';
            })
            ->editColumn('odometer_reading', function ($row) {
                $odo = $row->odometer_reading ? number_format($row->odometer_reading, 0) . ' km' : '-';
                $dist = $row->distance_travelled ? '<br><small class="text-muted">+' . number_format($row->distance_travelled, 0) . ' km</small>' : '';
                return $odo . $dist;
            })
            ->addColumn('mileage', function ($row) {
                if ($row->calculated_mileage && $row->calculated_mileage > 0) {
                    return '<span class="badge bg-light-success text-success fw-bold">' . number_format($row->calculated_mileage, 2) . ' km/L</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('slip_image', function ($row) {
                if ($row->slip_image && file_exists(public_path('uploads/vehicles/fuel_slips/' . $row->slip_image))) {
                    $imgUrl = asset('uploads/vehicles/fuel_slips/' . $row->slip_image);
                    return '<a href="' . $imgUrl . '" target="_blank" class="view-slip-btn" data-url="' . $imgUrl . '" title="View Slip">' .
                           '<img src="' . $imgUrl . '" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;" alt="Slip" />' .
                           '</a>';
                }
                return '<span class="text-muted small">No Image</span>';
            })
            ->editColumn('approval_status', function ($row) {
                $status = $row->approval_status ?? 'Pending';
                $badgeClass = match($status) {
                    'Approved' => 'bg-success',
                    'Rejected' => 'bg-danger',
                    default => 'bg-warning text-dark'
                };
                return '<span class="badge ' . $badgeClass . '">' . e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse-vehicle-fuel-log-edit', $row->id);
                $deleteUrl = route('warehouse-vehicle-fuel-log-delete', $row->id);
                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fa fa-pen-to-square"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-fuel-btn" data-url="' . $deleteUrl . '" data-id="' . $row->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['fuel_date', 'driver_name', 'vehicle_info', 'fuel_type', 'quantity_liters', 'total_amount', 'odometer_reading', 'mileage', 'slip_image', 'approval_status', 'action'])
            ->make(true);
    }

    /**
     * Add Fuel Log View
     */
    public function fuelLogCreate()
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.fuel_logs.create', compact('drivers', 'vehicles'));
    }

    /**
     * Store Fuel Log
     */
    public function fuelLogStore(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'vehicle_id' => 'required|exists:vehicle,id',
            'fuel_date' => 'required|date',
            'fuel_type' => 'required|string|max:50',
            'quantity_liters' => 'required|numeric|min:0.01',
            'rate_per_liter' => 'required|numeric|min:0.01',
            'total_amount' => 'nullable|numeric|min:0',
            'odometer_reading' => 'nullable|numeric|min:0',
            'fuel_station_name' => 'nullable|string|max:191',
            'payment_mode' => 'nullable|string|max:50',
            'approval_status' => 'required|in:Pending,Approved,Rejected',
            'slip_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $warehouse_user_id = Auth::guard('backend')->user()->id;

        $vehicle_id = $request->vehicle_id;
        $driver = Admin::find($request->driver_id);
        if ($driver && $driver->vehicle_id) {
            $vehicle_id = $driver->vehicle_id;
        }

        // Auto calculate total if empty
        $quantity = (float)$request->quantity_liters;
        $rate = (float)$request->rate_per_liter;
        $totalAmount = $request->filled('total_amount') && (float)$request->total_amount > 0
            ? (float)$request->total_amount
            : round($quantity * $rate, 2);

        // Distance & Mileage calculation
        $odo = $request->filled('odometer_reading') ? (float)$request->odometer_reading : null;
        $distance = null;
        $mileage = null;

        if ($odo !== null && $odo > 0) {
            $prevLog = VehicleFuelLog::where('vehicle_id', $vehicle_id)
                ->where('odometer_reading', '<', $odo)
                ->orderBy('odometer_reading', 'desc')
                ->first();

            if ($prevLog && $prevLog->odometer_reading) {
                $distance = $odo - (float)$prevLog->odometer_reading;
                if ($distance > 0 && $quantity > 0) {
                    $mileage = round($distance / $quantity, 2);
                }
            }

            // Update vehicle current odometer if higher
            Vehicle::where('id', $vehicle_id)
                ->where(function ($q) use ($odo) {
                    $q->whereNull('current_odometer')->orWhere('current_odometer', '<', $odo);
                })
                ->update(['current_odometer' => $odo]);
        }

        // Upload slip image
        $slipFilename = null;
        if ($request->hasFile('slip_image')) {
            $file = $request->file('slip_image');
            $slipFilename = 'fuel_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/vehicles/fuel_slips');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $slipFilename);
        }

        $fuelLog = new VehicleFuelLog();
        $fuelLog->vehicle_id = $vehicle_id;
        $fuelLog->driver_id = $request->driver_id;
        $fuelLog->fuel_date = $request->fuel_date;
        $fuelLog->fuel_type = $request->fuel_type;
        $fuelLog->quantity_liters = $quantity;
        $fuelLog->rate_per_liter = $rate;
        $fuelLog->total_amount = $totalAmount;
        $fuelLog->odometer_reading = $odo;
        $fuelLog->distance_travelled = $distance;
        $fuelLog->calculated_mileage = $mileage;
        $fuelLog->fuel_station_name = $request->fuel_station_name;
        $fuelLog->slip_image = $slipFilename;
        $fuelLog->payment_mode = $request->payment_mode ?? 'Cash';
        $fuelLog->approval_status = $request->approval_status;
        $fuelLog->approved_by = ($request->approval_status === 'Approved') ? $warehouse_user_id : null;
        $fuelLog->remarks = $request->remarks;
        $fuelLog->save();

        return redirect()->route('warehouse-vehicle-fuel-logs')->with('success_msg', 'Fuel entry added successfully.');
    }

    /**
     * Edit Fuel Log View
     */
    public function fuelLogEdit($id)
    {
        $log = VehicleFuelLog::findOrFail($id);
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.fuel_logs.edit', compact('log', 'drivers', 'vehicles'));
    }

    /**
     * Update Fuel Log
     */
    public function fuelLogUpdate(Request $request, $id)
    {
        $log = VehicleFuelLog::findOrFail($id);

        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'vehicle_id' => 'required|exists:vehicle,id',
            'fuel_date' => 'required|date',
            'fuel_type' => 'required|string|max:50',
            'quantity_liters' => 'required|numeric|min:0.01',
            'rate_per_liter' => 'required|numeric|min:0.01',
            'total_amount' => 'nullable|numeric|min:0',
            'odometer_reading' => 'nullable|numeric|min:0',
            'fuel_station_name' => 'nullable|string|max:191',
            'payment_mode' => 'nullable|string|max:50',
            'approval_status' => 'required|in:Pending,Approved,Rejected',
            'slip_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $warehouse_user_id = Auth::guard('backend')->user()->id;

        $vehicle_id = $request->vehicle_id;
        $driver = Admin::find($request->driver_id);
        if ($driver && $driver->vehicle_id) {
            $vehicle_id = $driver->vehicle_id;
        }

        $quantity = (float)$request->quantity_liters;
        $rate = (float)$request->rate_per_liter;
        $totalAmount = $request->filled('total_amount') && (float)$request->total_amount > 0
            ? (float)$request->total_amount
            : round($quantity * $rate, 2);

        $odo = $request->filled('odometer_reading') ? (float)$request->odometer_reading : null;
        $distance = $log->distance_travelled;
        $mileage = $log->calculated_mileage;

        if ($odo !== null && $odo > 0) {
            $prevLog = VehicleFuelLog::where('vehicle_id', $vehicle_id)
                ->where('id', '!=', $id)
                ->where('odometer_reading', '<', $odo)
                ->orderBy('odometer_reading', 'desc')
                ->first();

            if ($prevLog && $prevLog->odometer_reading) {
                $distance = $odo - (float)$prevLog->odometer_reading;
                if ($distance > 0 && $quantity > 0) {
                    $mileage = round($distance / $quantity, 2);
                }
            }

            Vehicle::where('id', $vehicle_id)
                ->where(function ($q) use ($odo) {
                    $q->whereNull('current_odometer')->orWhere('current_odometer', '<', $odo);
                })
                ->update(['current_odometer' => $odo]);
        }

        // Handle image upload
        if ($request->hasFile('slip_image')) {
            $destination = public_path('uploads/vehicles/fuel_slips');
            if ($log->slip_image && File::exists($destination . '/' . $log->slip_image)) {
                File::delete($destination . '/' . $log->slip_image);
            }
            $file = $request->file('slip_image');
            $filename = 'fuel_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $log->slip_image = $filename;
        }

        $log->vehicle_id = $vehicle_id;
        $log->driver_id = $request->driver_id;
        $log->fuel_date = $request->fuel_date;
        $log->fuel_type = $request->fuel_type;
        $log->quantity_liters = $quantity;
        $log->rate_per_liter = $rate;
        $log->total_amount = $totalAmount;
        $log->odometer_reading = $odo;
        $log->distance_travelled = $distance;
        $log->calculated_mileage = $mileage;
        $log->fuel_station_name = $request->fuel_station_name;
        $log->payment_mode = $request->payment_mode ?? 'Cash';
        $log->approval_status = $request->approval_status;
        $log->approved_by = ($request->approval_status === 'Approved') ? $warehouse_user_id : null;
        $log->remarks = $request->remarks;
        $log->save();

        return redirect()->route('warehouse-vehicle-fuel-logs')->with('success_msg', 'Fuel entry updated successfully.');
    }

    /**
     * Delete Fuel Log
     */
    public function fuelLogDelete($id)
    {
        $log = VehicleFuelLog::findOrFail($id);
        if ($log->slip_image) {
            $filePath = public_path('uploads/vehicles/fuel_slips/' . $log->slip_image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $log->delete();

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => 'Fuel entry deleted successfully.']);
        }
        return redirect()->route('warehouse-vehicle-fuel-logs')->with('success_msg', 'Fuel entry deleted successfully.');
    }


    // =========================================================================
    // 2. DAILY EXPENSES
    // =========================================================================

    /**
     * Daily Expenses List & Summary View
     */
    public function dailyExpenses(Request $request)
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        $driverIds = $drivers->pluck('id')->toArray();

        $expQuery = VehicleDailyExpense::whereIn('driver_id', $driverIds);
        $totalExpenses = (clone $expQuery)->sum('amount');
        $approvedExpenses = (clone $expQuery)->where('reimbursement_status', 'Approved')->sum('amount');
        $pendingExpenses = (clone $expQuery)->where('reimbursement_status', 'Pending')->sum('amount');
        $thisMonthExpenses = (clone $expQuery)->whereMonth('expense_date', date('m'))->whereYear('expense_date', date('Y'))->sum('amount');

        return view('warehouse.vehicle.daily_expenses.index', compact(
            'drivers', 'vehicles', 'totalExpenses', 'approvedExpenses', 'pendingExpenses', 'thisMonthExpenses'
        ));
    }

    /**
     * Daily Expenses Yajra DataTable
     */
    public function getDailyExpensesDatatable(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $driverIds = DriverWarehouse::where('warehouse_id', $warehouse_id)->pluck('driver_id')->toArray();

        $query = VehicleDailyExpense::with(['vehicle', 'driver', 'approver'])
            ->whereIn('driver_id', $driverIds);

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->filled('expense_category')) {
            $query->where('expense_category', $request->expense_category);
        }
        if ($request->filled('reimbursement_status')) {
            $query->where('reimbursement_status', $request->reimbursement_status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $query->orderBy('expense_date', 'desc')->orderBy('id', 'desc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('expense_date', function ($row) {
                return '<span class="fw-semibold">' . date('d M, Y', strtotime($row->expense_date)) . '</span>';
            })
            ->addColumn('driver_name', function ($row) {
                $driverName = $row->driver ? e($row->driver->name) : '<span class="text-muted">N/A</span>';
                $phone = $row->driver && $row->driver->phone ? '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($row->driver->phone) . '</small>' : '';
                return $driverName . $phone;
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) {
                    return '<span class="text-muted">N/A</span>';
                }
                return '<div class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</div>' .
                       '<small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
            })
            ->editColumn('expense_category', function ($row) {
                $badgeClass = match(strtolower($row->expense_category ?? '')) {
                    'toll', 'toll tax' => 'bg-info text-dark',
                    'parking', 'parking charges' => 'bg-primary text-white',
                    'washing', 'vehicle washing' => 'bg-cyan text-dark',
                    'driver allowance', 'food allowance' => 'bg-secondary text-white',
                    'puncture/air', 'puncture' => 'bg-warning text-dark',
                    default => 'bg-dark text-white'
                };
                return '<span class="badge ' . $badgeClass . '">' . e($row->expense_category) . '</span>';
            })
            ->editColumn('amount', function ($row) {
                return '<span class="fw-bold text-success">₹' . number_format($row->amount, 2) . '</span>';
            })
            ->editColumn('description', function ($row) {
                return $row->description ? '<span title="' . e($row->description) . '">' . Str::limit(e($row->description), 40) . '</span>' : '<span class="text-muted">-</span>';
            })
            ->editColumn('bill_receipt_image', function ($row) {
                if ($row->bill_receipt_image && file_exists(public_path('uploads/vehicles/daily_expenses/' . $row->bill_receipt_image))) {
                    $imgUrl = asset('uploads/vehicles/daily_expenses/' . $row->bill_receipt_image);
                    return '<a href="' . $imgUrl . '" target="_blank" class="view-slip-btn" data-url="' . $imgUrl . '" title="View Bill">' .
                           '<img src="' . $imgUrl . '" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;" alt="Bill" />' .
                           '</a>';
                }
                return '<span class="text-muted small">No Image</span>';
            })
            ->editColumn('reimbursement_status', function ($row) {
                $status = $row->reimbursement_status ?? 'Pending';
                $badgeClass = match($status) {
                    'Approved', 'Paid' => 'bg-success',
                    'Rejected' => 'bg-danger',
                    default => 'bg-warning text-dark'
                };
                return '<span class="badge ' . $badgeClass . '">' . e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse-vehicle-daily-expense-edit', $row->id);
                $deleteUrl = route('warehouse-vehicle-daily-expense-delete', $row->id);
                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fa fa-pen-to-square"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-expense-btn" data-url="' . $deleteUrl . '" data-id="' . $row->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['expense_date', 'driver_name', 'vehicle_info', 'expense_category', 'amount', 'description', 'bill_receipt_image', 'reimbursement_status', 'action'])
            ->make(true);
    }

    /**
     * Add Daily Expense View
     */
    public function dailyExpenseCreate()
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.daily_expenses.create', compact('drivers', 'vehicles'));
    }

    /**
     * Store Daily Expense
     */
    public function dailyExpenseStore(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'vehicle_id' => 'required|exists:vehicle,id',
            'expense_date' => 'required|date',
            'expense_category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'reimbursement_status' => 'required|in:Pending,Approved,Rejected,Paid',
            'bill_receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $warehouse_user_id = Auth::guard('backend')->user()->id;

        $vehicle_id = $request->vehicle_id;
        $driver = Admin::find($request->driver_id);
        if ($driver && $driver->vehicle_id) {
            $vehicle_id = $driver->vehicle_id;
        }

        $billFilename = null;
        if ($request->hasFile('bill_receipt_image')) {
            $file = $request->file('bill_receipt_image');
            $billFilename = 'exp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/vehicles/daily_expenses');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $billFilename);
        }

        $expense = new VehicleDailyExpense();
        $expense->vehicle_id = $vehicle_id;
        $expense->driver_id = $request->driver_id;
        $expense->expense_date = $request->expense_date;
        $expense->expense_category = $request->expense_category;
        $expense->amount = (float)$request->amount;
        $expense->description = $request->description;
        $expense->bill_receipt_image = $billFilename;
        $expense->reimbursement_status = $request->reimbursement_status;
        $expense->approved_by = in_array($request->reimbursement_status, ['Approved', 'Paid']) ? $warehouse_user_id : null;
        $expense->save();

        return redirect()->route('warehouse-vehicle-daily-expenses')->with('success_msg', 'Daily expense added successfully.');
    }

    /**
     * Edit Daily Expense View
     */
    public function dailyExpenseEdit($id)
    {
        $expense = VehicleDailyExpense::findOrFail($id);
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.daily_expenses.edit', compact('expense', 'drivers', 'vehicles'));
    }

    /**
     * Update Daily Expense
     */
    public function dailyExpenseUpdate(Request $request, $id)
    {
        $expense = VehicleDailyExpense::findOrFail($id);

        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'vehicle_id' => 'required|exists:vehicle,id',
            'expense_date' => 'required|date',
            'expense_category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'reimbursement_status' => 'required|in:Pending,Approved,Rejected,Paid',
            'bill_receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $warehouse_user_id = Auth::guard('backend')->user()->id;

        $vehicle_id = $request->vehicle_id;
        $driver = Admin::find($request->driver_id);
        if ($driver && $driver->vehicle_id) {
            $vehicle_id = $driver->vehicle_id;
        }

        if ($request->hasFile('bill_receipt_image')) {
            $destination = public_path('uploads/vehicles/daily_expenses');
            if ($expense->bill_receipt_image && File::exists($destination . '/' . $expense->bill_receipt_image)) {
                File::delete($destination . '/' . $expense->bill_receipt_image);
            }
            $file = $request->file('bill_receipt_image');
            $filename = 'exp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $expense->bill_receipt_image = $filename;
        }

        $expense->vehicle_id = $vehicle_id;
        $expense->driver_id = $request->driver_id;
        $expense->expense_date = $request->expense_date;
        $expense->expense_category = $request->expense_category;
        $expense->amount = (float)$request->amount;
        $expense->description = $request->description;
        $expense->reimbursement_status = $request->reimbursement_status;
        $expense->approved_by = in_array($request->reimbursement_status, ['Approved', 'Paid']) ? $warehouse_user_id : null;
        $expense->save();

        return redirect()->route('warehouse-vehicle-daily-expenses')->with('success_msg', 'Daily expense updated successfully.');
    }

    /**
     * Delete Daily Expense
     */
    public function dailyExpenseDelete($id)
    {
        $expense = VehicleDailyExpense::findOrFail($id);
        if ($expense->bill_receipt_image) {
            $filePath = public_path('uploads/vehicles/daily_expenses/' . $expense->bill_receipt_image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $expense->delete();

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => 'Daily expense deleted successfully.']);
        }
        return redirect()->route('warehouse-vehicle-daily-expenses')->with('success_msg', 'Daily expense deleted successfully.');
    }


    // =========================================================================
    // 3. REPAIR LOGS & MAINTENANCE COSTS
    // =========================================================================

    /**
     * Repair Logs List & Summary View
     */
    public function repairLogs(Request $request)
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        $driverIds = $drivers->pluck('id')->toArray();

        $repairQuery = VehicleRepairLog::whereIn('driver_id', $driverIds);
        $totalRepairCost = (clone $repairQuery)->sum('total_cost');
        $totalPartsCost = (clone $repairQuery)->sum('spare_parts_cost');
        $totalLaborCost = (clone $repairQuery)->sum('labor_charges');
        $unpaidRepairs = (clone $repairQuery)->where('payment_status', 'Unpaid')->sum('total_cost');

        return view('warehouse.vehicle.repair_logs.index', compact(
            'drivers', 'vehicles', 'totalRepairCost', 'totalPartsCost', 'totalLaborCost', 'unpaidRepairs'
        ));
    }

    /**
     * Repair Logs Yajra DataTable
     */
    public function getRepairLogsDatatable(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $driverIds = DriverWarehouse::where('warehouse_id', $warehouse_id)->pluck('driver_id')->toArray();

        $query = VehicleRepairLog::with(['vehicle', 'driver'])
            ->whereIn('driver_id', $driverIds);

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('repair_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('repair_date', '<=', $request->to_date);
        }

        $query->orderBy('repair_date', 'desc')->orderBy('id', 'desc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('repair_date', function ($row) {
                return '<span class="fw-semibold">' . date('d M, Y', strtotime($row->repair_date)) . '</span>';
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) {
                    return '<span class="text-muted">N/A</span>';
                }
                return '<div class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</div>' .
                       '<small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
            })
            ->addColumn('driver_name', function ($row) {
                return $row->driver ? e($row->driver->name) : '<span class="text-muted">N/A</span>';
            })
            ->editColumn('garage_workshop_name', function ($row) {
                $contact = $row->garage_contact_no ? '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($row->garage_contact_no) . '</small>' : '';
                return '<span class="fw-semibold">' . e($row->garage_workshop_name) . '</span>' . $contact;
            })
            ->editColumn('repair_reason', function ($row) {
                return '<span class="badge bg-secondary text-white">' . e($row->repair_reason) . '</span>';
            })
            ->addColumn('cost_breakdown', function ($row) {
                return '<small class="text-muted">' .
                       'Parts: ₹' . number_format($row->spare_parts_cost ?? 0, 2) . '<br>' .
                       'Labor: ₹' . number_format($row->labor_charges ?? 0, 2) .
                       '</small>';
            })
            ->editColumn('total_cost', function ($row) {
                return '<span class="fw-bold text-danger">₹' . number_format($row->total_cost, 2) . '</span>';
            })
            ->editColumn('odometer_at_repair', function ($row) {
                return $row->odometer_at_repair ? number_format($row->odometer_at_repair, 0) . ' km' : '-';
            })
            ->editColumn('bill_copy_image', function ($row) {
                if ($row->bill_copy_image && file_exists(public_path('uploads/vehicles/repair_bills/' . $row->bill_copy_image))) {
                    $imgUrl = asset('uploads/vehicles/repair_bills/' . $row->bill_copy_image);
                    return '<a href="' . $imgUrl . '" target="_blank" class="view-slip-btn" data-url="' . $imgUrl . '" title="View Bill Copy">' .
                           '<img src="' . $imgUrl . '" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;" alt="Bill" />' .
                           '</a>';
                }
                return '<span class="text-muted small">No Image</span>';
            })
            ->editColumn('payment_status', function ($row) {
                $status = $row->payment_status ?? 'Unpaid';
                $badgeClass = match($status) {
                    'Paid' => 'bg-success',
                    'Partially Paid' => 'bg-info text-dark',
                    default => 'bg-danger text-white'
                };
                return '<span class="badge ' . $badgeClass . '">' . e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse-vehicle-repair-log-edit', $row->id);
                $deleteUrl = route('warehouse-vehicle-repair-log-delete', $row->id);
                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fa fa-pen-to-square"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-repair-btn" data-url="' . $deleteUrl . '" data-id="' . $row->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['repair_date', 'vehicle_info', 'driver_name', 'garage_workshop_name', 'repair_reason', 'cost_breakdown', 'total_cost', 'bill_copy_image', 'payment_status', 'action'])
            ->make(true);
    }

    /**
     * Add Repair Log View
     */
    public function repairLogCreate()
    {
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.repair_logs.create', compact('drivers', 'vehicles'));
    }

    /**
     * Store Repair Log
     */
    public function repairLogStore(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicle,id',
            'driver_id' => 'nullable|exists:admins,id',
            'repair_date' => 'required|date',
            'completion_date' => 'nullable|date',
            'garage_workshop_name' => 'required|string|max:191',
            'garage_contact_no' => 'nullable|string|max:50',
            'invoice_number' => 'nullable|string|max:100',
            'repair_reason' => 'required|string|max:191',
            'issues_reported' => 'nullable|string|max:1000',
            'work_performed' => 'nullable|string|max:1000',
            'spare_parts_cost' => 'nullable|numeric|min:0',
            'labor_charges' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'odometer_at_repair' => 'nullable|numeric|min:0',
            'bill_copy_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid',
            'payment_mode' => 'nullable|string|max:50',
        ]);

        $vehicle_id = $request->vehicle_id;
        if ($request->filled('driver_id')) {
            $driver = Admin::find($request->driver_id);
            if ($driver && $driver->vehicle_id) {
                $vehicle_id = $driver->vehicle_id;
            }
        }

        $parts = (float)($request->spare_parts_cost ?? 0);
        $labor = (float)($request->labor_charges ?? 0);
        $tax = (float)($request->tax_amount ?? 0);
        $total = $request->filled('total_cost') && (float)$request->total_cost > 0
            ? (float)$request->total_cost
            : ($parts + $labor + $tax);

        $odo = $request->filled('odometer_at_repair') ? (float)$request->odometer_at_repair : null;
        if ($odo !== null && $odo > 0) {
            Vehicle::where('id', $vehicle_id)
                ->where(function ($q) use ($odo) {
                    $q->whereNull('current_odometer')->orWhere('current_odometer', '<', $odo);
                })
                ->update(['current_odometer' => $odo]);
        }

        $billFilename = null;
        if ($request->hasFile('bill_copy_image')) {
            $file = $request->file('bill_copy_image');
            $billFilename = 'repair_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/vehicles/repair_bills');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $billFilename);
        }

        $repair = new VehicleRepairLog();
        $repair->vehicle_id = $vehicle_id;
        $repair->driver_id = $request->driver_id;
        $repair->repair_date = $request->repair_date;
        $repair->completion_date = $request->completion_date;
        $repair->garage_workshop_name = $request->garage_workshop_name;
        $repair->garage_contact_no = $request->garage_contact_no;
        $repair->invoice_number = $request->invoice_number;
        $repair->repair_reason = $request->repair_reason;
        $repair->issues_reported = $request->issues_reported;
        $repair->work_performed = $request->work_performed;
        $repair->spare_parts_cost = $parts;
        $repair->labor_charges = $labor;
        $repair->tax_amount = $tax;
        $repair->total_cost = $total;
        $repair->odometer_at_repair = $odo;
        $repair->bill_copy_image = $billFilename;
        $repair->payment_status = $request->payment_status;
        $repair->payment_mode = $request->payment_mode ?? 'Cash';
        $repair->save();

        return redirect()->route('warehouse-vehicle-repair-logs')->with('success_msg', 'Vehicle repair entry added successfully.');
    }

    /**
     * Edit Repair Log View
     */
    public function repairLogEdit($id)
    {
        $repair = VehicleRepairLog::findOrFail($id);
        $drivers = $this->getAssignedDrivers();
        $vehicles = $this->getActiveVehicles();
        return view('warehouse.vehicle.repair_logs.edit', compact('repair', 'drivers', 'vehicles'));
    }

    /**
     * Update Repair Log
     */
    public function repairLogUpdate(Request $request, $id)
    {
        $repair = VehicleRepairLog::findOrFail($id);

        $request->validate([
            'vehicle_id' => 'required|exists:vehicle,id',
            'driver_id' => 'nullable|exists:admins,id',
            'repair_date' => 'required|date',
            'completion_date' => 'nullable|date',
            'garage_workshop_name' => 'required|string|max:191',
            'garage_contact_no' => 'nullable|string|max:50',
            'invoice_number' => 'nullable|string|max:100',
            'repair_reason' => 'required|string|max:191',
            'issues_reported' => 'nullable|string|max:1000',
            'work_performed' => 'nullable|string|max:1000',
            'spare_parts_cost' => 'nullable|numeric|min:0',
            'labor_charges' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'odometer_at_repair' => 'nullable|numeric|min:0',
            'bill_copy_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid',
            'payment_mode' => 'nullable|string|max:50',
        ]);

        $vehicle_id = $request->vehicle_id;
        if ($request->filled('driver_id')) {
            $driver = Admin::find($request->driver_id);
            if ($driver && $driver->vehicle_id) {
                $vehicle_id = $driver->vehicle_id;
            }
        }

        $parts = (float)($request->spare_parts_cost ?? 0);
        $labor = (float)($request->labor_charges ?? 0);
        $tax = (float)($request->tax_amount ?? 0);
        $total = $request->filled('total_cost') && (float)$request->total_cost > 0
            ? (float)$request->total_cost
            : ($parts + $labor + $tax);

        $odo = $request->filled('odometer_at_repair') ? (float)$request->odometer_at_repair : null;
        if ($odo !== null && $odo > 0) {
            Vehicle::where('id', $vehicle_id)
                ->where(function ($q) use ($odo) {
                    $q->whereNull('current_odometer')->orWhere('current_odometer', '<', $odo);
                })
                ->update(['current_odometer' => $odo]);
        }

        if ($request->hasFile('bill_copy_image')) {
            $destination = public_path('uploads/vehicles/repair_bills');
            if ($repair->bill_copy_image && File::exists($destination . '/' . $repair->bill_copy_image)) {
                File::delete($destination . '/' . $repair->bill_copy_image);
            }
            $file = $request->file('bill_copy_image');
            $filename = 'repair_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $repair->bill_copy_image = $filename;
        }

        $repair->vehicle_id = $vehicle_id;
        $repair->driver_id = $request->driver_id;
        $repair->repair_date = $request->repair_date;
        $repair->completion_date = $request->completion_date;
        $repair->garage_workshop_name = $request->garage_workshop_name;
        $repair->garage_contact_no = $request->garage_contact_no;
        $repair->invoice_number = $request->invoice_number;
        $repair->repair_reason = $request->repair_reason;
        $repair->issues_reported = $request->issues_reported;
        $repair->work_performed = $request->work_performed;
        $repair->spare_parts_cost = $parts;
        $repair->labor_charges = $labor;
        $repair->tax_amount = $tax;
        $repair->total_cost = $total;
        $repair->odometer_at_repair = $odo;
        $repair->payment_status = $request->payment_status;
        $repair->payment_mode = $request->payment_mode ?? 'Cash';
        $repair->save();

        return redirect()->route('warehouse-vehicle-repair-logs')->with('success_msg', 'Vehicle repair entry updated successfully.');
    }

    /**
     * Delete Repair Log
     */
    public function repairLogDelete($id)
    {
        $repair = VehicleRepairLog::findOrFail($id);
        if ($repair->bill_copy_image) {
            $filePath = public_path('uploads/vehicles/repair_bills/' . $repair->bill_copy_image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $repair->delete();

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => 'Vehicle repair entry deleted successfully.']);
        }
        return redirect()->route('warehouse-vehicle-repair-logs')->with('success_msg', 'Vehicle repair entry deleted successfully.');
    }
}
