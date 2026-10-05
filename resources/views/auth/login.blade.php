@extends('layouts.app')
@section('title','تسجيل الدخول')
@section('content')
<div class="card" style="max-width:560px;margin:30px auto;"><h1>تسجيل الدخول</h1><p class="subtitle">ادخل إلى حسابك لمتابعة الطلبات.</p>
<form method="POST" action="{{ route('login.store') }}">@csrf
<div class="form-group"><label>البريد الإلكتروني</label><input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></div>
<div class="form-group"><label>كلمة المرور</label><input type="password" name="password" autocomplete="current-password" required></div>
<button type="submit" class="btn">دخول</button>
</form><p>لا تملك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p></div>
@endsection
