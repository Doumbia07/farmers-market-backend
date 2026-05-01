<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price_fcfa' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price_fcfa' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);
        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
