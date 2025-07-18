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
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            return "https://res.cloudinary.com/{$cloudName}/image/upload/{$this->gambar_utama}";
        }
        return null;
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file) {
            if (\Illuminate\Support\Str::startsWith($this->file, ['http://', 'https://'])) {
                return $this->file;
            }
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            return "https://res.cloudinary.com/{$cloudName}/image/upload/{$this->file}";
        }
        return null;
    }
}
