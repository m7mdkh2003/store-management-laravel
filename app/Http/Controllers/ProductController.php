<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(8);

        return view('products.index', compact('products'));
    }

    public function archived()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(8);

        return view('products.archived', compact('products'));
    }

    public function restore(int $product)
    {
        $archivedProduct = Product::onlyTrashed()->findOrFail($product);
        $archivedProduct->restore();

        return redirect()->route('products.archived')
            ->with('success', 'تمت استعادة المنتج إلى المتجر.');
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'تمت إضافة المنتج بنجاح.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'تم تعديل المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'تمت أرشفة المنتج مع الاحتفاظ بسجل الطلبات.');
    }
}
