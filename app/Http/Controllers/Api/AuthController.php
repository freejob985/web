<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:30',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'family_name' => $data['family_name'] ?? null,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'] ?? null,
        ]);
        Auth::login($user);
        $meResponse = $this->me($request);
        $meData = $meResponse->getData(true);
        
        // Generate a simple token for the user
        $token = $this->generateUserToken($user);
        
        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => $meData['user'],
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return response()->json(['success' => false, 'message' => 'بيانات الدخول غير صحيحة'], 422);
        }
        $request->session()->regenerate();
        $meResponse = $this->me($request);
        $meData = $meResponse->getData(true);
        
        // Generate a simple token for the user
        $user = Auth::user();
        $token = $this->generateUserToken($user);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => $meData['user'],
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['user' => null]);
        $user->load('addresses');
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'family_name' => $user->family_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'birth_date' => optional($user->birth_date)->toDateString(),
                'addresses' => $user->addresses->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'title' => $a->title,
                        'address' => $a->address,
                        'city' => $a->city,
                        'governorate' => $a->governorate,
                        'phone' => $a->phone,
                        'is_default' => (bool) $a->is_default,
                    ];
                })->values(),
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'birth_date' => 'nullable|date',
        ]);
        $user->update($data);
        return $this->me($request);
    }

    /**
     * Generate a simple authentication token for the user
     */
    private function generateUserToken($user)
    {
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => time(),
        ];
        
        $encodedPayload = base64_encode(json_encode($payload));
        $hash = substr(hash('sha256', $encodedPayload . config('app.key')), 0, 16);
        
        return $encodedPayload . '.' . $hash;
    }

    /**
     * Verify user token
     */
    public function verifyToken(Request $request)
    {
        $token = $request->header('Authorization') ?? $request->input('token');
        
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token not provided'], 401);
        }

        // Remove "Bearer " prefix if present
        $token = str_replace('Bearer ', '', $token);
        
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 2) {
                throw new \Exception('Invalid token format');
            }

            $payload = json_decode(base64_decode($parts[0]), true);
            $hash = $parts[1];

            // Verify hash
            $expectedHash = substr(hash('sha256', $parts[0] . config('app.key')), 0, 16);
            if ($hash !== $expectedHash) {
                throw new \Exception('Invalid token hash');
            }

            // Check if token is not too old (24 hours)
            if (time() - $payload['timestamp'] > 86400) {
                throw new \Exception('Token expired');
            }

            // Find user
            $user = User::find($payload['user_id']);
            if (!$user) {
                throw new \Exception('User not found');
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid token: ' . $e->getMessage()], 401);
        }
    }
}
