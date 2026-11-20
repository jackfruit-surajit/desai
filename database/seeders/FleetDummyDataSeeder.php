<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\VehicleFuelLog;
use App\Models\VehicleDailyExpense;
use App\Models\VehicleRepairLog;
use App\Models\VehicleMaintenanceSchedule;

class FleetDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Active Drivers and their assigned Vehicles
        $driverVehicles = [
            ['driver_id' => 33, 'vehicle_id' => 7, 'fuel_type' => 'Petrol', 'base_odo' => 12500],
            ['driver_id' => 42, 'vehicle_id' => 6, 'fuel_type' => 'Petrol', 'base_odo' => 14200],
            ['driver_id' => 38, 'vehicle_id' => 8, 'fuel_type' => 'Petrol', 'base_odo' => 9800],
            ['driver_id' => 43, 'vehicle_id' => 25, 'fuel_type' => 'Petrol', 'base_odo' => 16500],
        ];

        $fuelStations = [
            'Indian Oil Corporation - Panaji Bypass',
            'Bharat Petroleum (BPCL) - Margao Highway',
            'Hindustan Petroleum (HPCL) - Vasco City Point',
            'Shell Fuel Point - Mapusa Cross',
            'Indian Oil Auto Care - Ponda Industrial Zone',
            'Nayara Energy Fuel Station - Porvorim',
            'HPCL COCO Fuel Station - Bambolim',
        ];

        $expenseCategories = [
            ['cat' => 'Toll', 'desc' => 'Atal Setu cable bridge toll fee', 'min' => 50, 'max' => 100],
            ['cat' => 'Toll', 'desc' => 'Zuari river bridge new bypass toll charge', 'min' => 50, 'max' => 100],
            ['cat' => 'Parking', 'desc' => 'Panaji municipal market commercial parking fee', 'min' => 30, 'max' => 60],
            ['cat' => 'Parking', 'desc' => 'Margao wholesale market entry and parking receipt', 'min' => 40, 'max' => 80],
            ['cat' => 'Washing', 'desc' => 'Complete body foam wash & chassis cleaning', 'min' => 250, 'max' => 350],
            ['cat' => 'Driver Allowance', 'desc' => 'Driver lunch & tea allowance for overtime distribution route', 'min' => 150, 'max' => 250],
            ['cat' => 'Puncture/Air', 'desc' => 'Rear tyre puncture repair & nitrogen tyre air top-up', 'min' => 80, 'max' => 150],
            ['cat' => 'Minor Repair', 'desc' => 'Wiper blade replacement & side indicator bulb change', 'min' => 180, 'max' => 320],
            ['cat' => 'Other', 'desc' => 'Emergency headlight fuse and relay switch replacement', 'min' => 120, 'max' => 220],
        ];

        $garages = [
            ['name' => 'Maruti Suzuki Arena Authorized Care - Panaji', 'phone' => '9822114455'],
            ['name' => 'Goa Motor Works & Body Hub - Margao', 'phone' => '9890223344'],
            ['name' => 'National Tyre, Brake & Alignment Hub - Ponda', 'phone' => '9850112233'],
            ['name' => 'Express Auto Clinic & Electricals - Mapusa', 'phone' => '9823445566'],
            ['name' => 'Reliable Garage & Service Centre - Vasco', 'phone' => '9860778899'],
        ];

        $repairReasons = [
            [
                'reason' => 'Scheduled Service',
                'issues' => 'Periodic 10,000 KM maintenance service, engine oil replacement, filter cleaning',
                'work' => 'Replaced engine oil, oil filter, air filter, spark plugs checked and brake pads cleaned',
                'parts' => 1850.00, 'labor' => 850.00, 'tax' => 243.00
            ],
            [
                'reason' => 'Brake System Overhaul',
                'issues' => 'Front brake noise and low brake pedal bite reported by driver',
                'work' => 'Replaced front disc brake pads, brake fluid bleeding & rear drum brake shoe adjustment',
                'parts' => 1450.00, 'labor' => 600.00, 'tax' => 184.50
            ],
            [
                'reason' => 'Clutch & Gearbox Service',
                'issues' => 'Hard clutch pedal and gear shifting difficulty during delivery routes',
                'work' => 'Clutch cable replaced, clutch plate assembly aligned and gear oil topped up',
                'parts' => 2200.00, 'labor' => 950.00, 'tax' => 283.50
            ],
            [
                'reason' => 'Tyre Replacement & Balancing',
                'issues' => 'Uneven front tyre tread wear and high-speed steering vibration',
                'work' => '2 new Apollo commercial tyres installed, computerised wheel balancing & 3D wheel alignment',
                'parts' => 7400.00, 'labor' => 500.00, 'tax' => 711.00
            ],
            [
                'reason' => 'Suspension & Greasing',
                'issues' => 'Squeaking sound from leaf springs on bumpy village roads',
                'work' => 'Suspension bush set replaced, leaf spring greasing, shock absorber mounting tightened',
                'parts' => 1150.00, 'labor' => 700.00, 'tax' => 166.50
            ],
            [
                'reason' => 'Electrical & Headlight Wiring',
                'issues' => 'Left headlamp flickering and horn intermittent',
                'work' => 'Headlight relay replaced, wiring harness cleaned & new high-tone horn fitted',
                'parts' => 650.00, 'labor' => 350.00, 'tax' => 90.00
            ],
            [
                'reason' => 'Cooling System Flush',
                'issues' => 'Engine temperature rising during afternoon traffic delivery jams',
                'work' => 'Radiator flushed, coolant replaced, radiator cap & thermostat valve renewed',
                'parts' => 950.00, 'labor' => 450.00, 'tax' => 126.00
            ],
        ];

        // ---------------------------------------------------------------------
        // 1. SEED 30 FUEL LOGS
        // ---------------------------------------------------------------------
        $this->command->info('Seeding 30 Vehicle Fuel Logs...');
        $currentOdometerMap = [];

        // Distribute 30 logs across the 4 driver-vehicles over dates Oct 01 to Nov 20, 2026
        for ($i = 0; $i < 30; $i++) {
            $dv = $driverVehicles[$i % count($driverVehicles)];
            $vId = $dv['vehicle_id'];

            if (!isset($currentOdometerMap[$vId])) {
                $currentOdometerMap[$vId] = $dv['base_odo'];
            }

            // Days back: from 50 days ago to today
            $dayOffset = 50 - intval(($i / 30) * 50);
            $fuelDate = date('Y-m-d', strtotime("2026-11-20 - {$dayOffset} days"));

            $distance = rand(220, 420);
            $currentOdometerMap[$vId] += $distance;
            $currentOdo = $currentOdometerMap[$vId];

            $liters = round(rand(2000, 3600) / 100, 2); // 20.00 to 36.00 L
            $rate = round(rand(9650, 9780) / 100, 2);   // 96.50 to 97.80 Rs
            $totalAmount = round($liters * $rate, 2);
            $mileage = round($distance / $liters, 2);

            $status = ($i % 7 === 0) ? 'Pending' : 'Approved';
            $approver = ($status === 'Approved') ? 35 : null;

            $modes = ['Cash', 'Online_UPI', 'Company_Card', 'Cash', 'Online_UPI'];
            $paymentMode = $modes[$i % count($modes)];
            $station = $fuelStations[$i % count($fuelStations)];

            VehicleFuelLog::create([
                'vehicle_id' => $vId,
                'driver_id' => $dv['driver_id'],
                'fuel_date' => $fuelDate,
                'fuel_type' => $dv['fuel_type'],
                'quantity_liters' => $liters,
                'rate_per_liter' => $rate,
                'total_amount' => $totalAmount,
                'odometer_reading' => $currentOdo,
                'distance_travelled' => $distance,
                'calculated_mileage' => $mileage,
                'fuel_station_name' => $station,
                'payment_mode' => $paymentMode,
                'approval_status' => $status,
                'approved_by' => $approver,
                'remarks' => "Daily distribution run tank refill at {$station}",
            ]);
        }

        // ---------------------------------------------------------------------
        // 2. SEED 30 DAILY EXPENSES
        // ---------------------------------------------------------------------
        $this->command->info('Seeding 30 Vehicle Daily Expenses...');

        for ($i = 0; $i < 30; $i++) {
            $dv = $driverVehicles[$i % count($driverVehicles)];
            $catData = $expenseCategories[$i % count($expenseCategories)];

            $dayOffset = 48 - intval(($i / 30) * 48);
            $expenseDate = date('Y-m-d', strtotime("2026-11-20 - {$dayOffset} days"));

            $amount = round(rand($catData['min'] * 10, $catData['max'] * 10) / 10, 2);
            $statuses = ['Approved', 'Paid', 'Approved', 'Pending', 'Paid'];
            $status = $statuses[$i % count($statuses)];
            $approver = in_array($status, ['Approved', 'Paid']) ? 35 : null;

            VehicleDailyExpense::create([
                'vehicle_id' => $dv['vehicle_id'],
                'driver_id' => $dv['driver_id'],
                'expense_date' => $expenseDate,
                'expense_category' => $catData['cat'],
                'amount' => $amount,
                'description' => $catData['desc'],
                'reimbursement_status' => $status,
                'approved_by' => $approver,
            ]);
        }

        // ---------------------------------------------------------------------
        // 3. SEED 30 REPAIR LOGS
        // ---------------------------------------------------------------------
        $this->command->info('Seeding 30 Vehicle Repair Logs...');

        // Spread across active vehicles (Vehicles 6, 7, 8, 10, 11, 25)
        $repairVehicles = [
            ['vehicle_id' => 7, 'driver_id' => 33],
            ['vehicle_id' => 6, 'driver_id' => 42],
            ['vehicle_id' => 8, 'driver_id' => 38],
            ['vehicle_id' => 25, 'driver_id' => 43],
            ['vehicle_id' => 10, 'driver_id' => 33],
            ['vehicle_id' => 11, 'driver_id' => 42],
        ];

        for ($i = 0; $i < 30; $i++) {
            $rv = $repairVehicles[$i % count($repairVehicles)];
            $garage = $garages[$i % count($garages)];
            $rData = $repairReasons[$i % count($repairReasons)];

            $dayOffset = 60 - intval(($i / 30) * 58);
            $repairDate = date('Y-m-d', strtotime("2026-11-20 - {$dayOffset} days"));
            $compDate = date('Y-m-d', strtotime("{$repairDate} + " . rand(0, 2) . " days"));

            $parts = $rData['parts'] + rand(-100, 200);
            $labor = $rData['labor'] + rand(-50, 100);
            $tax = round(($parts + $labor) * 0.09, 2);
            $totalCost = round($parts + $labor + $tax, 2);

            $invNo = 'INV-GA26-' . str_pad($i + 101, 5, '0', STR_PAD_LEFT);
            $payStatuses = ['Paid', 'Paid', 'Partially Paid', 'Paid', 'Unpaid'];
            $payStatus = $payStatuses[$i % count($payStatuses)];
            $payModes = ['Online_UPI', 'Cash', 'Bank_Transfer', 'Cheque'];

            $odoAtRepair = isset($currentOdometerMap[$rv['vehicle_id']])
                ? max(1000, $currentOdometerMap[$rv['vehicle_id']] - rand(200, 1500))
                : rand(8000, 15000);

            VehicleRepairLog::create([
                'vehicle_id' => $rv['vehicle_id'],
                'driver_id' => $rv['driver_id'],
                'repair_date' => $repairDate,
                'completion_date' => $compDate,
                'garage_workshop_name' => $garage['name'],
                'garage_contact_no' => $garage['phone'],
                'invoice_number' => $invNo,
                'repair_reason' => $rData['reason'],
                'issues_reported' => $rData['issues'],
                'work_performed' => $rData['work'],
                'spare_parts_cost' => $parts,
                'labor_charges' => $labor,
                'tax_amount' => $tax,
                'total_cost' => $totalCost,
                'odometer_at_repair' => $odoAtRepair,
                'payment_status' => $payStatus,
                'payment_mode' => $payModes[$i % count($payModes)],
            ]);
        }

        // ---------------------------------------------------------------------
        // 4. SEED 20 VEHICLE MAINTENANCE SCHEDULES
        // ---------------------------------------------------------------------
        $this->command->info('Seeding 20 Vehicle Maintenance Schedules...');

        $schedServices = [
            ['type' => 'Engine_Oil_Filter', 'km' => 5000, 'months' => 3],
            ['type' => 'Brake_Service', 'km' => 10000, 'months' => 6],
            ['type' => 'Tyre_Replacement_Rotation', 'km' => 15000, 'months' => 9],
            ['type' => 'Battery_Electrical', 'km' => 20000, 'months' => 12],
            ['type' => 'AC_Coolant_Service', 'km' => 10000, 'months' => 6],
        ];

        $allVehicleIds = [6, 7, 8, 10, 25];

        foreach ($allVehicleIds as $vId) {
            $baseOdo = $currentOdometerMap[$vId] ?? 12000;

            foreach ($schedServices as $srv) {
                $lastOdo = max(0, $baseOdo - rand(1500, 4000));
                $nextOdo = $lastOdo + $srv['km'];
                $lastDate = date('Y-m-d', strtotime('2026-11-20 - ' . rand(30, 90) . ' days'));
                $nextDate = date('Y-m-d', strtotime('2026-11-20 + ' . rand(15, 75) . ' days'));

                $status = ($nextOdo - $baseOdo < 500) ? 'Due_Soon' : 'Normal';

                VehicleMaintenanceSchedule::create([
                    'vehicle_id' => $vId,
                    'service_type' => $srv['type'],
                    'interval_km' => $srv['km'],
                    'interval_months' => $srv['months'],
                    'last_service_date' => $lastDate,
                    'last_service_odometer' => $lastOdo,
                    'next_due_date' => $nextDate,
                    'next_due_odometer' => $nextOdo,
                    'alert_advance_days' => 7,
                    'alert_advance_km' => 500,
                    'status' => $status,
                ]);
            }
        }

        // Update latest odometer on vehicle table
        foreach ($currentOdometerMap as $vId => $maxOdo) {
            Vehicle::where('id', $vId)->update(['current_odometer' => $maxOdo]);
        }

        $this->command->info('Fleet dummy data seeded successfully! 30 fuel logs, 30 daily expenses, 30 repair logs, and 25 maintenance schedules.');
    }
}
