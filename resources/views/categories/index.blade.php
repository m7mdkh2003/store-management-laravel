@extends('layouts.app')
@section('title','إدارة الأصناف')
@section('content')
<div class="page-card"><div class="page-header"><div><h1>إدارة الأصناف</h1><p class="subtitle">الأصناف المرتبطة بمنتجات لا يمكن حذفها قبل معالجة المنتجات.</p></div><div class="actions"><a href="{{ route('categories.create') }}" class="btn">إضافة صنف</a><a href="{{ route('dashboard') }}" class="btn btn-secondary">لوحة الإدارة</a></div></div></div>
<div class="table-card"><table><thead><tr><th>#</th><th>الاسم</th><th>الوصف</th><th>المنتجات</th><th>الإجراءات</th></tr></thead><tbody>
@forelse($categories as $category)<tr><td>{{ $category->id }}</td><td>{{ $category->name }}</td><td>{{ $category->description ?? '-' }}</td><td>{{ $category->products_count }}</td><td><div class="actions"><a href="{{ route('categories.edit',$category) }}" class="btn">تعديل</a><form method="POST" action="{{ route('categories.destroy',$category) }}">@csrf @method('DELETE')<button class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button></form></div></td></tr>
@empty<tr><td colspan="5" class="empty-row">لا توجد أصناف حتى الآن.</td></tr>@endforelse
</tbody></table><div class="pagination">{{ $categories->links() }}</div></div>
@endsection
