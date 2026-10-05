<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(8);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'تمت إضافة الصنف بنجاح.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'تم تعديل الصنف بنجاح.');
    }

    public function destroy(Category $category)
    {
        $hasProducts = Product::withTrashed()
            ->where('category_id', $category->id)
            ->exists();

        if ($hasProducts) {
            return back()->with('error', 'لا يمكن حذف الصنف لأنه مرتبط بمنتجات.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'تم حذف الصنف بنجاح.');
    }
}
