<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_shows_register_link_without_demo_credentials(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('Demo admin');
        $response->assertDontSee('admin@juragandaging.test');
        $response->assertSee(route('register'), false);
    }

    public function test_visitor_can_register_as_customer(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Customer Baru',
            'email' => 'customer-baru@example.test',
            'phone' => '081234567890',
            'address' => 'Jalan Testing Morowali nomor 1',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('products'));

        $user = User::query()->where('email', 'customer-baru@example.test')->firstOrFail();

        $this->assertSame('Customer Baru', $user->name);
        $this->assertSame('customer', $user->role);
        $this->assertTrue(Hash::check('Password123', $user->password));
        $this->assertAuthenticatedAs($user);
    }
}
