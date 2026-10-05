<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orders)
    {
    }

    public function index()
    {
        $orders = Order::with(['user', 'product'])
            ->latest()
            ->paginate(12);

        return view('orders.index', compact('orders'));
    }

    public function mine(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('orders.mine', compact('orders'));
    }

    public function store(PlaceOrderRequest $request, Product $product)
    {
        $this->orders->place(
            $request->user(),
            $product,
            (int) $request->validated('quantity')
        );

        return redirect()->route('orders.mine')
            ->with('success', 'تم إرسال الطلب بنجاح.');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->orders->cancelForCustomer($request->user(), $order);

        return redirect()->route('orders.mine')
            ->with('success', 'تم إلغاء الطلب وإرجاع الكمية إلى المخزون.');
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->orders->changeStatus($order, $request->validated('status'));

        return redirect()->route('orders.index')
            ->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
