<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SIMRS API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan SIMRS RS Santa Anna.
    | Saat ini menggunakan mock data. Ketika API SIMRS sudah tersedia,
    | ubah SIMRS_API_URL dan SIMRS_API_KEY melalui file .env.
    |
    */

    'api_url' => env('SIMRS_API_URL', ''),
    'api_key' => env('SIMRS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Mode Operasi
    |--------------------------------------------------------------------------
    |
    | 'mock' = menggunakan data dummy lokal
    | 'live' = menggunakan koneksi API SIMRS
    |
    */

    'mode' => env('SIMRS_MODE', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Timeout & Retry
    |--------------------------------------------------------------------------
    */

    'timeout' => env('SIMRS_TIMEOUT', 30),
    'retry_attempts' => env('SIMRS_RETRY', 3),

];
