try {
    echo "--- Checking Users Table Columns ---\n";
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    print_r($columns);

    if (in_array('aps_access_token', $columns)) {
        echo "\nSUCCESS: 'aps_access_token' exists.\n";
    } else {
        echo "\nFAIL: 'aps_access_token' MISSING.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
