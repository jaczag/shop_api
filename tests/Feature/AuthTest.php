<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory([
            'password' => 'password'
        ])->create();
    }

    /**
     * @return void
     */
    public function test_success_user_registration(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'John',
            'email' => 'john123@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'code' => 200
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john123@example.com',
            'name' => 'John',
        ]);
    }


    /**
     * @return void
     */
    public function test_validation_errors_user_registration(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
                "status",
                "message" => [
                    "name",
                    "email",
                    "password",
                    "password_confirmation"
                ],
                "code",
            ]
        );
    }

    /**
     * @return void
     */
    public function test_user_registration_email_taken(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
                "status",
                "message" => [
                    "email",
                ],
                "code",
            ]
        );
    }

    /**
     * Test user logged in successful
     * @return void
     */
    public function test_user_login_success(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                    "status",
                    "data" => [
                        "user" => [
                            "id",
                            "name",
                            "email",
                            "role",
                            "created_at",
                            "updated_at",
                        ],
                        "token"
                    ],
                    "message",
                    "code",
                ]
            );

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_user_login_with_invalid_credentials(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => fake()->email,
            'password' => 'just_testing'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
                "status",
                "message",
                "code",
            ]
        );
    }

    /**
     * @return void
     */
    public function test_login_validation_errors(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
                "status",
                "message" => [
                    "password",
                    "email"
                ],
                "code",
            ]
        );
    }

    /**
     * @return void
     */
    public function test_logout_success(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                    "status",
                    "data",
                    "message",
                    "code",
                ]
            );
    }

    /**
     * @return void
     */
    public function test_guest_user_cannot_logout(): void
    {
        $this->postJson(route('auth.logout'))
            ->assertStatus(401)
            ->assertJsonStructure([
                    "status",
                    "message",
                    "code",
                ]
            );
    }
}
