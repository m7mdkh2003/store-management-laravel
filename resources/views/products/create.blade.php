@extends('layouts.app')

@section('title', 'إضافة منتج')

@section('content')
    <div class="card">
        <h1>إضافة منتج جديد</h1>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($categories->count() == 0)
            <p>لا توجد أصناف. يجب إضافة صنف أولاً.</p>
            <a href="{{ route('categories.create') }}" class="btn">إضافة صنف</a>
        @else
            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                <div class="form-group">
                    <label>الصنف</label>
                    <select name="category_id" required>
                        <option value="">اختر الصنف</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>اسم المنتج</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label>المخزون</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                </div>

                <button type="submit" class="btn">حفظ</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        @endif
    </div>
@endsection