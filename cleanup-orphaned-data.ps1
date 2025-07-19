# Script untuk membersihkan data yang bermasalah
Write-Host "🧹 Cleanup Orphaned Data Script" -ForegroundColor Yellow
Write-Host "=================================" -ForegroundColor Yellow

Write-Host ""
Write-Host "1. Menjalankan migration untuk soft delete..." -ForegroundColor White
docker-compose exec app php artisan migrate

Write-Host ""
Write-Host "2. Menampilkan produk yang bermasalah..." -ForegroundColor White
docker-compose exec app php artisan tinker --execute="
use App\Models\Produk;
use App\Models\OrderItem;

// Cari produk yang masih digunakan di order_items
\$productsWithOrders = Produk::whereHas('orderItems')->get();
echo 'Produk yang masih digunakan dalam pesanan:' . PHP_EOL;
foreach (\$productsWithOrders as \$product) {
    \$orderCount = \$product->orderItems()->count();
    echo \"- ID: {\$product->id}, Nama: {\$product->nama}, Order Count: {\$orderCount}\" . PHP_EOL;
}

// Cari order_items yang tidak memiliki produk (orphaned)
\$orphanedOrderItems = OrderItem::whereDoesntHave('produk')->get();
echo 'Order items yang tidak memiliki produk (orphaned):' . PHP_EOL;
foreach (\$orphanedOrderItems as \$item) {
    echo \"- ID: {\$item->id}, Product ID: {\$item->product_id}, Order ID: {\$item->order_id}\" . PHP_EOL;
}
"

Write-Host ""
Write-Host "3. Opsi pembersihan:" -ForegroundColor White
Write-Host "   a) Hapus order_items yang orphaned" -ForegroundColor Cyan
Write-Host "   b) Soft delete produk yang bermasalah" -ForegroundColor Cyan
Write-Host "   c) Hapus order yang pending untuk produk tertentu" -ForegroundColor Cyan

Write-Host ""
Write-Host "4. Untuk menjalankan cleanup, gunakan:" -ForegroundColor White
Write-Host "   docker-compose exec app php artisan tinker" -ForegroundColor Green
Write-Host ""
Write-Host "   Kemudian jalankan perintah berikut:" -ForegroundColor White
Write-Host "   // Hapus order_items orphaned" -ForegroundColor Cyan
Write-Host "   App\Models\OrderItem::whereDoesntHave('produk')->delete();" -ForegroundColor Green
Write-Host ""
Write-Host "   // Soft delete produk yang bermasalah" -ForegroundColor Cyan
Write-Host "   App\Models\Produk::whereHas('orderItems')->delete();" -ForegroundColor Green
Write-Host ""
Write-Host "   // Hapus order pending untuk produk tertentu" -ForegroundColor Cyan
Write-Host "   App\Models\Order::where('status', 'pending')->whereHas('items', function(\$q) { \$q->where('product_id', 60); })->delete();" -ForegroundColor Green 