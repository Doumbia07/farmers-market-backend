<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::with('children')->whereNull('parent_id')->get());
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $category = Category::create($data);
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category->load('children'));
    }


    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $category->update($data);
        return response()->json($category);
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
