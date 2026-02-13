try {
    echo "--- Testing Setting Model ---\n";
    \App\Models\Setting::set('test_key', 'test_verification_value');
    $val = \App\Models\Setting::get('test_key');
    echo "Reading 'test_key': " . ($val === 'test_verification_value' ? "PASS" : "FAIL ($val)") . "\n";

    echo "\n--- Testing AutodeskService ---\n";
    $service = new \App\Services\AutodeskService();
    $pkce = $service->generatePkceParams();
    
    if (strlen($pkce['verifier']) === 128) {
        echo "PKCE Verifier Length (128): PASS\n";
    } else {
        echo "PKCE Verifier Length: FAIL (" . strlen($pkce['verifier']) . ")\n";
    }

    if (!empty($pkce['challenge'])) {
        echo "PKCE Challenge Generated: PASS\n";
    } else {
        echo "PKCE Challenge: FAIL\n";
    }

    $url = $service->getAuthorizationUrl($pkce['challenge']);
    echo "Auth URL Generated: " . (filter_var($url, FILTER_VALIDATE_URL) ? "PASS" : "FAIL") . "\n";
    echo "URL: " . substr($url, 0, 50) . "...\n";

    echo "\n--- Verification Complete ---\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
