<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class SupabaseUploader
{
    public static function upload($file, $folder = null)
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_KEY');
        $bucket = env('SUPABASE_BUCKET', 'images');
        $client = new Client([
            'base_uri' => $supabaseUrl,
            'headers' => [
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ]
        ]);
        $fileName = uniqid() . '_' . ($file instanceof \Illuminate\Http\UploadedFile ? $file->getClientOriginalName() : basename($file));
        if ($folder) {
            $fileName = $folder . '/' . $fileName;
        }
        $response = $client->post("/storage/v1/object/$bucket/$fileName", [
            'headers' => [
                'Content-Type' => $file instanceof \Illuminate\Http\UploadedFile ? $file->getMimeType() : mime_content_type($file),
            ],
            'body' => fopen($file instanceof \Illuminate\Http\UploadedFile ? $file->getPathname() : $file, 'r'),
        ]);
        if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
            return "$supabaseUrl/storage/v1/object/public/$bucket/$fileName";
        } else {
            return null;
        }
    }
}