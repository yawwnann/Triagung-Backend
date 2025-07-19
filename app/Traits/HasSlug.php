<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        // Gunakan event 'saving' bukan 'creating' agar slug juga diperbarui
        // saat nama diubah pada record yang sudah ada.
        static::saving(function ($model) {
            // Generate slug baru jika field 'nama' berubah atau slug kosong
            if ($model->isDirty('nama') || empty($model->slug)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }
}