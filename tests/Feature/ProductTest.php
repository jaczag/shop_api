<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Category $category;
    private Collection $products;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory([
            'role' => UserRoleEnum::Admin->value
        ])->create();

        $this->category = Category::factory()->create();
        $this->products = Product::factory(['category_id' => $this->category->id])
            ->count(20)
            ->create();
    }

    /*
     * @return void
     */
    public function test_fetch_list_of_products(): void
    {
        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'data' => [],
                    'pagination' => [
                        'total' => 20,
                        'count' => 10,
                        'per_page' => 10,
                        'current_page' => 1,
                        'total_pages' => 2
                    ]
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_show_product(): void
    {
        $product = $this->products->first();
        $response = $this->getJson(route('products.show', ['product' => $product->id]));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $product->id,
                    'external_id' => $product->external_id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'category' => [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'created_at' => $this->category->created_at,
                        'updated_at' => $this->category->updated_at,
                    ],
                    'is_active' => $product->is_active,
                    'description' => $product->description,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_show_product_which_does_not_exist(): void
    {
        $response = $this->getJson(route('products.show', ['product' => 1000000000000000000000000]));

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => "Does not exist any instance of product with the given id",
                'code' => 404
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_update_product(): void
    {
        $product = $this->products->first();
        $response = $this->actingAs($this->admin)
            ->putJson(
                route('admin.products.update', [
                        'product' => $product->id,
                        'title' => 'updated_title'
                    ]
                )
            );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $product->id,
                    'external_id' => $product->external_id,
                    'title' => 'updated_title',
                    'category' => [
                        'id' => $this->category->id,
                    ],
                    'is_active' => $product->is_active,
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }


    /**
     * @return void
     */
    public function test_user_role_user_cannot_update_product(): void
    {
        $product = $this->products->first();
        $user = User::factory([
            'role' => UserRoleEnum::User->value
        ])->create();

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.products.update', [
                        'product' => $product->id,
                        'title' => 'updated_title'
                    ]
                )
            );

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => "This action is unauthorized",
                'code' => 401
            ]);
    }

    /**
     * @return void
     */
    public function test_guest_cannot_update_product(): void
    {
        $product = $this->products->first();
        $response = $this->putJson(
                route('admin.products.update', [
                        'product' => $product->id,
                        'title' => 'updated_title'
                    ]
                )
            );

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => "Unauthenticated.",
                'code' => 401
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_store_product(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson(
                route('admin.products.store', [
                        'price' => '1',
                        'title' => 'new_title',
                        'category_id' => $this->category->id,
                    ]
                )
            );

        $product = Product::all()->last();

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $product->id,
                    'external_id' => $product->external_id,
                    'title' => 'new_title',
                    'price' => 1,
                    'category' => [
                        'id' => $this->category->id,
                    ],
                    'is_active' => 0,
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_store_product_validation_errors(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson(
                route('admin.products.store', []
                )
            );

        $product = Product::all()->last();

        $response->assertStatus(400)
            ->assertJson([
                    'status' => 'error',
                    'code' => 400,
                    'message' => [
                        'title' => [],
                        'price' => [],
                        'category_id' => [],
                    ],
                ]
            );
    }

    /**
     * @return void
     */
    public function test_user_role_user_cannot_store_product(): void
    {
        $user = User::factory([
            'role' => UserRoleEnum::User->value
        ])->create();

        $response = $this->actingAs($user)
            ->postJson(
                route('admin.products.store', [
                        'price' => '1',
                        'title' => 'new_title',
                        'category_id' => $this->category->id,
                    ]
                )
            );

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => "This action is unauthorized",
                'code' => 401
            ]);
    }

    /**
     * @return void
     */
    public function test_guest_cannot_store_product(): void
    {
        $response = $this->postJson(
                route('admin.products.store', [
                        'price' => '1',
                        'title' => 'new_title',
                        'category_id' => $this->category->id,
                    ]
                )
            );

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => "Unauthenticated.",
                'code' => 401
            ]);
    }
}
