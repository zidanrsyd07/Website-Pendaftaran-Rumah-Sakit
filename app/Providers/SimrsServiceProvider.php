<?php

namespace App\Providers;

use App\Services\MockSimrsService;
use App\Services\SimrsServiceInterface;
use Illuminate\Support\ServiceProvider;

class SimrsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SimrsServiceInterface::class, function ($app) {
            $mode = config('simrs.mode', 'mock');

            if ($mode === 'live') {
                // Ketika SIMRS API tersedia, ganti dengan:
                // return new LiveSimrsService(config('simrs.api_url'), config('simrs.api_key'));
                throw new \RuntimeException('SIMRS live mode belum tersedia. Gunakan mode mock.');
            }

            return new MockSimrsService;
        });
    }

    public function boot(): void
    {
        //
    }
}
