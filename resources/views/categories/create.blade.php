@extends('layouts.app')

@section('title', 'إضافة صنف')

@section('content')
    <div class="card">
        <h1>إضافة صنف جديد</h1>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="form-group">
                <label>اسم الصنف</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn">حفظ</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
@endsection