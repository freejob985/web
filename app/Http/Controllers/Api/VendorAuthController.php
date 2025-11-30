<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:30',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'commercial_record' => 'required|string|unique:vendors,commercial_record',
            'tax_number' => 'required|string|max:50',
            'bank_account' => 'required|string|max:50',
            'bank_name' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'governorate' => 'required|string|max:100',
            'business_categories' => 'required|array|min:1',
            'business_description' => 'nullable|string|max:1000',
            // New fields
            'postal_code' => 'nullable|string|max:20',
            'business_category_id' => 'nullable|integer|exists:business_categories,id',
            'description' => 'nullable|string|max:1000',
            'delivery_fee' => 'nullable|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'governorate_id' => 'nullable|integer|exists:governorates,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'is_featured' => 'nullable|boolean',
            'is_fresh' => 'nullable|boolean',
            'category' => 'nullable|string|max:100'
        ]);

        $vendor = Vendor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'business_name' => $data['business_name'],
            'business_type' => $data['business_type'],
            'commercial_record' => $data['commercial_record'],
            'tax_number' => $data['tax_number'],
            'bank_account' => $data['bank_account'],
            'bank_name' => $data['bank_name'],
            'address' => $data['address'],
            'city' => $data['city'],
            'governorate' => $data['governorate'],
            'business_categories' => $data['business_categories'],
            'business_description' => $data['business_description'] ?? null,
            // New fields
            'postal_code' => $data['postal_code'] ?? null,
            'business_category_id' => $data['business_category_id'] ?? null,
            'description' => $data['description'] ?? null,
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'free_delivery_threshold' => $data['free_delivery_threshold'] ?? 0,
            'governorate_id' => $data['governorate_id'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'is_fresh' => $data['is_fresh'] ?? false,
            'category' => $data['category'] ?? null,
            'status' => 'pending',
            'is_active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء حسابك بنجاح. سيتم مراجعة طلبك والتواصل معك قريباً.',
            'data' => [
                'vendor' => [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'business_name' => $vendor->business_name,
                    'status' => $vendor->status,
                    'is_active' => $vendor->is_active,
                    'is_featured' => $vendor->is_featured,
                    'is_fresh' => $vendor->is_fresh,
                    'category' => $vendor->category,
                    'description' => $vendor->description,
                    'delivery_fee' => $vendor->delivery_fee,
                    'free_delivery_threshold' => $vendor->free_delivery_threshold,
                    'created_at' => $vendor->created_at?->toDateTimeString(),
                ],
                'token' => null // No token for registration, vendor needs to login
            ]
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('vendor')->attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return response()->json(['success' => false, 'message' => 'بيانات الدخول غير صحيحة'], 422);
        }

        $request->session()->regenerate();
        $meResponse = $this->me($request);
        $meData = $meResponse->getData(true);
        
        // Generate a simple token for the vendor
        $vendor = Auth::guard('vendor')->user();
        $token = $this->generateVendorToken($vendor);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'vendor' => $meData['vendor'],
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function me(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        if (!$vendor) return response()->json(['success' => false, 'vendor' => null]);

        return response()->json([
            'success' => true,
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'email' => $vendor->email,
                'phone' => $vendor->phone,
                'business_name' => $vendor->business_name,
                'business_type' => $vendor->business_type,
                'commercial_record' => $vendor->commercial_record,
                'tax_number' => $vendor->tax_number,
                'bank_account' => $vendor->bank_account,
                'bank_name' => $vendor->bank_name,
                'address' => $vendor->address,
                'city' => $vendor->city,
                'governorate' => $vendor->governorate,
                'business_categories' => $vendor->business_categories,
                'business_description' => $vendor->business_description,
                'status' => $vendor->status,
                'is_active' => $vendor->is_active,
                'is_featured' => $vendor->is_featured,
                'rating' => $vendor->rating,
                'created_at' => $vendor->created_at?->toDateTimeString(),
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 401);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:30',
            'address' => 'sometimes|required|string|max:500',
            'city' => 'sometimes|required|string|max:100',
            'governorate' => 'sometimes|required|string|max:100',
            'business_categories' => 'sometimes|required|array',
            'business_description' => 'nullable|string|max:1000'
        ]);

        $vendor->update($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'email' => $vendor->email,
                'phone' => $vendor->phone,
                'address' => $vendor->address,
                'city' => $vendor->city,
                'governorate' => $vendor->governorate,
                'business_categories' => $vendor->business_categories,
                'business_description' => $vendor->business_description,
                'status' => $vendor->status,
            ]
        ]);
    }

    /**
     * Generate a simple token for vendor authentication
     */
    private function generateVendorToken($vendor)
    {
        // Create a simple token using vendor ID, email, and timestamp
        $payload = [
            'vendor_id' => $vendor->id,
            'email' => $vendor->email,
            'timestamp' => time(),
            'type' => 'vendor_auth'
        ];
        
        // Encode the payload as base64
        $token = base64_encode(json_encode($payload));
        
        // Add a simple hash for security
        $hash = hash('sha256', $token . config('app.key'));
        $token = $token . '.' . substr($hash, 0, 16);
        
        return $token;
    }

    /**
     * Validate vendor token
     */
    public function validateToken(Request $request)
    {
        $token = $request->header('Authorization');
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

            // Find vendor
            $vendor = Vendor::find($payload['vendor_id']);
            if (!$vendor) {
                throw new \Exception('Vendor not found');
            }

            return response()->json([
                'success' => true,
                'vendor' => [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'phone' => $vendor->phone,
                    'business_name' => $vendor->business_name,
                    'status' => $vendor->status,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid token: ' . $e->getMessage()], 401);
        }
    }
}
