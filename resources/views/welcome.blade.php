@extends('layouts.app')
@section('title', 'StoreFlow')
@section('content')
<div class="store-hero">
    <h1>StoreFlow</h1>
    <p>متجر تجريبي متكامل لإدارة المنتجات والمخزون والطلبات، مع لوحة إدارة منفصلة وصلاحيات واضحة.</p>
    @auth
        <a href="{{ route('orders.mine') }}" class="store-btn">عرض طلباتي</a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('dashboard') }}" class="store-btn store-btn-dark">لوحة الإدارة</a>
        @endif
    @else
        <a href="{{ route('login') }}" class="store-btn">تسجيل الدخول</a>
        <a href="{{ route('register') }}" class="store-btn store-btn-dark">إنشاء حساب</a>
    @endauth
</div>
<div class="store-section">
    <h2>البحث عن منتج</h2>
    <form action="{{ isset($category) ? route('category.products', $category) : route('home') }}" method="GET" class="search-form">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ابحث باسم المنتج أو الوصف..." class="search-input">
        <button type="submit" class="btn btn-primary">بحث</button>
        @if(!empty($search))<a href="{{ isset($category) ? route('category.products', $category) : route('home') }}" class="btn btn-secondary">إلغاء</a>@endif
    </form>
</div>
<div class="store-section">
    <h2>الأصناف</h2>
    <div class="category-wrapper">
        <a href="{{ route('home') }}" class="category-pill {{ !isset($category) ? 'active' : '' }}">كل الأصناف</a>
        @foreach($categories as $cat)
            <a href="{{ route('category.products', $cat) }}" class="category-pill {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">{{ $cat->name }} <span>({{ $cat->products_count }})</span></a>
        @endforeach
    </div>
</div>
<div class="store-section">
    <h2>@isset($category) منتجات صنف: {{ $category->name }} @else المنتجات @endisset</h2>
    @if(!empty($search))<p class="subtitle">نتائج البحث عن: "{{ $search }}"</p>@endif
    <div class="products-grid">
        @forelse($products as $product)
            <article class="product-card">
                <h3>{{ $product->name }}</h3>
                <div class="product-category">{{ $product->category->name ?? 'بدون صنف' }}</div>
                <div class="product-description">{{ $product->description ?? 'لا يوجد وصف لهذا المنتج.' }}</div>
                <div class="product-price">${{ number_format((float)$product->price, 2) }}</div>
                <div class="product-stock">المخزون: @if($product->stock > 0)<span class="stock-available">{{ $product->stock }}</span>@else<span class="stock-empty">غير متوفر</span>@endif</div>
                <div class="product-actions">
                    @auth
                        @if($product->stock > 0)
                            <form action="{{ route('orders.store', $product) }}" method="POST" class="order-form">
                                @csrf
                                <label for="quantity-{{ $product->id }}">الكمية</label>
                                <input type="number" id="quantity-{{ $product->id }}" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="quantity-input" required>
                                <button type="submit" class="store-btn store-btn-success">إرسال الطلب</button>
                            </form>
                        @else
                            <button type="button" class="store-btn store-btn-dark" disabled>غير متوفر</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="store-btn">سجل الدخول للطلب</a>
                    @endauth
                </div>
            </article>
        @empty
            <div class="empty-products">لا توجد منتجات مطابقة.</div>
        @endforelse
    </div>
    <div class="pagination-wrapper">{{ $products->links() }}</div>
</div>
@endsection
