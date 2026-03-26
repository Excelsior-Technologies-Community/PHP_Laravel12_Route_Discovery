# PHP_Laravel12_Route_Discovery

## Introduction

PHP_Laravel12_Route_Discovery is a modern Laravel 12 project that demonstrates how to implement automatic route registration using PHP 8 Attributes and Reflection.

Instead of manually defining routes in Laravel's route files, this project dynamically discovers and registers routes by scanning controller methods. Each method is annotated with a custom Route attribute, making routing more intuitive, maintainable, and scalable.

The project is inspired by advanced routing concepts used in packages like Spatie Laravel Route Discovery, but implemented from scratch to provide a deeper understanding of how attribute-based routing works internally.

---

## Project Overview

This project focuses on building a custom route discovery mechanism in Laravel 12.

The system works by:

1. Scanning all controller files inside the `app/Http/Controllers` directory
2. Using PHP Reflection to inspect each controller method
3. Detecting custom `#[Route]` attributes applied to methods
4. Dynamically registering routes based on attribute configuration

This approach removes the need for manual route definitions and promotes a cleaner, more modular code structure.

### Key Highlights

- Eliminates repetitive route definitions
- Uses modern PHP 8+ features (Attributes)
- Demonstrates reflection-based programming
- Follows clean architecture principles
- Easily extendable for real-world applications

This project is designed primarily as a learning and demonstration tool to understand advanced Laravel routing techniques.

---

## Requirements

- PHP >= 8.2
- Laravel 12
- Composer

---

## Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Route_Discovery "12.*"
cd PHP_Laravel12_Route_Discovery
```
---

## Step 2: Create Route Attribute

File: `app/Attributes/Route.php`

```php
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class Route
{
    public function __construct(
        public string $method = 'get',
        public string $uri = '',
        public array $middleware = [],
        public ?string $name = null
    ) {}
}
```

---

## Step 3: Create Route Registrar

File: `app/Http/RouteDiscovery/RouteRegistrar.php`

```php
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
```

---

## Step 4: Register in web.php

File: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\RouteDiscovery\RouteRegistrar;

Route::get('/', function () {
    return view('welcome');
});

// Auto route discovery
RouteRegistrar::register();
```

---

## Step 5: Create Controller with Attributes

```bash
php artisan make:controller ProductController
```
File: `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Attributes\Route;

class ProductController extends Controller
{
    #[Route(method: 'get', uri: 'products', name: 'products.index')] 
    public function index()
    {
        return "Product Index";
    }
    #[Route(method: 'post', uri: 'products/store', middleware: ['web'])] 
    
    public function store()
    {
        return "Product Stored";
    }
    #[Route(method: 'get', uri: 'products/show')] 
    
    public function show()
    {
        return "Product Show";
    }
}
```

---

## Step 6: Run Server

Run:

```bash
php artisan serve
```

Open in browser:

```bash
http://127.0.0.1:8000/products
```

---

## Output

<img src="screenshots/Screenshot 2026-03-26 114751.png" width="1000">

<img src="screenshots/Screenshot 2026-03-26 115610.png" width="1000">

---

## How It Works

- Controllers are scanned automatically
- PHP Reflection reads method attributes
- Custom #[Route] attributes define route behavior
- Routes are dynamically registered using Laravel Router

This eliminates the need to manually define routes in web.php.

---

## Project Structure

```
PHP_Laravel12_Route_Discovery/
│
├── app/
│   ├── Attributes/
│   │   └── Route.php                   # Custom Attribute
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php   # Test controller (returns text)
│   │   │
│   │   └── RouteDiscovery/
│   │       └── RouteRegistrar.php      # MAIN LOGIC
│
├── routes/
│   └── web.php                         # Calls RouteRegistrar
│
├── bootstrap/
├── config/
├── public/
├── storage/
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── README.md
```

---

Your PHP_Laravel12_Route_Discovery Project is now ready!
