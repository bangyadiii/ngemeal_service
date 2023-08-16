<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->where("data.user.name", "Test User")
                ->where("data.user.email", "test@example.com")
                ->whereType("data.access_token", "string")
                ->missing("data.user.password")
                ->etc();
        });
    }
}
