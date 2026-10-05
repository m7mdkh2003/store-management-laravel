@extends('layouts.app')
@section('title','أرشيف المنتجات')
@section('content')
<div class="page-card"><div class="page-header"><div><h1>أرشيف المنتجات</h1><p class="subtitle">المنتجات المؤرشفة لا تظهر في المتجر، ويمكن استعادتها بدون فقد سجل الطلبات.</p></div><a href="{{ route('products.index') }}" class="btn btn-secondary">المنتجات النشطة</a></div></div>
<div class="table-card"><table><thead><tr><th>#</th><th>المنتج</th><th>الصنف</th><th>السعر</th><th>المخزون</th><th>تاريخ الأرشفة</th><th>الإجراء</th></tr></thead><tbody>
@forelse($products as $product)<tr><td>{{ $product->id }}</td><td>{{ $product->name }}</td><td>{{ $product->category->name ?? '-' }}</td><td>${{ number_format((float)$product->price,2) }}</td><td>{{ $product->stock }}</td><td>{{ optional($product->deleted_at)->format('Y-m-d H:i') }}</td><td><form action="{{ route('products.restore',$product->id) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-success">استعادة</button></form></td></tr>
@empty<tr><td colspan="7" class="empty-row">لا توجد منتجات مؤرشفة.</td></tr>@endforelse
</tbody></table><div class="pagination">{{ $products->links() }}</div></div>
@endsection
