<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Hub;

class HubServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Hub::class, Hub::class);
    }
}
