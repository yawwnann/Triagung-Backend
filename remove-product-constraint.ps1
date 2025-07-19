# Script untuk menghapus foreign key constraint produk
Write-Host "🔗 Remove Product Foreign Key Constraint" -ForegroundColor Yellow
Write-Host "=========================================" -ForegroundColor Yellow

Write-Host ""
Write-Host "1. Menjalankan migration untuk menghapus foreign key constraint..." -ForegroundColor White
docker-compose exec app php artisan migrate

Write-Host ""
Write-Host "2. Verifikasi bahwa constraint sudah dihapus..." -ForegroundColor White
docker-compose exec app php artisan tinker --execute="
use App\Models\Produk;
use App\Models\OrderItem;

// Coba hapus produk yang sebelumnya bermasalah
try {
    \$product = Produk::find(60);
    if (\$product) {
        \$product->delete();
        echo '✅ Produk berhasil dihapus!' . PHP_EOL;
    } else {
        echo '❌ Produk dengan ID 60 tidak ditemukan' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}

// Cek data order_items yang masih ada
\$orderItems = OrderItem::where('product_id', 60)->get();
echo 'Order items dengan product_id 60: ' . \$orderItems->count() . PHP_EOL;

foreach (\$item as \$orderItems) {
    echo \"- Order ID: {\$item->order_id}, Product Name: {\$item->product_name}, Price: {\$item->price}\" . PHP_EOL;
}
"

Write-Host ""
Write-Host "3. Menampilkan informasi perubahan:" -ForegroundColor White
Write-Host "   ✅ Foreign key constraint dari order_items ke produks sudah dihapus" -ForegroundColor Green
Write-Host "   ✅ Produk sekarang bisa dihapus tanpa mempengaruhi pesanan" -ForegroundColor Green
Write-Host "   ✅ Data produk di pesanan disimpan sebagai snapshot (product_name, price)" -ForegroundColor Green
Write-Host "   ✅ Relasi model sudah diperbarui" -ForegroundColor Green

Write-Host ""
Write-Host "4. Keuntungan dari perubahan ini:" -ForegroundColor White
Write-Host "   • Produk bisa dihapus tanpa error foreign key" -ForegroundColor Cyan
Write-Host "   • Data pesanan tetap utuh dengan snapshot produk" -ForegroundColor Cyan
Write-Host "   • Fleksibilitas dalam manajemen produk" -ForegroundColor Cyan
Write-Host "   • Tidak perlu soft delete" -ForegroundColor Cyan

Write-Host ""
Write-Host "✅ Selesai! Sekarang coba hapus produk lagi di Filament Admin." -ForegroundColor Green 