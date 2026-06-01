<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\CustomerSegmentationService;
use Carbon\CarbonImmutable;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCustomerSegmentationReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_segmentation_service_calculates_rfm_and_cluster_metrics(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-05-18 12:00:00'));
        $this->seedSegmentationOrders();

        $report = app(CustomerSegmentationService::class)
            ->analyze(CarbonImmutable::parse('2026-05-18 23:59:59'));

        $loyalCustomer = $report['customers']->firstWhere('customer_name', 'Loyal Buyer');

        $this->assertSame(4, $report['totalCustomers']);
        $this->assertGreaterThanOrEqual(2, $report['clusterCount']);
        $this->assertNotEmpty($report['testedClusterScores']);
        $this->assertNotEmpty($report['clusterDistribution']);
        $this->assertSame('Semua periode', $report['period']['label']);
        $this->assertSame('Loyal Buyer', $report['topCustomer']['customer_name']);
        $this->assertNotNull($loyalCustomer);
        $this->assertSame(3, $loyalCustomer['frequency']);
        $this->assertSame(900000, $loyalCustomer['monetary']);
        $this->assertSame(1, $loyalCustomer['recency']);
    }

    public function test_customer_segmentation_service_filters_orders_by_transaction_period(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-05-18 12:00:00'));
        $this->seedSegmentationOrders();

        $report = app(CustomerSegmentationService::class)->analyze(
            CarbonImmutable::parse('2026-05-18 23:59:59'),
            CarbonImmutable::parse('2026-05-01 00:00:00'),
            CarbonImmutable::parse('2026-05-18 23:59:59'),
        );

        $this->assertSame(2, $report['totalCustomers']);
        $this->assertSame('01 May 2026 - 18 May 2026', $report['period']['label']);
        $this->assertSame('Loyal Buyer', $report['topCustomer']['customer_name']);
        $this->assertTrue($report['customers']->contains('customer_name', 'Pembeli Baru'));
        $this->assertFalse($report['customers']->contains('customer_name', 'Pembeli Dormant'));
        $this->assertFalse($report['customers']->contains('customer_name', 'High Value Lama'));
    }

    public function test_admin_dashboard_shows_rfm_kmeans_customer_segmentation(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-05-18 12:00:00'));
        $admin = User::factory()->create(['role' => 'admin']);
        $this->seedSegmentationOrders();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Segmentasi Pelanggan RFM');
        $response->assertSee('K-Means');
        $response->assertSee('Silhouette');
        $response->assertSee('Distribusi Customer per Cluster');
        $response->assertSee('Transaksi Tertinggi');
        $response->assertSee('Loyal Buyer');
        $response->assertSee('Pembeli Dormant');
        $response->assertSee('Cluster');
    }

    public function test_admin_dashboard_filters_segmentation_report_by_transaction_period(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-05-18 12:00:00'));
        $admin = User::factory()->create(['role' => 'admin']);
        $this->seedSegmentationOrders();

        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'segment_start' => '2026-05-01',
            'segment_end' => '2026-05-18',
        ]));

        $response->assertOk();
        $response->assertSee('01 May 2026 - 18 May 2026');
        $response->assertSee('value="2026-05-01"', false);
        $response->assertSee('value="2026-05-18"', false);
        $response->assertSee('Loyal Buyer');

        $segmentationSection = Str::between(
            $response->getContent(),
            'Segmentasi Pelanggan RFM',
            'Monitoring Aktivitas Sales',
        );

        $this->assertStringContainsString('Pembeli Baru', $segmentationSection);
        $this->assertStringNotContainsString('Pembeli Dormant', $segmentationSection);
    }

    public function test_database_seeder_creates_rfm_dummy_orders_with_marketing_strategies(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-05-18 12:00:00'));

        $this->seed(DatabaseSeeder::class);

        $report = app(CustomerSegmentationService::class)
            ->analyze(CarbonImmutable::parse('2026-05-18 23:59:59'));
        $recommendations = strtolower($report['segments']->pluck('recommendation')->implode(' '));

        $this->assertGreaterThanOrEqual(7, $report['totalCustomers']);
        $this->assertTrue($report['customers']->contains('customer_name', 'Resto Sari Laut Premium'));
        $this->assertTrue($report['customers']->contains('customer_name', 'Bu Rina Catering'));
        $this->assertStringContainsString('cashback loyalti', $recommendations);
        $this->assertStringContainsString('bundling hemat', $recommendations);
    }

    private function seedSegmentationOrders(): void
    {
        $loyalCustomer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Loyal Buyer',
            'email' => 'loyal@example.test',
            'phone' => '081100000001',
        ]);

        foreach ([
            ['total_amount' => 400000, 'paid_at' => '2026-05-17 09:00:00'],
            ['total_amount' => 250000, 'paid_at' => '2026-05-12 09:00:00'],
            ['total_amount' => 250000, 'paid_at' => '2026-05-05 09:00:00'],
        ] as $order) {
            $this->createPaidOrder(array_merge($order, [
                'user_id' => $loyalCustomer->id,
                'customer_name' => 'Loyal Buyer',
                'customer_email' => 'loyal@example.test',
                'customer_phone' => '081100000001',
            ]));
        }

        $this->createPaidOrder([
            'customer_name' => 'Pembeli Baru',
            'customer_email' => 'baru@example.test',
            'customer_phone' => '081100000002',
            'total_amount' => 120000,
            'paid_at' => '2026-05-18 10:00:00',
        ]);

        $this->createPaidOrder([
            'customer_name' => 'Pembeli Dormant',
            'customer_email' => 'dormant@example.test',
            'customer_phone' => '081100000003',
            'total_amount' => 90000,
            'paid_at' => '2026-03-01 10:00:00',
        ]);

        foreach ([
            ['total_amount' => 350000, 'paid_at' => '2026-04-01 10:00:00'],
            ['total_amount' => 300000, 'paid_at' => '2026-03-20 10:00:00'],
        ] as $order) {
            $this->createPaidOrder(array_merge($order, [
                'customer_name' => 'High Value Lama',
                'customer_email' => 'high-value@example.test',
                'customer_phone' => '081100000004',
            ]));
        }
    }

    private function createPaidOrder(array $attributes = []): Order
    {
        return Order::query()->create(array_merge([
            'order_number' => 'ORD-RFM-'.Str::upper(Str::random(8)),
            'customer_name' => 'Customer RFM',
            'customer_email' => 'customer-rfm@example.test',
            'customer_phone' => '081100000000',
            'shipping_address' => 'Alamat segmentasi pelanggan',
            'total_amount' => 100000,
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'payment_method' => 'midtrans',
            'paid_at' => '2026-05-18 10:00:00',
        ], $attributes));
    }
}
