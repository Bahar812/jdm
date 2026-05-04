<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogAndMemberFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_with_generated_slug(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => ' Daging Sapi Premium ',
            'slug' => '',
            'category' => 'Daging Sapi',
            'badge' => 'Best Seller',
            'unit' => 'kg',
            'price' => 125000,
            'stock' => 25,
            'image_url' => 'https://example.test/sapi.jpg',
            'description' => 'Produk daging sapi premium.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Daging Sapi Premium',
            'slug' => 'daging-sapi-premium',
            'category' => 'Daging Sapi',
            'price' => 125000,
            'stock' => 25,
            'is_active' => true,
        ]);
    }

    public function test_admin_product_slug_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Product::query()->create($this->productAttributes(['slug' => 'daging-sapi']));

        $response = $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $this->productAttributes([
                'name' => 'Daging Sapi Lain',
                'slug' => 'daging-sapi',
            ]));

        $response->assertRedirect(route('admin.products.create'));
        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_member_password_requires_letters_and_numbers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->from(route('admin.members.create'))
            ->post(route('admin.members.store'), [
                'name' => 'Member Baru',
                'email' => 'member-baru@example.test',
                'phone' => '081234567890',
                'address' => 'Morowali, Sulawesi Tengah',
                'role' => 'customer',
                'password' => 'password',
            ]);

        $response->assertRedirect(route('admin.members.create'));
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'member-baru@example.test']);
    }

    private function productAttributes(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Daging Sapi',
            'slug' => 'daging-sapi',
            'category' => 'Daging',
            'badge' => null,
            'unit' => 'kg',
            'price' => 100000,
            'stock' => 10,
            'image_url' => null,
            'description' => 'Produk testing.',
            'is_active' => true,
        ], $overrides);
    }
}
