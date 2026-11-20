<?php
require 'c:/laragon/www/desai/vendor/autoload.php';
$app = require_once 'c:/laragon/www/desai/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach (App\Models\Admin::where('role_id', 4)->get() as $w) {
    $dw = App\Models\DriverWarehouse::where('warehouse_id', $w->id)->get();
    echo "WH: {$w->id} - {$w->name} => Drivers: " . $dw->pluck('driver_id')->implode(', ') . "\n";
    foreach ($dw as $item) {
        $driver = App\Models\Admin::find($item->driver_id);
        echo "   Driver: {$driver->id} - {$driver->name} | vehicle_id: " . var_export($driver->vehicle_id, true) . "\n";
    }
}
