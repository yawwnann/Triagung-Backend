<?php

namespace App\Models;

use App\Traits\HasSlug; // <-- 1. Import Trait
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    use HasFactory, HasSlug; // <-- 2. Gunakan Trait

    protected $fillable = ['kategori_produk_id', 'nama', 'slug', 'deskripsi', 'harga', 'stok', 'gambar'];

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
}