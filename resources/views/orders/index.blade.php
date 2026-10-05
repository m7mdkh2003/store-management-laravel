@extends('layouts.app')
@section('title', 'إدارة الطلبات')
@section('content')
<div class="page-card"><div class="page-header"><div><h1>إدارة الطلبات</h1><p class="subtitle">تحديث الطلب ضمن دورة حالات مقيدة للحفاظ على المخزون وسلامة السجل.</p></div><a href="{{ route('dashboard') }}" class="btn btn-secondary">رجوع</a></div></div>
<div class="table-card">
<table>
<thead><tr><th>#</th><th>المستخدم</th><th>المنتج</th><th>الكمية</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th><th>الإجراء</th></tr></thead>
<tbody>
@forelse($orders as $order)
<tr>
<td>{{ $order->id }}</td><td>{{ $order->user->name ?? 'مستخدم محذوف' }}</td><td>{{ $order->product->name ?? 'منتج محذوف' }}</td><td>{{ $order->quantity }}</td><td>${{ number_format((float)$order->total_price,2) }}</td>
<td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td><td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
<td>
@if(count($order->allowedAdminTransitions()))
<form action="{{ route('orders.status',$order) }}" method="POST" class="actions">@csrf @method('PATCH')
<select name="status" required><option value="">اختر الحالة</option>@foreach($order->allowedAdminTransitions() as $status)<option value="{{ $status }}">{{ match($status) { 'approved'=>'مقبول','rejected'=>'مرفوض','completed'=>'مكتمل',default=>$status } }}</option>@endforeach</select>
<button class="btn btn-success" type="submit">تحديث</button></form>
@else <span class="meta">حالة نهائية</span> @endif
</td>
</tr>
@empty <tr><td colspan="8" class="empty-row">لا توجد طلبات حالياً.</td></tr> @endforelse
</tbody>
</table>
<div class="pagination">{{ $orders->links() }}</div>
</div>
@endsection
