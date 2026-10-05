<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StoreFlow')</title>
    <style>
        :root { --primary:#2563eb; --dark:#111827; --muted:#6b7280; --line:#e5e7eb; --bg:#f3f4f6; --success:#16a34a; --danger:#dc2626; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Arial,"Noto Sans Arabic",sans-serif; background:var(--bg); color:var(--dark); }
        a { color:inherit; }
        .navbar { background:var(--dark); color:#fff; padding:14px 4%; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; position:sticky; top:0; z-index:20; box-shadow:0 4px 16px rgba(0,0,0,.12); }
        .navbar-title a { color:#fff; text-decoration:none; font-weight:800; font-size:24px; letter-spacing:.3px; }
        .navbar-links { display:flex; align-items:center; gap:14px; flex-wrap:wrap; }
        .navbar-links > a { color:#e5e7eb; text-decoration:none; font-weight:700; }
        .navbar-links > a:hover { color:#fff; }
        .user-chip { padding:7px 11px; border:1px solid #374151; border-radius:999px; color:#d1d5db; font-size:13px; }
        .container { width:min(1180px,92%); margin:auto; padding:30px 0 60px; }
        .card,.page-card,.store-section,.table-card,.store-hero { background:#fff; padding:28px; border-radius:16px; margin-bottom:24px; box-shadow:0 8px 24px rgba(15,23,42,.06); }
        .store-hero { padding:38px; }
        .store-hero h1,.page-header h1,.card h1 { margin-top:0; }
        .store-hero p,.subtitle { color:var(--muted); line-height:1.8; }
        .page-header { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; }
        .btn,.store-btn { display:inline-flex; align-items:center; justify-content:center; padding:10px 17px; border-radius:9px; border:0; background:var(--primary); color:#fff; text-decoration:none; font-weight:700; cursor:pointer; font-size:14px; transition:.15s ease; }
        .btn:hover,.store-btn:hover { opacity:.92; transform:translateY(-1px); }
        .btn-secondary,.store-btn-dark { background:#4b5563; }
        .btn-success,.store-btn-success { background:var(--success); }
        .btn-danger { background:var(--danger); }
        .btn-warning { background:#d97706; }
        button:disabled { opacity:.55; cursor:not-allowed; transform:none!important; }
        .alert-success,.success-message,.alert-error,.error-box { padding:14px 16px; border-radius:10px; margin:0 0 20px; font-weight:700; }
        .alert-success,.success-message { background:#dcfce7; color:#166534; }
        .alert-error,.error-box { background:#fee2e2; color:#991b1b; }
        .error-box ul { margin:0; padding-right:18px; }
        .form-group { margin-bottom:17px; }
        label { display:block; margin-bottom:7px; font-weight:700; }
        input,textarea,select { width:100%; padding:11px 12px; border:1px solid #d1d5db; border-radius:9px; font:inherit; background:#fff; }
        input:focus,textarea:focus,select:focus { outline:2px solid #bfdbfe; border-color:#60a5fa; }
        textarea { min-height:110px; resize:vertical; }
        form { margin:0; }
        .search-form { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
        .search-form .search-input { flex:1; min-width:220px; }
        .category-wrapper { display:flex; flex-wrap:wrap; gap:10px; }
        .category-pill { padding:9px 15px; border:1px solid var(--line); border-radius:999px; text-decoration:none; font-weight:700; background:#f9fafb; }
        .category-pill.active { background:var(--primary); border-color:var(--primary); color:#fff; }
        .products-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px; }
        .product-card { border:1px solid var(--line); border-radius:14px; padding:20px; background:#fff; }
        .product-card h3 { margin:0 0 12px; font-size:22px; }
        .product-category { display:inline-block; padding:5px 10px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-size:13px; font-weight:700; margin-bottom:12px; }
        .product-description { color:#4b5563; min-height:48px; line-height:1.65; }
        .product-price { font-size:23px; font-weight:800; color:var(--primary); margin:14px 0 7px; }
        .product-stock { font-weight:700; margin-bottom:14px; }
        .stock-available { color:var(--success); } .stock-empty { color:var(--danger); }
        .quantity-input { width:95px; margin-bottom:10px; }
        .dashboard-grid,.stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:18px; margin-bottom:24px; }
        .dashboard-card,.stat-card { background:#fff; padding:24px; border-radius:15px; box-shadow:0 8px 24px rgba(15,23,42,.06); }
        .dashboard-card { text-align:center; }
        .stat-card h3 { margin:0 0 10px; color:#4b5563; font-size:15px; }
        .stat-card p { margin:0; font-size:30px; font-weight:800; color:var(--primary); }
        .table-card,.card { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:720px; }
        th,td { padding:14px 12px; border-bottom:1px solid var(--line); text-align:right; vertical-align:middle; }
        th { background:#f9fafb; white-space:nowrap; }
        .actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .actions select { width:auto; min-width:145px; margin:0; }
        .status-badge { display:inline-block; padding:6px 11px; border-radius:999px; font-size:13px; font-weight:800; white-space:nowrap; }
        .status-pending { background:#fef3c7; color:#92400e; }
        .status-approved { background:#dcfce7; color:#166534; }
        .status-rejected { background:#fee2e2; color:#991b1b; }
        .status-completed { background:#dbeafe; color:#1e40af; }
        .status-cancelled { background:#e5e7eb; color:#374151; }
        .empty-products,.empty-row { text-align:center; padding:28px; color:var(--muted); }
        .pagination,.pagination-wrapper { margin-top:22px; }
        .meta { font-size:13px; color:var(--muted); }
        .footer { text-align:center; color:#6b7280; padding:18px 0 30px; font-size:13px; }
        @media (max-width:700px) { .navbar { padding:12px 4%; } .container { width:94%; padding-top:20px; } .card,.page-card,.store-section,.table-card,.store-hero { padding:20px; } .store-hero h1 { font-size:27px; } }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-title"><a href="{{ route('home') }}">StoreFlow</a></div>
    <div class="navbar-links">
        <a href="{{ route('home') }}">المتجر</a>
        @auth
            <a href="{{ route('orders.mine') }}">طلباتي</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}">لوحة الإدارة</a>
            @endif
            <span class="user-chip">{{ auth()->user()->name }} · {{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}</span>
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="btn btn-danger">خروج</button></form>
        @else
            <a href="{{ route('login') }}">تسجيل الدخول</a>
            <a href="{{ route('register') }}">إنشاء حساب</a>
        @endauth
    </div>
</nav>
<main class="container">
    @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert-error">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="error-box"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<footer class="footer">StoreFlow · Laravel Store & Inventory Management</footer>
</body>
</html>
