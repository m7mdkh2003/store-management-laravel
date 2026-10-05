@extends('layouts.app')

@section('title', 'تعديل منتج')

@section('content')
    <div class="card">
        <h1>تعديل المنتج</h1>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>الصنف</label>
                <select name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>السعر</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label>المخزون</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
            </div>

            <button type="submit" class="btn">تحديث</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
@endsection