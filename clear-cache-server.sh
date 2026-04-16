#!/bin/bash

# Script untuk clear semua cache Laravel di server
# Jalankan script ini di server setelah deploy perubahan

echo "🔄 Clearing Laravel Cache..."

# Clear application cache
php artisan cache:clear
echo "✅ Application cache cleared"

# Clear route cache
php artisan route:clear
echo "✅ Route cache cleared"

# Clear config cache
php artisan config:clear
echo "✅ Config cache cleared"

# Clear view cache
php artisan view:clear
echo "✅ View cache cleared"

# Clear compiled classes
php artisan clear-compiled
echo "✅ Compiled classes cleared"

# Optimize untuk production (opsional)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

echo ""
echo "✨ All cache cleared successfully!"
echo ""
echo "📝 Jika masih belum berubah, coba:"
echo "   1. Restart PHP-FPM: sudo systemctl restart php8.x-fpm"
echo "   2. Restart web server: sudo systemctl restart nginx"
echo "   3. Clear browser cache (Ctrl+Shift+R)"
