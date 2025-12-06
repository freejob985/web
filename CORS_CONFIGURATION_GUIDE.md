# 🔒 Laravel CORS Configuration Guide - Production Ready

## ✅ Configuration Complete

Your Laravel API has been configured to handle CORS requests from your frontend domains.

---

## 📋 Summary of Changes

### 1. **config/cors.php** - Updated ✅

The CORS configuration now includes your production domains:

```php
'allowed_origins' => [
    // Production domains
    'https://eliteonegrocery.com',
    'https://www.eliteonegrocery.com',
    'https://adminxd.eliteonegrocery.com',
    
    // Development domains
    'http://localhost:5173',
    'http://localhost:5174',
    // ... other local domains
],
```

### 2. **app/Http/Kernel.php** - Already Correct ✅

The CORS middleware is already enabled in your global middleware stack:

```php
protected $middleware = [
    // ...
    \Illuminate\Http\Middleware\HandleCors::class,  // ✅ Line 19
    // ...
];
```

**No changes needed** - Laravel 11's built-in CORS middleware is active.

---

## 🚀 Deployment Steps

### Step 1: Clear Configuration Cache

After making changes to `config/cors.php`, you **must** clear Laravel's configuration cache:

```bash
# Navigate to your Laravel project directory
cd /path/to/laravel/project

# Clear configuration cache
php artisan config:clear

# Clear application cache
php artisan cache:clear

# Optional: Clear route cache (if using route caching)
php artisan route:clear

# Optional: Recreate optimized config cache for production
php artisan config:cache
```

### Step 2: Deploy to Production Server

Upload the updated `config/cors.php` file to your server at:
- `https://adminxd.eliteonegrocery.com`

### Step 3: Run Artisan Commands on Server

SSH into your production server and run:

```bash
cd /home/u929533639/domains/eliteonegrocery.com/public_html/ad

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Optimize for production (this will cache the new config)
php artisan config:cache
php artisan route:cache
```

---

## 🔍 Complete CORS Configuration Breakdown

Here's your full `config/cors.php` file with explanations:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // 1. PATHS - Which routes should have CORS headers
    'paths' => [
        'api/*',                    // All API routes (/api/v1/*, /api/*)
        'sanctum/csrf-cookie'       // Laravel Sanctum CSRF cookie endpoint
    ],

    // 2. ALLOWED METHODS - HTTP methods accepted
    'allowed_methods' => ['*'],     // GET, POST, PUT, PATCH, DELETE, OPTIONS

    // 3. ALLOWED ORIGINS - Which domains can make requests
    'allowed_origins' => [
        'https://eliteonegrocery.com',          // Main production frontend
        'https://www.eliteonegrocery.com',      // WWW variant
        'https://adminxd.eliteonegrocery.com',  // Admin panel
        // ... development domains
    ],

    // 4. ALLOWED ORIGINS PATTERNS - Regex patterns (not used)
    'allowed_origins_patterns' => [],

    // 5. ALLOWED HEADERS - Headers the frontend can send
    'allowed_headers' => ['*'],     // Authorization, Content-Type, etc.

    // 6. EXPOSED HEADERS - Headers the browser can access
    'exposed_headers' => [],

    // 7. MAX AGE - Preflight cache duration (seconds)
    'max_age' => 0,                 // 0 = no caching (can set to 3600 for 1 hour)

    // 8. SUPPORTS CREDENTIALS - Allow cookies/auth headers
    'supports_credentials' => true, // Required for Sanctum/session auth
];
```

---

## ⚠️ Important: CORS + Credentials

### Why `supports_credentials => true`?

Your current configuration has:
```php
'supports_credentials' => true,
```

**This is correct** because:
- ✅ You're using Laravel Sanctum for authentication
- ✅ Your frontend needs to send cookies/session data
- ✅ You need the `Authorization` header to work

### ❌ Common Pitfall: Using `*` with Credentials

**NEVER do this:**
```php
'allowed_origins' => ['*'],          // ❌ WRONG
'supports_credentials' => true,      // ❌ CONFLICT!
```

**Why?** Browsers reject the combination of:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Credentials: true`

**Always specify exact domains** (as we've done) when using credentials.

---

## 🧪 Testing & Verification

### Method 1: Browser DevTools (Recommended)

1. **Open your frontend**: https://eliteonegrocery.com
2. **Open DevTools**: Press `F12` or `Ctrl+Shift+I` (Windows) / `Cmd+Option+I` (Mac)
3. **Go to Network tab**
4. **Refresh the page** and observe API requests

#### ✅ What to Look For:

**1. Preflight OPTIONS Request**

For each API call, you should see an OPTIONS request first:

```
Request:
  Method: OPTIONS
  URL: https://adminxd.eliteonegrocery.com/api/v1/settings/general

Response Headers:
  Access-Control-Allow-Origin: https://eliteonegrocery.com
  Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
  Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With
  Access-Control-Allow-Credentials: true
  Status: 204 No Content
```

**2. Actual Request (GET/POST/etc.)**

After the preflight succeeds, the actual request executes:

```
Request:
  Method: GET
  URL: https://adminxd.eliteonegrocery.com/api/v1/settings/general

Response Headers:
  Access-Control-Allow-Origin: https://eliteonegrocery.com
  Access-Control-Allow-Credentials: true
  Content-Type: application/json
  Status: 200 OK
```

#### ❌ What Indicates a Problem:

**Missing CORS Headers:**
```
Response Headers:
  Content-Type: application/json
  [NO Access-Control-Allow-Origin header]  ❌ PROBLEM
```

**Wrong Origin:**
```
Access-Control-Allow-Origin: https://wrong-domain.com  ❌ PROBLEM
```

**Failed Preflight:**
```
Status: 403 Forbidden  ❌ PROBLEM
or
Status: 500 Internal Server Error  ❌ PROBLEM
```

### Method 2: cURL Testing

Test from your terminal to verify CORS headers:

```bash
# Test preflight OPTIONS request
curl -i -X OPTIONS \
  -H "Origin: https://eliteonegrocery.com" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type, Authorization" \
  https://adminxd.eliteonegrocery.com/api/v1/auth/login

# Expected response should include:
# Access-Control-Allow-Origin: https://eliteonegrocery.com
# Access-Control-Allow-Credentials: true
# Access-Control-Allow-Methods: ...
```

```bash
# Test actual GET request
curl -i -X GET \
  -H "Origin: https://eliteonegrocery.com" \
  https://adminxd.eliteonegrocery.com/api/v1/settings/general

# Expected response should include:
# Access-Control-Allow-Origin: https://eliteonegrocery.com
```

### Method 3: Online CORS Tester

Visit: https://www.test-cors.org/

- **Remote URL**: `https://adminxd.eliteonegrocery.com/api/v1/settings/general`
- **HTTP Method**: GET
- Click "Send Request"
- Should see: ✅ "CORS request succeeded!"

---

## ✅ Testing Checklist

After deployment, verify:

- [ ] Configuration cache cleared on server (`php artisan config:clear`)
- [ ] Configuration optimized (`php artisan config:cache`)
- [ ] Browser console shows NO CORS errors
- [ ] Network tab shows OPTIONS requests with status `204` or `200`
- [ ] Network tab shows `Access-Control-Allow-Origin: https://eliteonegrocery.com` header
- [ ] Network tab shows `Access-Control-Allow-Credentials: true` header
- [ ] Actual API requests (GET/POST) complete successfully
- [ ] Authentication works (if using Sanctum/sessions)
- [ ] Frontend can successfully fetch data from API

---

## 🐛 Troubleshooting

### Issue: Still Getting CORS Errors After Changes

**Possible Causes:**

1. **Cache Not Cleared**
   ```bash
   # Clear ALL caches
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Old Config Still Cached**
   ```bash
   # Delete cached config file manually
   rm bootstrap/cache/config.php
   
   # Then recreate
   php artisan config:cache
   ```

3. **Browser Cache**
   - Hard refresh: `Ctrl+F5` (Windows) / `Cmd+Shift+R` (Mac)
   - Or open DevTools → Network tab → Check "Disable cache"

4. **Wrong Domain/Subdomain**
   - Ensure frontend is exactly `https://eliteonegrocery.com`
   - Not `http://` (insecure) or `www.` variant
   - If using `www.`, ensure it's in `allowed_origins`

5. **Reverse Proxy/CDN Issues**
   - If using Cloudflare/CDN, ensure it's not stripping CORS headers
   - Check CDN cache purge

### Issue: 403 Forbidden on OPTIONS Request

**Cause:** Middleware blocking preflight requests

**Solution:** Ensure CORS middleware is positioned correctly in `Kernel.php`:

```php
protected $middleware = [
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,  // ✅ MUST be early
    // ... other middleware
];
```

### Issue: Credentials Not Working

**Cause:** Frontend not sending credentials

**Solution:** Ensure your frontend uses:

```javascript
// Fetch API
fetch('https://adminxd.eliteonegrocery.com/api/v1/...', {
  credentials: 'include',  // ✅ Required
  headers: {
    'Content-Type': 'application/json',
  }
});

// Axios
axios.defaults.withCredentials = true;  // ✅ Required
```

---

## 📊 CORS Flow Diagram

```
Frontend (https://eliteonegrocery.com)
         |
         | 1. Send OPTIONS request (Preflight)
         ↓
Backend (https://adminxd.eliteonegrocery.com/api/v1/...)
         |
         | 2. Check CORS config → allowed_origins
         |
         | 3. Add CORS headers:
         |    - Access-Control-Allow-Origin
         |    - Access-Control-Allow-Methods
         |    - Access-Control-Allow-Headers
         |    - Access-Control-Allow-Credentials
         ↓
         | 4. Return 204 No Content
         |
Frontend receives 204 ✅
         |
         | 5. Send actual request (GET/POST/etc.)
         ↓
Backend processes request
         |
         | 6. Add CORS headers to response
         ↓
Frontend receives data ✅
```

---

## 🔐 Security Best Practices

### ✅ DO:

- ✅ **Specify exact domains** in `allowed_origins` (as we've done)
- ✅ **Use HTTPS** in production for both frontend and API
- ✅ **Keep `supports_credentials => true`** for authenticated requests
- ✅ **Limit `allowed_headers`** if you know exactly which headers you need
- ✅ **Set `max_age`** to cache preflight (e.g., 3600) for better performance
- ✅ **Use environment variables** for domain configuration in production

### ❌ DON'T:

- ❌ **Never use `'*'` in production** for `allowed_origins`
- ❌ **Never combine `'*'` with `supports_credentials => true`**
- ❌ **Don't expose sensitive headers** in `exposed_headers`
- ❌ **Don't allow all headers** if you can specify exact ones
- ❌ **Don't disable CSRF protection** just to avoid CORS issues

---

## 📝 Production Optimization (Optional)

To improve performance, you can cache preflight requests:

```php
// In config/cors.php
'max_age' => 3600,  // Cache preflight for 1 hour
```

This reduces the number of OPTIONS requests the browser needs to make.

---

## 🎯 Final Configuration Summary

### Files Modified:
1. ✅ `config/cors.php` - Added production domains

### Files Verified:
1. ✅ `app/Http/Kernel.php` - CORS middleware already enabled

### Commands to Run:
```bash
# On production server
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Expected Result:
✅ Frontend at `https://eliteonegrocery.com` can successfully call API at `https://adminxd.eliteonegrocery.com/api/v1/*`

---

## 📞 Support

If you still encounter issues after following this guide:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs (Apache/Nginx)
3. Verify SSL certificates are valid for both domains
4. Ensure DNS is correctly configured
5. Test with browser in incognito mode (to rule out cache issues)

---

**Last Updated**: December 6, 2025
**Laravel Version**: 11.x
**Status**: Production Ready ✅
