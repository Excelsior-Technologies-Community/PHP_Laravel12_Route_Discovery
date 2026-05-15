<?php

namespace App\Http\Controllers;

use App\Attributes\Route;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    #[Route(method: 'get', uri: 'products', name: 'products.index')]
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->oldest()
            ->paginate(3);

        return view('products.index', compact('products'));
    }

    #[Route(method: 'post', uri: 'products/store', name: 'products.store')]
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        Product::create($request->all());

        return back()->with('success', 'Product Added Successfully');
    }

    #[Route(method: 'delete', uri: 'products/delete/{id}', name: 'products.delete')]
    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        return back()->with('success', 'Product Deleted Successfully');
    }
}