@extends('layouts.app')
@section('title','إنشاء حساب')
@section('content')
<div class="card" style="max-width:560px;margin:30px auto;"><h1>إنشاء حساب</h1><p class="subtitle">كلمة المرور يجب أن تكون 8 أحرف على الأقل وتحتوي أحرفاً وأرقاماً.</p>
<form method="POST" action="{{ route('register.store') }}">@csrf
<div class="form-group"><label>الاسم</label><input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required></div>
<div class="form-group"><label>البريد الإلكتروني</label><input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></div>
<div class="form-group"><label>كلمة المرور</label><input type="password" name="password" autocomplete="new-password" required></div>
<div class="form-group"><label>تأكيد كلمة المرور</label><input type="password" name="password_confirmation" autocomplete="new-password" required></div>
<button type="submit" class="btn">تسجيل</button>
</form><p>لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p></div>
@endsection
