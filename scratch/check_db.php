<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "Column type: " . Schema::getColumnType('peminjamans', 'status') . "\n";
    // Check table info
    $result = DB::select("SHOW COLUMNS FROM peminjamans LIKE 'status'");
    print_r($result);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
