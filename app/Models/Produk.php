<?php

namespace App\Models;

use App\Traits\HasSlug; // <-- 1. Import Trait
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, HasSlug, SoftDeletes; // <-- 2. Gunakan Trait

    protected $fillable = ['kategori_produk_id', 'nama', 'slug', 'deskripsi', 'harga', 'berat', 'stok', 'gambar'];

    // Panggil metode boot dari trait
    protected static function boot()
    {
        parent::boot();
        static::bootHasSlug(); // <-- 3. Panggil boot method dari trait
    }

    public function kategoriProduk(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class);
    }

    // Relasi ke order_items dihapus karena foreign key constraint sudah dihapus
    // public function orderItems(): HasMany
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id');
    // }
}