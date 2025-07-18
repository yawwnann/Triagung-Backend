<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadTest extends Model
{
    protected $fillable = ['file', 'gambar_utama'];

    public function getGambarUtamaUrlAttribute(): ?string
    {
        if ($this->gambar_utama) {
            if (\Illuminate\Support\Str::startsWith($this->gambar_utama, ['http://', 'https://'])) {
                return $this->gambar_utama;
            }
            // Sekarang hanya return null jika bukan url
            return null;
        }
        return null;
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file) {
            if (\Illuminate\Support\Str::startsWith($this->file, ['http://', 'https://'])) {
                return $this->file;
            }
            // Sekarang hanya return null jika bukan url
            return null;
        }
        return null;
    }
}
