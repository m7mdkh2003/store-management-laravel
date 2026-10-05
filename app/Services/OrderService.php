<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function place(User $user, Product $product, int $quantity): Order
    {
        return DB::transaction(function () use ($user, $product, $quantity) {
            $lockedProduct = Product::query()
                ->whereKey($product->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedProduct->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'الكمية المطلوبة أكبر من المخزون المتوفر.',
                ]);
            }

            $order = $user->orders()->create([
                'product_id' => $lockedProduct->id,
                'quantity' => $quantity,
                'total_price' => round((float) $lockedProduct->price * $quantity, 2),
                'status' => Order::STATUS_PENDING,
            ]);

            $lockedProduct->decrement('stock', $quantity);

            return $order;
        }, 3);
    }

    public function cancelForCustomer(User $user, Order $order): void
    {
        if ($order->user_id !== $user->id) {
            throw new AuthorizationException('لا يمكنك إلغاء طلب لا يخصك.');
        }

        DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status !== Order::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'order' => 'يمكن إلغاء الطلب فقط عندما يكون قيد الانتظار.',
                ]);
            }

            $this->restoreStock($lockedOrder);
            $lockedOrder->update(['status' => Order::STATUS_CANCELLED]);
        }, 3);
    }

    public function changeStatus(Order $order, string $newStatus): void
    {
        DB::transaction(function () use ($order, $newStatus) {
            $lockedOrder = Order::query()
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === $newStatus) {
                return;
            }

            if (! in_array($newStatus, $lockedOrder->allowedAdminTransitions(), true)) {
                throw ValidationException::withMessages([
                    'status' => 'الانتقال المطلوب بين حالات الطلب غير مسموح.',
                ]);
            }

            if ($newStatus === Order::STATUS_REJECTED) {
                $this->restoreStock($lockedOrder);
            }

            $lockedOrder->update(['status' => $newStatus]);
        }, 3);
    }

    private function restoreStock(Order $order): void
    {
        if (! $order->product_id) {
            return;
        }

        $product = Product::withTrashed()
            ->whereKey($order->product_id)
            ->lockForUpdate()
            ->first();

        $product?->increment('stock', $order->quantity);
    }
}
