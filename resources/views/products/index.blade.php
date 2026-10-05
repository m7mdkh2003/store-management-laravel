@extends('layouts.app')
@section('title','إدارة المنتجات')
@section('content')
<div class="page-card"><div class="page-header"><div><h1>إدارة المنتجات</h1><p class="subtitle">حذف المنتج يعني أرشفته Soft Delete للحفاظ على سجل الطلبات.</p></div><div class="actions"><a href="{{ route('products.create') }}" class="btn">إضافة منتج</a><a href="{{ route('products.archived') }}" class="btn btn-warning">الأرشيف</a><a href="{{ route('dashboard') }}" class="btn btn-secondary">لوحة الإدارة</a></div></div></div>
<div class="table-card"><table><thead><tr><th>#</th><th>المنتج</th><th>الصنف</th><th>السعر</th><th>المخزون</th><th>الإجراءات</th></tr></thead><tbody>
@forelse($products as $product)<tr><td>{{ $product->id }}</td><td>{{ $product->name }}</td><td>{{ $product->category->name ?? '-' }}</td><td>${{ number_format((float)$product->price,2) }}</td><td>{{ $product->stock }}</td><td><div class="actions"><a href="{{ route('products.edit',$product) }}" class="btn">تعديل</a><form method="POST" action="{{ route('products.destroy',$product) }}">@csrf @method('DELETE')<button class="btn btn-danger" onclick="return confirm('سيتم أرشفة المنتج مع الاحتفاظ بسجل الطلبات. متابعة؟')">أرشفة</button></form></div></td></tr>
@empty<tr><td colspan="6" class="empty-row">لا توجد منتجات حتى الآن.</td></tr>@endforelse
</tbody></table><div class="pagination">{{ $products->links() }}</div></div>
@endsection
