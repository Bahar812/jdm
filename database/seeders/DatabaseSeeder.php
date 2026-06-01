<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\HomeContent;
use App\Models\Product;
use App\Models\SalesClient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@juragandaging.test'],
            [
                'name' => 'Admin Juragan Daging',
                'phone' => '081234567890',
                'address' => 'Morowali, Sulawesi Tengah',
                'password' => 'admin12345',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'member@juragandaging.test'],
            [
                'name' => 'Member Demo',
                'phone' => '081298765432',
                'address' => 'Morowali',
                'password' => 'member12345',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        $products = collect(config('products'))->map(function (array $item): array {
            return [
                'name' => $item['name'],
                'slug' => $item['slug'] ?? Str::slug($item['name']),
                'category' => $item['category'] ?? 'Lainnya',
                'badge' => $item['badge'] ?? null,
                'unit' => ltrim($item['unit'] ?? 'pcs', '/'),
                'price' => (int) ($item['price'] ?? 0),
                'stock' => 100,
                'image_url' => $item['image'] ?? null,
                'description' => $item['description'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        Product::query()->upsert(
            $products,
            ['slug'],
            ['name', 'category', 'badge', 'unit', 'price', 'stock', 'image_url', 'description', 'is_active', 'updated_at']
        );

        HomeContent::syncDefaults();

        $this->call(ArticleSeeder::class);
        $this->seedSalesClients($admin);
        $this->seedRfmOrders();
    }

    private function seedSalesClients(User $admin): void
    {
        $clients = [
            [
                'business_name' => 'Kopi Selatan',
                'business_type' => SalesClient::TYPE_CAFE,
                'contact_person' => 'Dina',
                'phone' => '081234567890',
                'email' => 'dina@kopiselatan.test',
                'address' => 'Jl. Trans Sulawesi, Morowali',
                'cooperation_offer' => 'Penawaran pasokan daging sapi slice dan ayam fillet untuk menu harian cafe.',
                'status' => SalesClient::STATUS_FOLLOW_UP,
                'last_contacted_at' => now()->subDays(1)->toDateString(),
                'next_follow_up_at' => now()->addDays(2)->toDateString(),
                'notes' => 'Menunggu konfirmasi kebutuhan mingguan dan daftar harga grosir.',
                'activity_description' => 'Sudah dihubungi via WhatsApp, client minta proposal harga.',
            ],
            [
                'business_name' => 'Resto Nusantara',
                'business_type' => SalesClient::TYPE_RESTAURANT,
                'contact_person' => 'Pak Arif',
                'phone' => '082145678901',
                'email' => 'arif@restonusantara.test',
                'address' => 'Jl. Ahmad Yani, Bungku Tengah',
                'cooperation_offer' => 'Kerja sama pasokan daging sapi, ayam, dan seafood untuk kebutuhan dapur resto.',
                'status' => SalesClient::STATUS_DEAL,
                'last_contacted_at' => now()->subDays(2)->toDateString(),
                'next_follow_up_at' => now()->addWeek()->toDateString(),
                'notes' => 'Setuju mulai order percobaan untuk minggu depan.',
                'activity_description' => 'Client setuju kerja sama pasokan mingguan.',
            ],
            [
                'business_name' => 'Warung Steak Morowali',
                'business_type' => SalesClient::TYPE_RESTAURANT,
                'contact_person' => 'Raka',
                'phone' => '085244556677',
                'email' => null,
                'address' => 'Kompleks Kuliner Bahodopi',
                'cooperation_offer' => 'Penawaran daging steak cut, tenderloin, dan saus pendamping.',
                'status' => SalesClient::STATUS_NEGOTIATION,
                'last_contacted_at' => now()->subDays(3)->toDateString(),
                'next_follow_up_at' => now()->addDays(3)->toDateString(),
                'notes' => 'Masih membandingkan harga dengan supplier lama.',
                'activity_description' => 'Negosiasi harga untuk pembelian rutin daging steak cut.',
            ],
            [
                'business_name' => 'Cafe Senja',
                'business_type' => SalesClient::TYPE_CAFE,
                'contact_person' => 'Maya',
                'phone' => '081399887766',
                'email' => 'maya@cafesenja.test',
                'address' => 'Jl. Pulau Dua, Morowali',
                'cooperation_offer' => 'Penawaran paket daging burger patty dan frozen food untuk menu snack.',
                'status' => SalesClient::STATUS_PROSPECT,
                'last_contacted_at' => now()->subDays(4)->toDateString(),
                'next_follow_up_at' => now()->addDays(5)->toDateString(),
                'notes' => 'PIC tertarik dengan katalog produk frozen.',
                'activity_description' => 'Prospek baru dari kunjungan sales area Morowali.',
            ],
            [
                'business_name' => 'RM Padang Minang',
                'business_type' => SalesClient::TYPE_RESTAURANT,
                'contact_person' => 'Bu Sari',
                'phone' => '081246801357',
                'email' => null,
                'address' => 'Jl. Poros Bungku',
                'cooperation_offer' => 'Penawaran daging rendang dan ayam potong untuk kebutuhan rumah makan.',
                'status' => SalesClient::STATUS_FOLLOW_UP,
                'last_contacted_at' => now()->subDays(5)->toDateString(),
                'next_follow_up_at' => now()->toDateString(),
                'notes' => 'Perlu follow up hari ini untuk jadwal sampling.',
                'activity_description' => 'Client meminta sample produk sebelum menentukan volume order.',
            ],
        ];

        foreach ($clients as $item) {
            $activityDescription = $item['activity_description'];
            unset($item['activity_description']);

            $client = SalesClient::query()->updateOrCreate(
                ['business_name' => $item['business_name']],
                array_merge($item, ['created_by' => $admin->id])
            );

            $client->activities()->updateOrCreate(
                ['description' => $activityDescription],
                [
                    'user_id' => $admin->id,
                    'activity_date' => $client->last_contacted_at,
                    'status' => $client->status,
                    'next_follow_up_at' => $client->next_follow_up_at,
                ]
            );
        }
    }

    private function seedRfmOrders(): void
    {
        $products = Product::query()
            ->whereIn('slug', [
                'has-dalam-sapi',
                'short-plate-sapi',
                'dada-ayam-fillet',
                'paha-bawah-ayam',
                'fillet-dori',
                'udang-kupas',
                'chicken-nugget',
                'bakso-sapi',
                'saos-bbq',
                'kaldu-bubuk',
                'dimsum-ayam',
            ])
            ->get()
            ->keyBy('slug');

        if ($products->isEmpty()) {
            return;
        }

        $customers = [
            [
                'code' => 'HVC',
                'segment' => 'High Value Customer',
                'name' => 'Resto Sari Laut Premium',
                'email' => 'sarilaut.premium@example.test',
                'phone' => '081100000101',
                'address' => 'Jl. Pelabuhan No. 12, Morowali',
                'orders' => [
                    ['days_ago' => 2, 'items' => ['has-dalam-sapi' => 5, 'short-plate-sapi' => 4, 'udang-kupas' => 3]],
                    ['days_ago' => 8, 'items' => ['has-dalam-sapi' => 4, 'dada-ayam-fillet' => 8, 'fillet-dori' => 4]],
                    ['days_ago' => 15, 'items' => ['short-plate-sapi' => 6, 'udang-kupas' => 4, 'bakso-sapi' => 8]],
                    ['days_ago' => 23, 'items' => ['has-dalam-sapi' => 5, 'paha-bawah-ayam' => 10, 'saos-bbq' => 6]],
                    ['days_ago' => 31, 'items' => ['short-plate-sapi' => 7, 'dada-ayam-fillet' => 10, 'dimsum-ayam' => 8]],
                ],
            ],
            [
                'code' => 'RUTIN',
                'segment' => 'Pembeli Rutin Hemat',
                'name' => 'Warung Ayam Pak Jaya',
                'email' => 'pakjaya.ayam@example.test',
                'phone' => '081100000102',
                'address' => 'Jl. Ahmad Yani, Bungku',
                'orders' => [
                    ['days_ago' => 3, 'items' => ['paha-bawah-ayam' => 3, 'saos-bbq' => 1]],
                    ['days_ago' => 9, 'items' => ['paha-bawah-ayam' => 3, 'kaldu-bubuk' => 2]],
                    ['days_ago' => 16, 'items' => ['dada-ayam-fillet' => 2, 'saos-bbq' => 1]],
                    ['days_ago' => 24, 'items' => ['paha-bawah-ayam' => 4]],
                    ['days_ago' => 32, 'items' => ['chicken-nugget' => 5, 'bakso-sapi' => 4]],
                    ['days_ago' => 41, 'items' => ['paha-bawah-ayam' => 3, 'kaldu-bubuk' => 2]],
                ],
            ],
            [
                'code' => 'RISK',
                'segment' => 'High Value Risiko Churn',
                'name' => 'Hotel Bahari Indah',
                'email' => 'purchasing@hotelbahari.test',
                'phone' => '081100000103',
                'address' => 'Kawasan Hotel Bahari, Morowali',
                'orders' => [
                    ['days_ago' => 74, 'items' => ['has-dalam-sapi' => 8, 'udang-kupas' => 6, 'fillet-dori' => 8]],
                    ['days_ago' => 96, 'items' => ['short-plate-sapi' => 10, 'dada-ayam-fillet' => 12, 'bakso-sapi' => 10]],
                    ['days_ago' => 118, 'items' => ['has-dalam-sapi' => 7, 'short-plate-sapi' => 6, 'dimsum-ayam' => 12]],
                ],
            ],
            [
                'code' => 'POT',
                'segment' => 'Pelanggan Potensial',
                'name' => 'Cafe Grill Senja',
                'email' => 'grillsenja@example.test',
                'phone' => '081100000104',
                'address' => 'Jl. Pulau Dua, Morowali',
                'orders' => [
                    ['days_ago' => 5, 'items' => ['short-plate-sapi' => 4, 'has-dalam-sapi' => 2, 'saos-bbq' => 4]],
                ],
            ],
            [
                'code' => 'NEW',
                'segment' => 'Pelanggan Baru Aktif',
                'name' => 'Rumah Frozen Anita',
                'email' => 'anita.frozen@example.test',
                'phone' => '081100000105',
                'address' => 'Jl. Trans Sulawesi, Morowali',
                'orders' => [
                    ['days_ago' => 1, 'items' => ['chicken-nugget' => 6, 'bakso-sapi' => 6, 'dimsum-ayam' => 5]],
                ],
            ],
            [
                'code' => 'JARANG',
                'segment' => 'Pelanggan Jarang Beli',
                'name' => 'Bu Rina Catering',
                'email' => 'rina.catering@example.test',
                'phone' => '081100000106',
                'address' => 'Jl. Poros Bungku, Morowali',
                'orders' => [
                    ['days_ago' => 68, 'items' => ['dada-ayam-fillet' => 2, 'paha-bawah-ayam' => 2]],
                ],
            ],
            [
                'code' => 'RETENSI',
                'segment' => 'Perlu Retensi',
                'name' => 'Kantin Karyawan Site A',
                'email' => 'kantin.sitea@example.test',
                'phone' => '081100000107',
                'address' => 'Kawasan Industri Morowali',
                'orders' => [
                    ['days_ago' => 37, 'items' => ['dada-ayam-fillet' => 4, 'bakso-sapi' => 4]],
                    ['days_ago' => 83, 'items' => ['paha-bawah-ayam' => 3, 'chicken-nugget' => 4]],
                ],
            ],
        ];

        foreach ($customers as $customer) {
            $user = User::query()->firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                    'password' => 'customer12345',
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            foreach ($customer['orders'] as $index => $orderData) {
                $lineItems = [];
                $totalAmount = 0;

                foreach ($orderData['items'] as $slug => $quantity) {
                    $product = $products->get($slug);

                    if (! $product) {
                        continue;
                    }

                    $subtotal = (int) $product->price * (int) $quantity;
                    $totalAmount += $subtotal;
                    $lineItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_slug' => $product->slug,
                        'category' => $product->category,
                        'price' => (int) $product->price,
                        'quantity' => (int) $quantity,
                        'subtotal' => $subtotal,
                    ];
                }

                if ($lineItems === []) {
                    continue;
                }

                $paidAt = now()
                    ->subDays((int) $orderData['days_ago'])
                    ->setTime(9 + ($index % 7), 0, 0);
                $orderNumber = sprintf('RFM-%s-%02d', $customer['code'], $index + 1);

                $order = Order::query()->updateOrCreate(
                    ['order_number' => $orderNumber],
                    [
                        'user_id' => $user->id,
                        'customer_name' => $customer['name'],
                        'customer_email' => $customer['email'],
                        'customer_phone' => $customer['phone'],
                        'shipping_address' => $customer['address'],
                        'total_amount' => $totalAmount,
                        'status' => Order::STATUS_COMPLETED,
                        'payment_status' => Order::PAYMENT_PAID,
                        'payment_method' => 'midtrans',
                        'paid_at' => $paidAt,
                        'notes' => 'Dummy data RFM - '.$customer['segment'],
                    ]
                );

                $order->forceFill([
                    'created_at' => $paidAt->copy()->subMinutes(15),
                    'updated_at' => $paidAt,
                ])->saveQuietly();

                $order->items()->delete();
                $order->items()->createMany($lineItems);

                $order->statusHistories()->updateOrCreate(
                    ['source' => 'seeder'],
                    [
                        'from_status' => null,
                        'to_status' => Order::STATUS_COMPLETED,
                        'from_payment_status' => null,
                        'to_payment_status' => Order::PAYMENT_PAID,
                        'note' => 'Dummy order lunas untuk simulasi RFM pelanggan.',
                        'changed_by' => null,
                        'created_at' => $paidAt,
                        'updated_at' => $paidAt,
                    ]
                );
            }
        }
    }
}
