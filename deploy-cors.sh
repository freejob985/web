#!/bin/bash

# ============================================
# Laravel CORS Deployment Script
# ============================================
# Run this script on your production server after deploying CORS changes
# Location: /home/u929533639/domains/eliteonegrocery.com/public_html/ad

echo "🚀 Starting Laravel CORS Configuration Deployment..."
echo ""

# Step 1: Clear configuration cache
echo "📦 Step 1/4: Clearing configuration cache..."
php artisan config:clear
if [ $? -eq 0 ]; then
    echo "✅ Configuration cache cleared"
else
    echo "❌ Failed to clear configuration cache"
    exit 1
fi
echo ""

# Step 2: Clear application cache
echo "📦 Step 2/4: Clearing application cache..."
php artisan cache:clear
if [ $? -eq 0 ]; then
    echo "✅ Application cache cleared"
else
    echo "❌ Failed to clear application cache"
    exit 1
fi
echo ""

# Step 3: Clear route cache
echo "📦 Step 3/4: Clearing route cache..."
php artisan route:clear
if [ $? -eq 0 ]; then
    echo "✅ Route cache cleared"
else
    echo "❌ Failed to clear route cache"
    exit 1
fi
echo ""

# Step 4: Optimize for production
echo "⚡ Step 4/4: Optimizing for production..."
php artisan config:cache
if [ $? -eq 0 ]; then
    echo "✅ Configuration cached"
else
    echo "❌ Failed to cache configuration"
    exit 1
fi

php artisan route:cache
if [ $? -eq 0 ]; then
    echo "✅ Routes cached"
else
    echo "⚠️  Route caching failed (this is optional)"
fi
echo ""

# Step 5: Verify CORS config
echo "🔍 Verifying CORS configuration..."
php artisan tinker --execute="dump(config('cors'));"
echo ""

echo "✅ ============================================"
echo "✅ CORS Configuration Deployment Complete!"
echo "✅ ============================================"
echo ""
echo "📋 Next Steps:"
echo "   1. Test from browser: https://eliteonegrocery.com"
echo "   2. Check browser console for CORS errors"
echo "   3. Verify Network tab shows correct headers"
echo ""
echo "🔧 If issues persist:"
echo "   - Clear browser cache (Ctrl+F5)"
echo "   - Check Laravel logs: storage/logs/laravel.log"
echo "   - Verify domains in config/cors.php"
echo ""
