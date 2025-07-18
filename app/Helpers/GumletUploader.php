<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GumletUploader
{
    public static function upload($file, $folder = 'produk')
    {
        $apiKey = env('GUMLET_API_KEY');
        $endpoint = 'https://api.gumlet.com/v1/storage/upload';
        $client = new Client();
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        try {
            $response = $client->request('POST', $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $fileName,
                    ],
                    [
                        'name' => 'path',
                        'contents' => $folder . '/' . $fileName,
                    ],
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            if (isset($body['data']['url'])) {
                Log::info('Gumlet upload success', ['url' => $body['data']['url']]);
                return $body['data']['url'];
            } else {
                Log::error('Gumlet upload failed: No url in response', ['response' => $body]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Gumlet upload error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    // Untuk upload dari URL (misal dari seeder)
    public static function uploadFromUrl($imageUrl, $folder = 'produk')
    {
        $apiKey = env('GUMLET_API_KEY');
        $endpoint = 'https://api.gumlet.com/v1/storage/upload';
        $client = new Client();
        $fileName = uniqid() . '_' . basename(parse_url($imageUrl, PHP_URL_PATH));
        try {
            $response = $client->request('POST', $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($imageUrl, 'r'),
                        'filename' => $fileName,
                    ],
                    [
                        'name' => 'path',
                        'contents' => $folder . '/' . $fileName,
                    ],
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            if (isset($body['data']['url'])) {
                Log::info('Gumlet uploadFromUrl success', ['url' => $body['data']['url']]);
                return $body['data']['url'];
            } else {
                Log::error('Gumlet uploadFromUrl failed: No url in response', ['response' => $body]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Gumlet uploadFromUrl error', ['message' => $e->getMessage()]);
            return null;
        }
    }
}