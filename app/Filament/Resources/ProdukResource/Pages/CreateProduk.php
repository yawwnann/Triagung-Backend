<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CreateProduk extends CreateRecord
{
    protected static string $resource = ProdukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('DEBUG: Masuk mutateFormDataBeforeCreate', $data);
        Log::info('DEBUG: Tipe data gambar', [
            'type' => isset($data['gambar']) ? gettype($data['gambar']) : null,
            'class' => (isset($data['gambar']) && is_object($data['gambar'])) ? get_class($data['gambar']) : null
        ]);
        if (isset($data['gambar']) && $data['gambar'] instanceof \Illuminate\Http\UploadedFile) {
            $client = new \GuzzleHttp\Client([
                'base_uri' => env('SUPABASE_URL'),
                'headers' => [
                    'apikey' => env('SUPABASE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                ]
            ]);
            $file = $data['gambar'];
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $bucket = 'images';
            $response = $client->post("/storage/v1/object/$bucket/$fileName", [
                'headers' => [
                    'Content-Type' => $file->getMimeType(),
                ],
                'body' => fopen($file->getPathname(), 'r'),
            ]);
            if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
                $data['gambar'] = env('SUPABASE_URL') . "/storage/v1/object/public/$bucket/$fileName";
            } else {
                $data['gambar'] = null;
            }
        }
        return $data;
    }
}
