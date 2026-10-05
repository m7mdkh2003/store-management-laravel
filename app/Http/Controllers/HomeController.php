<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $this->validatedSearch($request);
        $categories = Category::withCount('products')->orderBy('name')->get();

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('welcome', compact('products', 'categories', 'search'));
    }

    public function category(Request $request, Category $category)
    {
        $search = $this->validatedSearch($request);
        $categories = Category::withCount('products')->orderBy('name')->get();

        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('welcome', compact('products', 'categories', 'category', 'search'));
    }

    private function validatedSearch(Request $request): ?string
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        return isset($data['search']) ? trim($data['search']) : null;
    }
}
