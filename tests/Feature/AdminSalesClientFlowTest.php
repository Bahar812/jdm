<?php

namespace Tests\Feature;

use App\Models\SalesClient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesClientFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_sales_client_and_activity_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.sales-clients.store'), $this->clientPayload([
                'business_name' => 'Kopi Selatan',
                'business_type' => SalesClient::TYPE_CAFE,
                'status' => SalesClient::STATUS_FOLLOW_UP,
                'activity_notes' => 'Sudah dihubungi via WhatsApp, minta proposal harga.',
            ]));

        $response->assertRedirect(route('admin.sales-clients.index'));

        $client = SalesClient::query()->where('business_name', 'Kopi Selatan')->first();

        $this->assertNotNull($client);
        $this->assertSame(SalesClient::TYPE_CAFE, $client->business_type);
        $this->assertSame(SalesClient::STATUS_FOLLOW_UP, $client->status);

        $activity = $client->activities()->first();

        $this->assertNotNull($activity);
        $this->assertSame('2026-05-10', $activity->activity_date->toDateString());

        $this->assertDatabaseHas('sales_activities', [
            'sales_client_id' => $client->id,
            'user_id' => $admin->id,
            'status' => SalesClient::STATUS_FOLLOW_UP,
            'description' => 'Sudah dihubungi via WhatsApp, minta proposal harga.',
        ]);

        $index = $this->actingAs($admin)->get(route('admin.sales-clients.index'));

        $index->assertOk();
        $index->assertSee('Kopi Selatan');
        $index->assertSee('Follow Up');
    }

    public function test_admin_can_update_client_status_and_monitor_it_on_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = SalesClient::query()->create($this->clientAttributes([
            'business_name' => 'Resto Nusantara',
            'status' => SalesClient::STATUS_PROSPECT,
            'created_by' => $admin->id,
        ]));

        $response = $this->actingAs($admin)
            ->put(route('admin.sales-clients.update', $client), $this->clientPayload([
                'business_name' => 'Resto Nusantara',
                'business_type' => SalesClient::TYPE_RESTAURANT,
                'status' => SalesClient::STATUS_DEAL,
                'activity_notes' => 'Client setuju kerja sama pasokan mingguan.',
            ]));

        $response->assertRedirect(route('admin.sales-clients.index'));

        $this->assertDatabaseHas('sales_clients', [
            'id' => $client->id,
            'status' => SalesClient::STATUS_DEAL,
        ]);

        $this->assertDatabaseHas('sales_activities', [
            'sales_client_id' => $client->id,
            'status' => SalesClient::STATUS_DEAL,
            'description' => 'Client setuju kerja sama pasokan mingguan.',
        ]);

        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboard->assertOk();
        $dashboard->assertSee('Monitoring Aktivitas Sales');
        $dashboard->assertSee('Resto Nusantara');
        $dashboard->assertSee('Client setuju kerja sama pasokan mingguan.');
    }

    public function test_admin_sales_client_status_must_be_known(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->from(route('admin.sales-clients.create'))
            ->post(route('admin.sales-clients.store'), $this->clientPayload([
                'status' => 'tidak-valid',
            ]));

        $response->assertRedirect(route('admin.sales-clients.create'));
        $response->assertSessionHasErrors('status');
        $this->assertDatabaseCount('sales_clients', 0);
    }

    private function clientPayload(array $overrides = []): array
    {
        return array_merge([
            'business_name' => 'Kopi Selatan',
            'business_type' => SalesClient::TYPE_CAFE,
            'contact_person' => 'Dina',
            'phone' => '081234567890',
            'email' => 'dina@example.test',
            'address' => 'Morowali',
            'cooperation_offer' => 'Penawaran pasokan daging sapi dan ayam untuk kebutuhan menu harian.',
            'status' => SalesClient::STATUS_PROSPECT,
            'last_contacted_at' => '2026-05-10',
            'next_follow_up_at' => '2026-05-17',
            'notes' => 'Client perlu daftar harga grosir.',
            'activity_notes' => null,
        ], $overrides);
    }

    private function clientAttributes(array $overrides = []): array
    {
        $attributes = $this->clientPayload($overrides);
        unset($attributes['activity_notes']);

        return $attributes;
    }
}
