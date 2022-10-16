<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Collection $categories;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory([
            'role' => UserRoleEnum::Admin->value
        ])->create();

        $this->categories = Category::factory()->count(20)->create();
    }

    /*
     * @return void
     */
    public function test_fetch_list_of_categories(): void
    {
        $response = $this->getJson(route('categories.index'));

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
    public function test_show_category(): void
    {
        $category = $this->categories->first();
        $response = $this->getJson(route('categories.show', ['category' => $category->id]));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_show_category_which_does_not_exist(): void
    {
        $response = $this->getJson(route('categories.show', ['category' => 1000000000000000000000000]));

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => "Does not exist any instance of category with the given id",
                'code' => 404
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_update_category(): void
    {
        $category = $this->categories->first();
        $response = $this->actingAs($this->admin)
            ->putJson(
                route('admin.categories.update', [
                        'category' => $category->id,
                        'name' => 'exampleName'
                    ]
                )
            );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $category->id,
                    'name' => 'exampleName',
                    'created_at' => timestampToString($category->created_at),
                    'updated_at' => timestampToString($category->updated_at),
                ],
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_update_category_validation_errors(): void
    {
        $category = $this->categories->first();
        $response = $this->actingAs($this->admin)
            ->putJson(
                route('admin.categories.update', [
                        'category' => $category->id,
                    ]
                )
            );

        $response->assertStatus(400)
            ->assertJson([
                    'status' => 'error',
                    'code' => 400,
                    'message' => [
                        'name' => []
                    ],
                ]
            );
    }

    /**
     * @return void
     */
    public function test_user_role_user_cannot_update_category(): void
    {
        $category = $this->categories->first();

        $user = User::factory([
            'role' => UserRoleEnum::User->value
        ])->create();

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.categories.update', [
                        'category' => $category->id,
                        'name' => 'exampleName'
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
    public function test_guest_cannot_update_category(): void
    {
        $category = $this->categories->first();

        $response = $this->putJson(
            route('admin.categories.update', [
                    'category' => $category->id,
                    'name' => 'exampleName'
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
    public function test_admin_update_category_which_does_not_exist(): void
    {
        $response = $this->actingAs($this->admin)
            ->putJson(
                route('admin.categories.update', [
                        'category' => 0,
                        'name' => 'example'
                    ]
                )
            );

        $response->assertStatus(404)
            ->assertJson([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Does not exist any instance of category with the given id'
                ]
            );
    }

    /**
     * @return void
     */
    public function test_admin_delete_category(): void
    {
        $category = $this->categories->first();
        $response = $this->actingAs($this->admin)
            ->deleteJson(
                route('admin.categories.destroy', [
                        'category' => $category->id,
                    ]
                )
            );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => null,
                'message' => "Success",
                'code' => 200
            ]);
    }

    /**
     * @return void
     */
    public function test_user_role_user_cannot_delete_category(): void
    {
        $category = $this->categories->first();
        $user = User::factory([
            'role' => UserRoleEnum::User->value
        ])->create();

        $response = $this->actingAs($user)
            ->deleteJson(
                route('admin.categories.destroy', [
                        'category' => $category->id,
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
    public function test_guest_cannot_delete_category(): void
    {
        $category = $this->categories->first();
        $response = $this->deleteJson(
            route('admin.categories.destroy', [
                    'category' => $category->id,
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
