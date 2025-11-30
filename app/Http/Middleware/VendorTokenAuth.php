<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorTokenAuth
{
    /**
     * Handle an incoming request.
     * 
     * This middleware supports both session-based and token-based authentication
     * for vendors accessing the API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // First, try session-based authentication
        if (Auth::guard('vendor')->check()) {
            return $next($request);
        }

        // If session auth fails, try token-based authentication
        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please provide authentication token.'
            ], 401);
        }

        // Remove "Bearer " prefix if present
        $token = str_replace('Bearer ', '', $token);
        
        try {
            $vendor = $this->validateVendorToken($token);
            
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid authentication token.'
                ], 401);
            }

            // Check if vendor is active
            if (!$vendor->is_active || $vendor->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not active or not approved yet.'
                ], 403);
            }

            // Log the vendor in for this request
            Auth::guard('vendor')->setUser($vendor);
            
            // Also set the user in request for direct access
            $request->setUserResolver(function () use ($vendor) {
                return $vendor;
            });

            return $next($request);
            
        } catch (\Exception $e) {
            \Log::error('Vendor token authentication failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Validate vendor token and return vendor if valid
     *
     * @param string $token
     * @return \App\Models\Vendor|null
     */
    private function validateVendorToken(string $token): ?Vendor
    {
        try {
            $parts = explode('.', $token);
            
            if (count($parts) !== 2) {
                throw new \Exception('Invalid token format');
            }

            $payload = json_decode(base64_decode($parts[0]), true);
            $hash = $parts[1];

            if (!$payload || !isset($payload['vendor_id'])) {
                throw new \Exception('Invalid token payload');
            }

            // Verify hash
            $expectedHash = substr(hash('sha256', $parts[0] . config('app.key')), 0, 16);
            if ($hash !== $expectedHash) {
                throw new \Exception('Invalid token signature');
            }

            // Check if token is not too old (7 days = 604800 seconds)
            // زيادة مدة صلاحية Token من 24 ساعة إلى 7 أيام لتحسين تجربة المستخدم
            if (isset($payload['timestamp']) && (time() - $payload['timestamp']) > 604800) {
                throw new \Exception('Token has expired. Please login again.');
            }

            // Find and return vendor
            $vendor = Vendor::find($payload['vendor_id']);
            
            if (!$vendor) {
                throw new \Exception('Vendor not found');
            }

            return $vendor;
            
        } catch (\Exception $e) {
            \Log::warning('Token validation failed: ' . $e->getMessage());
            return null;
        }
    }
}

