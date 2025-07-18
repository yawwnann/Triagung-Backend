<?php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dm3icigfr',
        'api_key'    => '932445379271325',
        'api_secret' => '3lHG7ZkrWwm_gpH1USLtkIVBLMk',
    ],
    'url' => [
        'secure' => true
    ]
]);

try {
    $result = $cloudinary->uploadApi()->upload('storage/app/public/01K0DT40TBWA0CV8C8A9VD2W6Z.jpg', [
        'folder' => 'produk'
    ]);
    print_r($result);
} catch (Exception $e) {
    echo 'Cloudinary upload error: ' . $e->getMessage();
}