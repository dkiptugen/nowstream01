<?php

use App\Providers\AppServiceProvider;
use App\Providers\BroadcastServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\MacrosServiceProvider;
use \Stevebauman\Location\LocationServiceProvider;

return [
    AppServiceProvider::class,
    BroadcastServiceProvider::class,
    EventServiceProvider::class,
    MacrosServiceProvider::class,
    LocationServiceProvider::class,
];
