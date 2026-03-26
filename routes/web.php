<?php

use Illuminate\Support\Facades\Route;
use App\Http\RouteDiscovery\RouteRegistrar;

Route::get('/', function () {
    return view('welcome');
});

// Auto route discovery
RouteRegistrar::register();

