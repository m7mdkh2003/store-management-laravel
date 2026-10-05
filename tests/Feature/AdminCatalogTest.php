<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_category_and_product(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('categories.store'), [
                'name' => 'Accessories',
                'description' => 'Store accessories',
            ])
            ->assertRedirect(route('categories.index'));

        $category = Category::where('name', 'Accessories')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('products.store'), [
                'category_id' => $category->id,
                'name' => 'Wireless Mouse',
                'description' => 'A demo product',
                'price' => 49.99,
                'stock' => 12,
            ])
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Wireless Mouse']);
    }

    public function test_deleting_product_is_soft_delete(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
