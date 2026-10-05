@extends('layouts.app')

@section('title', 'تعديل صنف')

@section('content')
    <div class="card">
        <h1>تعديل الصنف</h1>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>اسم الصنف</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit" class="btn">تحديث</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
@endsection
