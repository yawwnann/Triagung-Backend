<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;
use App\Helpers\GumletUploader;

class EditProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('MULAI_MUTATE_EDIT', $data);
        if (isset($data['gambar']) && $data['gambar'] instanceof \Illuminate\Http\UploadedFile) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);
            $result = $cloudinary->uploadApi()->upload($data['gambar']->getRealPath(), [
                'folder' => 'produk'
            ]);
            $data['gambar'] = $result['secure_url'] ?? null;
            @unlink($data['gambar']->getRealPath());
        } elseif (isset($data['gambar']) && is_string($data['gambar']) && file_exists(storage_path('app/public/' . $data['gambar']))) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);
            $result = $cloudinary->uploadApi()->upload(storage_path('app/public/' . $data['gambar']), [
                'folder' => 'produk'
            ]);
            $data['gambar'] = $result['secure_url'] ?? null;
            @unlink(storage_path('app/public/' . $data['gambar']));
        } else {
            Log::info('CLOUDINARY_UPLOAD_SKIP', ['gambar' => $data['gambar'] ?? null]);
        }
        return $data;
    }
}
