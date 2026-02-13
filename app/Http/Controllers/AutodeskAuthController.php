<?php

namespace App\Http\Controllers;

use App\Services\AutodeskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AutodeskAuthController extends Controller
{
    protected $apsService;

    public function __construct(AutodeskService $apsService)
    {
        $this->apsService = $apsService;
    }

    // 1. Connect Master Account (Admin Only)
    public function connectMaster()
    {
        if (!Auth::user()->hasRole('superadmin')) { // Assuming 'superadmin' role check
            abort(403, 'Unauthorized.');
        }

        $pkce = $this->apsService->generatePkceParams();
        
        Session::put('aps_auth_mode', 'master');
        Session::put('aps_pkce_verifier', $pkce['verifier']);

        return redirect()->away($this->apsService->getAuthorizationUrl($pkce['challenge']));
    }

    // 2. Connect User Account (Personal)
    public function connectUser()
    {
        $pkce = $this->apsService->generatePkceParams();
        
        Session::put('aps_auth_mode', 'user');
        Session::put('aps_pkce_verifier', $pkce['verifier']);

        return redirect()->away($this->apsService->getAuthorizationUrl($pkce['challenge']));
    }

    // 3. Callback
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect('/dashboard')->with('error', 'Authorization denied.');
        }

        $code = $request->code;
        $verifier = Session::get('aps_pkce_verifier');
        $mode = Session::get('aps_auth_mode');

        if (!$verifier) {
            Log::error('Autodesk Auth: Session (aps_pkce_verifier) missing.');
            return redirect('/dashboard')->with('error', 'Session expired. Please try again.');
        }

        Log::info('Autodesk Auth: Verifier found. Mode: ' . $mode);

        try {
            $tokenData = $this->apsService->exchangeCodeForToken($code, $verifier);
            Log::info('Autodesk Auth: Token exchanged successfully.');

            if ($mode === 'master') {
                if (!Auth::user()->hasRole('superadmin')) {
                    abort(403, 'Unauthorized action.');
                }
                $this->apsService->storeSystemToken($tokenData);
                $message = 'System Master Account connected successfully!';
            } else {
                $this->apsService->storeUserToken(Auth::user(), $tokenData);
                $message = 'Personal Autodesk Account connected successfully!';
            }

            // Cleanup Session
            Session::forget(['aps_pkce_verifier', 'aps_auth_mode']);

            return redirect('/dashboard')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Autodesk Callback Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Failed to connect: ' . $e->getMessage());
        }
    }

    // 4. Disconnect (Optional but good to have)
    public function disconnect()
    {
        $user = Auth::user();
        $user->update([
            'aps_access_token' => null,
            'aps_refresh_token' => null,
            'aps_token_expires_at' => null,
        ]);

        return back()->with('success', 'Disconnected from Autodesk successfully.');
    }
}
