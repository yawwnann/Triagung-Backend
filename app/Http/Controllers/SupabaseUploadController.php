<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SupabaseUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('gambar');
        $filePath = $file->getPathname();
        $fileName = $file->getClientOriginalName();

        $bucket = 'images'; // ganti sesuai nama bucket Anda di Supabase
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_KEY');

        $client = new Client([
            'base_uri' => $supabaseUrl,
            'headers' => [
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ]
        ]);

        $response = $client->post("/storage/v1/object/$bucket/$fileName", [
            'headers' => [
                'Content-Type' => $file->getMimeType(),
            ],
            'body' => fopen($filePath, 'r'),
        ]);

        if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
            $publicUrl = "$supabaseUrl/storage/v1/object/public/$bucket/$fileName";
            return response()->json(['url' => $publicUrl]);
        } else {
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }
}