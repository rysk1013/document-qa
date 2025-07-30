<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // CSRFクッキー取得（SPA認証に必要）
        $this->withHeader('Accept', 'application/json');
        $this->get('/sanctum/csrf-cookie');
    }

    #[Test]
    public function it_registers_a_new_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Taro Yamada',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
                ->assertJson(['message' => 'User registered successfully.']);

        $this->assertDatabaseHas('users', ['email' => 'taro@example.com']);
    }

    #[Test]
    public function it_returns_validation_error_for_duplicate_email()
    {
        User::factory()->create(['email' => 'taro@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Taro',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_logs_in_and_fetches_user_info_successfully()
    {
        $user = User::factory()->create([
            'email' => 'taro@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'taro@example.com',
            'password' => 'password123',
        ])->assertOk();

        $this->actingAs($user);

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.email', 'taro@example.com');
    }

    #[Test]
    public function it_logs_out_successfully()
    {
        $user = User::factory()->create();

        // トークン発行
        $token = $user->createToken('AccessToken')->plainTextToken;

        // 認証付きでログアウトAPIを叩く
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logged out successfully.']);
    }

    #[Test]
    public function it_returns_unauthorized_when_accessing_user_api_without_login()
    {
        $this->getJson('/api/user')
            ->assertUnauthorized();
    }
}
