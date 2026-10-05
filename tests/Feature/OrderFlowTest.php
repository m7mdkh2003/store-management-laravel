<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_place_order_and_stock_is_decremented(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 25, 'stock' => 5]);

        $this->actingAs($user)
            ->post(route('orders.store', $product), ['quantity' => 2])
            ->assertRedirect(route('orders.mine'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => 50,
            'status' => Order::STATUS_PENDING,
        ]);

        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_customer_cannot_order_more_than_available_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);

        $this->actingAs($user)
            ->from(route('home'))
            ->post(route('orders.store', $product), ['quantity' => 2])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock);
    }

    public function test_customer_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)->post(route('orders.store', $product), ['quantity' => 2]);
        $order = Order::firstOrFail();

        $this->actingAs($user)
            ->patch(route('orders.cancel', $order))
            ->assertRedirect(route('orders.mine'));

        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_admin_rejection_restores_stock(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)->post(route('orders.store', $product), ['quantity' => 2]);
        $order = Order::firstOrFail();

        $this->actingAs($admin)
            ->patch(route('orders.status', $order), ['status' => Order::STATUS_REJECTED])
            ->assertRedirect(route('orders.index'));

        $this->assertSame(Order::STATUS_REJECTED, $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }
    public function test_customer_cannot_cancel_another_customers_order(): void
    {
        $owner = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($owner)->post(route('orders.store', $product), ['quantity' => 1]);
        $order = Order::firstOrFail();

        $this->actingAs($otherCustomer)
            ->patch(route('orders.cancel', $order))
            ->assertForbidden();

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(4, $product->fresh()->stock);
    }

    public function test_admin_must_follow_order_status_workflow(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($customer)->post(route('orders.store', $product), ['quantity' => 1]);
        $order = Order::firstOrFail();

        $this->actingAs($admin)
            ->from(route('orders.index'))
            ->patch(route('orders.status', $order), ['status' => Order::STATUS_COMPLETED])
            ->assertRedirect(route('orders.index'))
            ->assertSessionHasErrors('status');

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('orders.status', $order), ['status' => Order::STATUS_APPROVED])
            ->assertRedirect(route('orders.index'));

        $this->actingAs($admin)
            ->patch(route('orders.status', $order->fresh()), ['status' => Order::STATUS_COMPLETED])
            ->assertRedirect(route('orders.index'));

        $this->assertSame(Order::STATUS_COMPLETED, $order->fresh()->status);
        $this->assertSame(4, $product->fresh()->stock);
    }

}
