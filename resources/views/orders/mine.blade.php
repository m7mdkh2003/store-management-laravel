@extends('layouts.app')
@section('title', 'طلباتي')
@section('content')
<div class="page-card"><div class="page-header"><div><h1>طلباتي</h1><p class="subtitle">تابع حالة طلباتك أو ألغِ الطلب وهو ما زال قيد الانتظار.</p></div><a href="{{ route('home') }}" class="btn btn-secondary">متابعة التسوق</a></div></div>
<div class="table-card">
<table>
<thead><tr><th>#</th><th>المنتج</th><th>الكمية</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th><th>الإجراء</th></tr></thead>
<tbody>
@forelse($orders as $order)
<tr>
<td>{{ $order->id }}</td><td>{{ $order->product->name ?? 'منتج غير متاح' }}</td><td>{{ $order->quantity }}</td><td>${{ number_format((float)$order->total_price,2) }}</td><td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td><td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
<td>@if($order->canBeCancelledBy(auth()->user()))<form action="{{ route('orders.cancel',$order) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="btn btn-danger" onclick="return confirm('هل تريد إلغاء الطلب؟ ستعود الكمية للمخزون.')">إلغاء الطلب</button></form>@else<span class="meta">—</span>@endif</td>
</tr>
@empty <tr><td colspan="7" class="empty-row">لا توجد لديك طلبات بعد.</td></tr> @endforelse
</tbody>
</table>
<div class="pagination">{{ $orders->links() }}</div>
</div>
@endsection
