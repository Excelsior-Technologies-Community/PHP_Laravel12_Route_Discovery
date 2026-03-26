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
