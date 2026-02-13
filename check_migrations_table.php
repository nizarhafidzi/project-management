try {
    echo "--- Checking Migrations Table ---\n";
    $migrations = \Illuminate\Support\Facades\DB::table('migrations')->get();
    foreach ($migrations as $m) {
        echo $m->migration . " (Batch: " . $m->batch . ")\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
