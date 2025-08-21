<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Create a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'category' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    // Get all products
    public function index()
    {
        $products = Product::with('farmer')->get();
        return response()->json($products);
    }
}
