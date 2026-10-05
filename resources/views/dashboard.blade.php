@extends('layouts.app')
@section('title', 'لوحة الإدارة')
@section('content')
<div class="page-card">
    <div class="page-header">
        <div><h1>لوحة الإدارة</h1><p class="subtitle">مرحباً {{ auth()->user()->name }} — نظرة سريعة على المتجر.</p></div>
        <a href="{{ route('home') }}" class="btn btn-secondary">عرض المتجر</a>
    </div>
</div>
<div class="stats-grid">
    <div class="stat-card"><h3>المنتجات النشطة</h3><p>{{ $productsCount }}</p></div>
    <div class="stat-card"><h3>الأصناف</h3><p>{{ $categoriesCount }}</p></div>
    <div class="stat-card"><h3>كل الطلبات</h3><p>{{ $ordersCount }}</p></div>
    <div class="stat-card"><h3>مبيعات مكتملة</h3><p>${{ number_format((float)$totalSales, 2) }}</p></div>
    <div class="stat-card"><h3>قيد الانتظار</h3><p>{{ $pendingOrders }}</p></div>
    <div class="stat-card"><h3>مكتملة</h3><p>{{ $completedOrders }}</p></div>
    <div class="stat-card"><h3>مخزون منخفض ≤ 5</h3><p>{{ $lowStockProducts }}</p></div>
</div>
<div class="dashboard-grid">
    <div class="dashboard-card"><h2>الأصناف</h2><p>إنشاء وتعديل وإدارة التصنيفات.</p><a href="{{ route('categories.index') }}" class="btn">إدارة الأصناف</a></div>
    <div class="dashboard-card"><h2>المنتجات</h2><p>إدارة الكتالوج والمخزون والأسعار.</p><a href="{{ route('products.index') }}" class="btn">إدارة المنتجات</a></div>
    <div class="dashboard-card"><h2>الطلبات</h2><p>مراجعة الطلبات وتحديث دورة حالتها.</p><a href="{{ route('orders.index') }}" class="btn">إدارة الطلبات</a></div>
</div>
<div class="table-card">
    <h2>آخر الطلبات</h2>
    <table>
        <thead><tr><th>#</th><th>المستخدم</th><th>المنتج</th><th>الكمية</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th></tr></thead>
        <tbody>
        @forelse($latestOrders as $order)
            <tr><td>{{ $order->id }}</td><td>{{ $order->user->name ?? 'مستخدم محذوف' }}</td><td>{{ $order->product->name ?? 'منتج محذوف' }}</td><td>{{ $order->quantity }}</td><td>${{ number_format((float)$order->total_price,2) }}</td><td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td><td>{{ $order->created_at->format('Y-m-d H:i') }}</td></tr>
        @empty <tr><td colspan="7" class="empty-row">لا توجد طلبات حالياً.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection
