<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AutodeskService
{
    protected $baseUrl = 'https://developer.api.autodesk.com';
    protected $clientId;
    protected $clientSecret;
    protected $callbackUrl;

    public function __construct()
    {
        $this->clientId = env('APS_CLIENT_ID');
        $this->clientSecret = env('APS_CLIENT_SECRET');
        $this->callbackUrl = env('APS_CALLBACK_URL');
    }

    // ==========================================
    // 1. PKCE & AUTHENTICATION
    // ==========================================

    /**
     * Generate PKCE Verifier and Challenge.
     */
    public function generatePkceParams()
    {
        $verifier = Str::random(128);
        $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
        
        return [
            'verifier' => $verifier,
            'challenge' => $challenge,
        ];
    }

    /**
     * Get Authorization URL with PKCE.
     */
    public function getAuthorizationUrl($codeChallenge)
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->callbackUrl,
            'scope' => 'data:read data:write viewables:read user:read account:write account:read data:create', // Added account:write for project creation
            'prompt' => 'login',
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ];
        
        return $this->baseUrl . '/authentication/v2/authorize?' . http_build_query($params);
    }

    /**
     * Exchange Code for Token (PKCE).
     */
    public function exchangeCodeForToken($code, $codeVerifier)
    {
        try {
            $response = Http::asForm()->post($this->baseUrl . '/authentication/v2/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret, 
                'redirect_uri' => $this->callbackUrl,
                'code_verifier' => $codeVerifier,
            ]);

            if ($response->failed()) {
                Log::error('Autodesk Auth Failed', $response->json());
                throw new \Exception('Failed to exchange code for token: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Autodesk Auth Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Store System Token (Master Account).
     */
    public function storeSystemToken(array $tokenData)
    {
        Setting::set('aps_master_access_token', $tokenData['access_token']);
        // Encrypt Refresh Token
        Setting::set('aps_master_refresh_token', Crypt::encryptString($tokenData['refresh_token']));
        Setting::set('aps_token_expires_at', now()->addSeconds($tokenData['expires_in'])->toIso8601String());
    }

    /**
     * Store User Token.
     */
    public function storeUserToken(User $user, array $tokenData)
    {
        $user->update([
            'aps_access_token' => $tokenData['access_token'],
            'aps_refresh_token' => $tokenData['refresh_token'], // User tokens not encrypted per user request? Or should be? Sticking to plan: only Master encrypted explicitly mentioned.
            'aps_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
        ]);
    }

    // ==========================================
    // 2. TOKEN RETRIEVAL & REFRESH
    // ==========================================

    /**
     * Get Valid System Token (Master).
     */
    public function getSystemToken()
    {
        $accessToken = Setting::get('aps_master_access_token');
        $expiresAt = Setting::get('aps_token_expires_at');

        if (!$accessToken || !$expiresAt) {
            throw new \Exception('System Master Account is not connected.');
        }

        // Check Expiry (with 5 minute buffer)
        if (Carbon::parse($expiresAt)->subMinutes(5)->isPast()) {
            return $this->refreshSystemToken();
        }

        return $accessToken;
    }

    /**
     * Refresh System Token.
     */
    public function refreshSystemToken()
    {
        try {
            $encryptedRefreshToken = Setting::get('aps_master_refresh_token');
            if (!$encryptedRefreshToken) {
                throw new \Exception('No refresh token available for System Account.');
            }

            $refreshToken = Crypt::decryptString($encryptedRefreshToken);

            $response = Http::asForm()->post($this->baseUrl . '/authentication/v2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->failed()) {
                Log::error('System Token Refresh Failed', $response->json());
                throw new \Exception('Failed to refresh System Token.');
            }

            $data = $response->json();
            $this->storeSystemToken($data);

            return $data['access_token'];

        } catch (\Exception $e) {
            Log::error('System Token Refresh Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Valid User Token.
     */
    public function getUserToken(User $user)
    {
        if (!$user->aps_access_token || !$user->aps_token_expires_at) {
            return null;
        }

        // Check Expiry
        if (Carbon::parse($user->aps_token_expires_at)->subMinutes(5)->isPast()) {
            return $this->refreshUserToken($user);
        }

        return $user->aps_access_token;
    }

    /**
     * Refresh User Token.
     */
    public function refreshUserToken(User $user)
    {
        try {
            if (!$user->aps_refresh_token) {
                return null;
            }

            $response = Http::asForm()->post($this->baseUrl . '/authentication/v2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->aps_refresh_token,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->failed()) {
                Log::error('User Token Refresh Failed: User ID ' . $user->id, $response->json());
                return null;
            }

            $data = $response->json();
            $this->storeUserToken($user, $data);

            return $data['access_token'];

        } catch (\Exception $e) {
            Log::error('User Token Refresh Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get Valid Viewer Token (Fallback Logic).
     */
    public function getValidViewerToken(User $user = null)
    {
        // 1. Try User Token
        if ($user) {
            $userToken = $this->getUserToken($user);
            if ($userToken) {
                return $userToken;
            }
        }

        // 2. Fallback to System Token
        try {
            return $this->getSystemToken();
        } catch (\Exception $e) {
            // 3. System Offline
            Log::critical('Autodesk Viewer Offline: Both User and System tokens failed.');
            throw new \Exception('System is Offline: Unable to access Autodesk Viewer services.');
        }
    }

    // ==========================================
    // 3. API PROXIES (Hubs, Projects, Data)
    // ==========================================

    public function getHubs(User $user = null)
    {
        try {
            $token = $this->getValidViewerToken($user);
            $response = Http::withToken($token)->get($this->baseUrl . '/project/v1/hubs');
            return $response->json()['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Get Hubs Failed: ' . $e->getMessage());
            return [];
        }
    }

    public function getProjects(User $user, $hubId)
    {
        try {
            $token = $this->getValidViewerToken($user);
            $response = Http::withToken($token)->get($this->baseUrl . "/project/v1/hubs/{$hubId}/projects");
            return $response->json()['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Get Projects Failed: ' . $e->getMessage());
            return [];
        }
    }

    public function getTopFolders(User $user, $hubId, $projectId)
    {
        return \Illuminate\Support\Facades\Cache::remember("acc_top_folders_{$projectId}", 300, function () use ($user, $hubId, $projectId) {
            try {
                $token = $this->getValidViewerToken($user);
                $url = $this->baseUrl . "/project/v1/hubs/{$hubId}/projects/{$projectId}/topFolders";
                $response = Http::withToken($token)->get($url);
    
                return $this->formatBrowserItems($response->json()['data'] ?? []);
            } catch (\Exception $e) {
                Log::error('Get Top Folders Failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    public function getFolderContents(User $user, $projectId, $folderId)
    {
        return \Illuminate\Support\Facades\Cache::remember("acc_folder_{$folderId}", 300, function () use ($user, $projectId, $folderId) {
            try {
                $token = $this->getValidViewerToken($user);
                $url = $this->baseUrl . "/data/v1/projects/{$projectId}/folders/{$folderId}/contents";
                $response = Http::withToken($token)->get($url);
    
                return $this->formatBrowserItems($response->json()['data'] ?? []);
            } catch (\Exception $e) {
                Log::error('Get Folder Contents Failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    private function formatBrowserItems(array $items)
    {
        return collect($items)->map(function($item) {
            $type = $item['attributes']['extension']['type'] ?? 'folders:autodesk.bim360:Folder';
            $urn = $item['relationships']['tip']['data']['id'] ?? $item['id'];

            return [
                'id' => $item['id'],
                'type' => $type, 
                'name' => $item['attributes']['displayName'] ?? 'Unknown',
                'urn' => $urn, 
            ];
        })->toArray();
    }

    // ==========================================
    // 4. MODEL DERIVATIVE (Viewer & Properties)
    // ==========================================
    
    private function makeSafeUrn($urn)
    {
        if (str_starts_with($urn, 'urn:')) $urn = base64_encode($urn);
        return rtrim(strtr($urn, '+/', '-_'), '=');
    }

    public function getManifest($urn, User $user = null)
    {
        try {
            $token = $this->getValidViewerToken($user);
            $safeUrn = $this->makeSafeUrn($urn);
            return Http::withToken($token)
                ->get($this->baseUrl . "/modelderivative/v2/designdata/{$safeUrn}/manifest")
                ->json();
        } catch (\Exception $e) {
             Log::error('Get Manifest Failed: ' . $e->getMessage());
             return null;
        }
    }

    public function translateToSvf($urn, User $user = null)
    {
        try {
            $token = $this->getValidViewerToken($user);
            $safeUrn = $this->makeSafeUrn($urn);
            $body = [
                'input' => ['urn' => $safeUrn],
                'output' => [['type' => 'svf', 'views' => ['2d', '3d']]],
                'destination' => ['region' => 'us']
            ];
            return Http::withToken($token)->withHeaders(['x-ads-force' => 'true'])
                ->post($this->baseUrl . "/modelderivative/v2/designdata/job", $body)->json();
        } catch (\Exception $e) {
            Log::error('Translate to SVF Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchModelProperties($urn, User $user = null)
    {
        try {
            $token = $this->getValidViewerToken($user);
            $safeUrn = $this->makeSafeUrn($urn);

            // A. Cari View GUID (3D)
            $metaResponse = Http::withToken($token)->get($this->baseUrl . "/modelderivative/v2/designdata/{$safeUrn}/metadata");
            if ($metaResponse->failed()) return ['status' => 'error', 'message' => 'Gagal ambil metadata view.'];

            $views = $metaResponse->json()['data']['metadata'] ?? [];
            $viewGuid = null;

            // Prioritas 1: Cari role '3d'
            foreach ($views as $view) {
                if (isset($view['role']) && $view['role'] === '3d') {
                    $viewGuid = $view['guid'];
                    break; 
                }
            }

            // Prioritas 2: Fallback ke view pertama
            if (!$viewGuid && count($views) > 0) {
                $viewGuid = $views[0]['guid'];
            }

            if (!$viewGuid) {
                return ['status' => 'error', 'message' => "Tidak ditemukan View 3D. Total views: " . count($views)];
            }

            Log::info(" Selected View GUID: $viewGuid for URN: $safeUrn");

            // B. Coba Download Langsung
            $url = $this->baseUrl . "/modelderivative/v2/designdata/{$safeUrn}/metadata/{$viewGuid}/properties";
            
            $propResponse = Http::withToken($token)
                ->timeout(600) 
                ->get($url . '?forceget=true');

            // C. JIKA ERROR 413 (Payload Too Large) -> PINDAH KE CHUNKING
            if ($propResponse->status() == 413) {
                Log::info("⚠️ Payload Too Large (413). Switching to Chunking Mode...");
                return $this->fetchPropertiesChunked($safeUrn, $viewGuid, $token);
            }

            if ($propResponse->status() == 202) {
                return ['status' => 'processing', 'message' => "Autodesk sedang indexing properties (202)."];
            }

            if ($propResponse->failed()) {
                 return ['status' => 'error', 'message' => "Gagal download properties. Code: " . $propResponse->status()];
            }

            // D. Sukses Direct Download
            return ['status' => 'success', 'data' => $propResponse->json()];
        } catch (\Exception $e) {
            Log::error('Fetch Model Properties Failed: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function fetchPropertiesChunked($urn, $viewGuid, $token)
    {
        try {
            // 1. Ambil Object Tree (Daftar ID)
            $treeUrl = $this->baseUrl . "/modelderivative/v2/designdata/{$urn}/metadata/{$viewGuid}";
            $treeResp = Http::withToken($token)->get($treeUrl . '?forceget=true');
            
            if ($treeResp->failed()) {
                return ['status' => 'error', 'message' => 'Gagal ambil Object Tree untuk chunking: ' . $treeResp->status()];
            }
            
            // 2. Ratakan Tree
            $allIds = [];
            $objects = $treeResp->json()['data']['objects'] ?? [];
            $this->flattenObjectIds($objects, $allIds);
            
            if (count($allIds) == 0) {
                 return ['status' => 'error', 'message' => 'Object Tree kosong.'];
            }

            // 3. Download per Batch
            $chunks = array_chunk($allIds, 200); 
            $combinedCollection = [];
            
            foreach ($chunks as $index => $chunkIds) {
                $postUrl = $this->baseUrl . "/modelderivative/v2/designdata/{$urn}/metadata/{$viewGuid}/properties";
                
                try {
                    $batchResp = Http::withToken($token)->post($postUrl, [
                        'objectids' => $chunkIds
                    ]);

                    if ($batchResp->successful()) {
                        $batchData = $batchResp->json()['data']['collection'] ?? [];
                        $combinedCollection = array_merge($combinedCollection, $batchData);
                    }
                } catch (\Exception $e) {
                    Log::warning("⚠️ Exception on batch $index: " . $e->getMessage());
                }
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'data' => [
                        'collection' => $combinedCollection
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Fetch Properties Chunked Failed: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function flattenObjectIds($nodes, &$ids)
    {
        foreach ($nodes as $node) {
            if (isset($node['objectid'])) {
                $ids[] = $node['objectid'];
            }
            if (isset($node['objects'])) {
                $this->flattenObjectIds($node['objects'], $ids);
            }
        }
    }
    // ==========================================
    // 5. ADMIN & PROJECT MANAGEMENT
    // ==========================================

    /**
     * Create New Project in ACC.
     */
    public function createProject(User $user, $accountId, array $data)
    {
        try {
            $token = $this->getValidViewerToken($user);
            
            // Standardize Payaload
            $payload = [
                'name' => $data['name'],
                'type' => 'Construction Management', // 'type' is required, 'project_type' was rejected
                // 'service_types' => 'doc_manager', // Rejected by API
                // 'start_date' => $data['start_date'], // Rejected by API
                // 'end_date' => $data['end_date'], // Rejected by API
                // 'currency' => 'USD', // Rejected by API
                // 'language' => 'en' // Rejected by API
            ];

            // ACC often uses /construction/admin/v1... or BIM 360 endpoints.
            // The user specified: POST /construction/admin/v1/accounts/:account_id/projects
            $url = $this->baseUrl . "/construction/admin/v1/accounts/{$accountId}/projects";

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->failed()) {
                Log::error('Create Project Failed', $response->json());
                
                // Specific error handling for duplicates
                if ($response->status() === 409) {
                     throw new \Exception('Project name already exists in this Account/Hub.');
                }
                
                throw new \Exception('Failed to create project: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Create Project Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Activate Project Service (Docs).
     */
    public function activateProjectService(User $user, $accountId, $projectId, $email)
    {
        try {
            $token = $this->getValidViewerToken($user);
            
            // HQ API Pattern: POST /hq/v1/accounts/:account_id/projects/:project_id/services
            $url = $this->baseUrl . "/hq/v1/accounts/{$accountId}/projects/{$projectId}/services";
             
            $body = [
                'serviceId' => 'docs', 
                'status' => 'active',
                'adminEmail' => $email
            ];
             
            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $body);
                
            if ($response->failed()) {
                Log::error('Activate Service Failed', $response->json());
                throw new \Exception('Failed to activate Docs service: ' . $response->body());
            }
             
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Activate Service Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Project Status.
     */
    public function getProjectStatus(User $user, $accountId, $projectId)
    {
        try {
            $token = $this->getValidViewerToken($user);
            // Unified Admin Get Project Endpoint
            $url = $this->baseUrl . "/construction/admin/v1/accounts/{$accountId}/projects/{$projectId}";

            $response = Http::withToken($token)->get($url);

            if ($response->failed()) {
                // 404 is expected during initial provisioning
                if ($response->status() === 404) {
                    return 'not_found';
                }
                
                Log::error('Get Project Status Failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            return $response->json()['status'] ?? null;
        } catch (\Exception $e) {
            Log::error('Get Project Status Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Wait for Project to become Active (Polling).
     */
    public function waitForProjectActive(User $user, $accountId, $projectId, $maxRetries = 10, $delaySeconds = 3)
    {
        for ($i = 0; $i < $maxRetries; $i++) {
            $status = $this->getProjectStatus($user, $accountId, $projectId);
            
            Log::info("Polling Project Status (Attempt " . ($i + 1) . "/{$maxRetries}): {$status}");

            if ($status === 'active') {
                return true;
            }

            sleep($delaySeconds);
        }

        throw new \Exception("Project activation timed out after " . ($maxRetries * $delaySeconds) . " seconds. Last status: " . ($status ?? 'unknown'));
    }

    /**
     * Add Project Admin (Replaces generic service activation).
     */
    public function addProjectAdmin(User $user, $projectId, $email)
    {
        try {
            $token = $this->getValidViewerToken($user);
            
            // Endpoint: POST /construction/admin/v1/projects/:project_id/users
            $url = $this->baseUrl . "/construction/admin/v1/projects/{$projectId}/users";
             
            $body = [
                [
                    'email' => $email,
                    'products' => [
                        [ 'key' => 'projectAdministration', 'access' => 'admin' ],
                        [ 'key' => 'docs', 'access' => 'admin' ]
                    ]
                ]
            ];
            
            // Note: The structure for 'users' endpoint is typically an array of user objects.
            // Verified docs: POST /projects/{projectId}/users accepts an array.
             
            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $body);
                
            if ($response->failed()) {
                Log::error('Add Project Admin Failed', $response->json());
                throw new \Exception('Failed to add project admin: ' . $response->body());
            }
             
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Add Project Admin Exception: ' . $e->getMessage());
            throw $e;
        }
    }
    // ==========================================
    // 6. VERSION CONTROL
    // ==========================================

    public function checkFileVersion(User $user, $projectId, $versionUrn)
    {
        try {
            $token = $this->getValidViewerToken($user);
            
            // 1. Get Version Details to find Item ID
            // Urn must be URL encoded
            $versionId = urlencode($versionUrn); 
            $url = $this->baseUrl . "/data/v1/projects/{$projectId}/versions/{$versionId}";
            
            $response = Http::withToken($token)->get($url);
            
            if ($response->failed()) {
                 Log::error('Check Version Failed (Step 1)', $response->json());
                 return null;
            }
            
            $data = $response->json();
            $itemId = $data['data']['relationships']['item']['data']['id'] ?? null;
            $currentVersionNumber = $data['data']['attributes']['versionNumber'] ?? null;

            if (!$itemId) {
                return null;
            }
            
            // 2. Get Item Details with Tip (Latest Version)
            $itemUrl = $this->baseUrl . "/data/v1/projects/{$projectId}/items/{$itemId}";
            $itemResponse = Http::withToken($token)->get($itemUrl . "?include=tip");
            
            if ($itemResponse->failed()) {
                 Log::error('Check Version Failed (Step 2)', $itemResponse->json());
                 return null;
            }
            
            $itemData = $itemResponse->json();
            $tipUrn = $itemData['data']['relationships']['tip']['data']['id'] ?? null;
            
            if (!$tipUrn) {
                return null;
            }

            // 3. Find Version Number from Included
            $included = $itemData['included'] ?? [];
            $latestVersion = null;
            
            foreach ($included as $inc) {
                if ($inc['id'] === $tipUrn) {
                    $latestVersion = $inc['attributes']['versionNumber'];
                    break;
                }
            }

            return [
                'urn' => $tipUrn,
                'version' => $latestVersion,
                'current_version' => $currentVersionNumber // Return current just in case
            ];

        } catch (\Exception $e) {
             Log::error('Check File Version Exception: ' . $e->getMessage());
             return null;
        }
    }
}


