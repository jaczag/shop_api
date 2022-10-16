<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Collection $products;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->products = Product::factory([
            'is_active' => 1
        ])->count(5)->create();
    }

    /**
     * @return void
     */
    public function test_guest_can_create_cart(): void
    {
        $response = $this->postJson(route('cart.store'), [
            'products_ids' => $this->products->pluck('id')->toArray()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Success',
                'data' => []
            ]);

        $response->assertJsonCount($this->products->count(), 'data.products');

        $this->assertDatabaseHas('carts', [
            'id' => 1,
            'user_id' => null,
        ]);

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $this->products->first()->id,
            'cart_id' => 1
        ]);
    }

    /**
     * @return void
     */
    public function test_logged_user_can_create_cart(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson(route('cart.store'), [
            'products_ids' => $this->products->pluck('id')->toArray()
        ]);

        $response->assertJsonCount($this->products->count(), 'data.products');
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Success',
                'data' => []
            ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_validation_attribute_type_errors(): void
    {
        $response = $this->postJson(route('cart.store'), [
            'products_ids' => ['first_id', 9999]
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'code' => 400,
                'message' => [
                    'products_ids.0' => [],
                    'products_ids.1' => []
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_validation_missing_attribute(): void
    {
        $response = $this->postJson(route('cart.store'), []);

        $response->assertStatus(400)
            ->assertJson([
                    'status' => 'error',
                    'code' => 400,
                    'message' => [
                        'products_ids' => []
                    ],
                ]
            );
    }
}
