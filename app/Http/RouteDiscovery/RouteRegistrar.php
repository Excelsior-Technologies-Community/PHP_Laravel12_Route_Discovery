<?php

namespace App\Http\RouteDiscovery;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use App\Attributes\Route as RouteAttribute;

class RouteRegistrar
{
    public static function register()
    {
        $files = File::allFiles(app_path('Http/Controllers'));

        foreach ($files as $file) {

            // SIMPLE & RELIABLE CLASS NAME
            $className = 'App\\Http\\Controllers\\' . pathinfo($file, PATHINFO_FILENAME);

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            foreach ($reflection->getMethods() as $method) {

                $attributes = $method->getAttributes(RouteAttribute::class);

                foreach ($attributes as $attribute) {

                    $routeData = $attribute->newInstance();

                    $httpMethod = strtolower($routeData->method);
                    $uri = $routeData->uri;

                    // Skip if no URI
                    if (!$uri) continue;

                    $route = Route::$httpMethod($uri, [$className, $method->getName()]);

                    if (!empty($routeData->middleware)) {
                        $route->middleware($routeData->middleware);
                    }

                    if ($routeData->name) {
                        $route->name($routeData->name);
                    }
                }
            }
        }
    }
}