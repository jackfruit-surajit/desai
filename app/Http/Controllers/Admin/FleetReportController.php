<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Vehicle;
use App\Models\VehicleFuelLog;
use App\Models\VehicleDailyExpense;
use App\Models\VehicleRepairLog;
use App\Models\DriverWarehouse;
use App\Models\Admin;
use Yajra\Datatables\Datatables;

class FleetReportController extends Controller
{
    // =========================================================================
    // 1. FUEL TRACKING REPORTS (VIEW ONLY)
    // =========================================================================

    public function fuelReports(Request $request)
    {
        $warehouses = Admin::where('role_id', 4)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name']);
        $drivers = Admin::where('role_id', 3)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name', 'phone', 'vehicle_id']);
        $vehicles = Vehicle::orderBy('vehicle_no', 'asc')->get(['id', 'vehicle_no', 'vehicle_name']);

        $fuelQuery = VehicleFuelLog::query();
        $totalLiters = (clone $fuelQuery)->sum('quantity_liters');
        $totalCost = (clone $fuelQuery)->sum('total_amount');
        $thisMonthCost = (clone $fuelQuery)->whereMonth('fuel_date', date('m'))->whereYear('fuel_date', date('Y'))->sum('total_amount');
        $totalEntries = (clone $fuelQuery)->count();
        $avgMileage = (clone $fuelQuery)->where('calculated_mileage', '>', 0)->avg('calculated_mileage');

        return view('admin.fleet_reports.fuel_reports', compact(
            'warehouses', 'drivers', 'vehicles', 'totalLiters', 'totalCost', 'thisMonthCost', 'totalEntries', 'avgMileage'
        ));
    }

    public function getFuelReportsDatatable(Request $request)
    {
        $query = VehicleFuelLog::with([
            'vehicle',
            'driver.driverWarehouse.warehouse',
            'approver'
        ]);

        if ($request->filled('warehouse_id')) {
            $driverIds = DriverWarehouse::where('warehouse_id', $request->warehouse_id)->pluck('driver_id')->toArray();
            $query->whereIn('driver_id', $driverIds);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

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
            ->addColumn('warehouse_name', function ($row) {
                $wh = $row->driver && $row->driver->driverWarehouse && $row->driver->driverWarehouse->warehouse 
                    ? $row->driver->driverWarehouse->warehouse->name 
                    : null;
                return $wh ? '<span class="badge bg-light text-dark fw-bold">' . e($wh) . '</span>' : '<span class="text-muted">Unassigned</span>';
            })
            ->addColumn('driver_info', function ($row) {
                if (!$row->driver) return '<span class="text-muted">N/A</span>';
                $phone = $row->driver->phone ? '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($row->driver->phone) . '</small>' : '';
                return '<span class="fw-bold">' . e($row->driver->name) . '</span>' . $phone;
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) return '<span class="text-muted">N/A</span>';
                return '<span class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</span>' .
                       '<br><small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
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
                           '<img src="' . $imgUrl . '" class="rounded border shadow-sm" style="width: 44px; height: 44px; object-fit: cover;" alt="Slip" />' .
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
            ->rawColumns(['fuel_date', 'warehouse_name', 'driver_info', 'vehicle_info', 'fuel_type', 'quantity_liters', 'total_amount', 'odometer_reading', 'mileage', 'slip_image', 'approval_status'])
            ->make(true);
    }


    // =========================================================================
    // 2. DAILY EXPENSES REPORTS (VIEW ONLY)
    // =========================================================================

    public function dailyExpenses(Request $request)
    {
        $warehouses = Admin::where('role_id', 4)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name']);
        $drivers = Admin::where('role_id', 3)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name', 'phone', 'vehicle_id']);
        $vehicles = Vehicle::orderBy('vehicle_no', 'asc')->get(['id', 'vehicle_no', 'vehicle_name']);

        $expQuery = VehicleDailyExpense::query();
        $totalExpenses = (clone $expQuery)->sum('amount');
        $approvedExpenses = (clone $expQuery)->whereIn('reimbursement_status', ['Approved', 'Paid'])->sum('amount');
        $pendingExpenses = (clone $expQuery)->where('reimbursement_status', 'Pending')->sum('amount');
        $thisMonthExpenses = (clone $expQuery)->whereMonth('expense_date', date('m'))->whereYear('expense_date', date('Y'))->sum('amount');

        return view('admin.fleet_reports.daily_expenses', compact(
            'warehouses', 'drivers', 'vehicles', 'totalExpenses', 'approvedExpenses', 'pendingExpenses', 'thisMonthExpenses'
        ));
    }

    public function getDailyExpensesDatatable(Request $request)
    {
        $query = VehicleDailyExpense::with([
            'vehicle',
            'driver.driverWarehouse.warehouse',
            'approver'
        ]);

        if ($request->filled('warehouse_id')) {
            $driverIds = DriverWarehouse::where('warehouse_id', $request->warehouse_id)->pluck('driver_id')->toArray();
            $query->whereIn('driver_id', $driverIds);
        }

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
            ->addColumn('warehouse_name', function ($row) {
                $wh = $row->driver && $row->driver->driverWarehouse && $row->driver->driverWarehouse->warehouse 
                    ? $row->driver->driverWarehouse->warehouse->name 
                    : null;
                return $wh ? '<span class="badge bg-light text-dark fw-bold">' . e($wh) . '</span>' : '<span class="text-muted">Unassigned</span>';
            })
            ->addColumn('driver_info', function ($row) {
                if (!$row->driver) return '<span class="text-muted">N/A</span>';
                $phone = $row->driver->phone ? '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($row->driver->phone) . '</small>' : '';
                return '<span class="fw-bold">' . e($row->driver->name) . '</span>' . $phone;
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) return '<span class="text-muted">N/A</span>';
                return '<span class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</span>' .
                       '<br><small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
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
                return $row->description ? '<span title="' . e($row->description) . '">' . Str::limit(e($row->description), 45) . '</span>' : '<span class="text-muted">-</span>';
            })
            ->editColumn('bill_receipt_image', function ($row) {
                if ($row->bill_receipt_image && file_exists(public_path('uploads/vehicles/daily_expenses/' . $row->bill_receipt_image))) {
                    $imgUrl = asset('uploads/vehicles/daily_expenses/' . $row->bill_receipt_image);
                    return '<a href="' . $imgUrl . '" target="_blank" class="view-slip-btn" data-url="' . $imgUrl . '" title="View Receipt">' .
                           '<img src="' . $imgUrl . '" class="rounded border shadow-sm" style="width: 44px; height: 44px; object-fit: cover;" alt="Receipt" />' .
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
            ->rawColumns(['expense_date', 'warehouse_name', 'driver_info', 'vehicle_info', 'expense_category', 'amount', 'description', 'bill_receipt_image', 'reimbursement_status'])
            ->make(true);
    }


    // =========================================================================
    // 3. REPAIR & SERVICE REPORTS (VIEW ONLY)
    // =========================================================================

    public function repairReports(Request $request)
    {
        $warehouses = Admin::where('role_id', 4)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name']);
        $drivers = Admin::where('role_id', 3)->where('is_hidden', 0)->orderBy('name', 'asc')->get(['id', 'name', 'phone', 'vehicle_id']);
        $vehicles = Vehicle::orderBy('vehicle_no', 'asc')->get(['id', 'vehicle_no', 'vehicle_name']);

        $repairQuery = VehicleRepairLog::query();
        $totalRepairCost = (clone $repairQuery)->sum('total_cost');
        $totalPartsCost = (clone $repairQuery)->sum('spare_parts_cost');
        $totalLaborCost = (clone $repairQuery)->sum('labor_charges');
        $unpaidRepairs = (clone $repairQuery)->where('payment_status', 'Unpaid')->sum('total_cost');

        return view('admin.fleet_reports.repair_reports', compact(
            'warehouses', 'drivers', 'vehicles', 'totalRepairCost', 'totalPartsCost', 'totalLaborCost', 'unpaidRepairs'
        ));
    }

    public function getRepairReportsDatatable(Request $request)
    {
        $query = VehicleRepairLog::with([
            'vehicle',
            'driver.driverWarehouse.warehouse'
        ]);

        if ($request->filled('warehouse_id')) {
            $driverIds = DriverWarehouse::where('warehouse_id', $request->warehouse_id)->pluck('driver_id')->toArray();
            $query->whereIn('driver_id', $driverIds);
        }

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
            ->addColumn('warehouse_name', function ($row) {
                $wh = $row->driver && $row->driver->driverWarehouse && $row->driver->driverWarehouse->warehouse 
                    ? $row->driver->driverWarehouse->warehouse->name 
                    : null;
                return $wh ? '<span class="badge bg-light text-dark fw-bold">' . e($wh) . '</span>' : '<span class="text-muted">Unassigned</span>';
            })
            ->addColumn('vehicle_info', function ($row) {
                if (!$row->vehicle) return '<span class="text-muted">N/A</span>';
                return '<span class="fw-bold text-primary">' . e($row->vehicle->vehicle_no) . '</span>' .
                       '<br><small class="text-muted">' . e($row->vehicle->vehicle_name) . '</small>';
            })
            ->addColumn('driver_info', function ($row) {
                if (!$row->driver) return '<span class="text-muted">N/A</span>';
                return '<span class="fw-bold">' . e($row->driver->name) . '</span>';
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
                           '<img src="' . $imgUrl . '" class="rounded border shadow-sm" style="width: 44px; height: 44px; object-fit: cover;" alt="Bill" />' .
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
            ->rawColumns(['repair_date', 'warehouse_name', 'vehicle_info', 'driver_info', 'garage_workshop_name', 'repair_reason', 'cost_breakdown', 'total_cost', 'bill_copy_image', 'payment_status'])
            ->make(true);
    }
}
