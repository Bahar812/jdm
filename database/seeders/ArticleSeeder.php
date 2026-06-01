<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Cara Menjaga Kualitas Frozen Food dari Gudang hingga Dapur Mitra',
                'slug' => 'cara-menjaga-kualitas-frozen-food',
                'category' => 'Artikel',
                'image_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'Panduan singkat menjaga suhu, pengemasan, dan proses distribusi frozen food agar kualitas produk tetap stabil.',
                'content' => implode("\n\n", [
                    'Kualitas frozen food sangat bergantung pada rantai dingin yang konsisten. Produk perlu disimpan pada suhu yang sesuai sejak diterima di gudang sampai tiba di dapur mitra.',
                    'Selain suhu, pengemasan juga perlu diperhatikan. Kemasan yang rapat membantu mengurangi risiko kontaminasi dan menjaga tekstur produk selama perjalanan.',
                    'JDM Frozen Food menjaga proses distribusi dengan pengecekan stok, pengemasan rapi, dan komunikasi pengiriman yang jelas kepada mitra usaha.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Tips Memilih Daging Beku Berkualitas untuk Usaha Kuliner',
                'slug' => 'tips-memilih-daging-beku-berkualitas',
                'category' => 'Tips',
                'image_url' => 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Kenali warna, tekstur, kemasan, dan sumber pasokan sebelum memilih daging beku untuk kebutuhan usaha.',
                'content' => implode("\n\n", [
                    'Daging beku berkualitas biasanya memiliki warna yang natural, tidak berbau menyengat, dan tidak memiliki banyak kristal es berlebihan pada permukaannya.',
                    'Untuk kebutuhan usaha kuliner, konsistensi pasokan sama pentingnya dengan kualitas produk. Supplier yang rapi membantu dapur menjaga standar menu setiap hari.',
                    'Sebelum membeli dalam jumlah besar, pastikan jenis potongan, ukuran, dan kebutuhan penyimpanan sudah sesuai dengan operasional dapur.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Kebutuhan Ayam Beku untuk Katering, Restoran, dan Retail',
                'slug' => 'kebutuhan-ayam-beku-untuk-katering-restoran-dan-retail',
                'category' => 'Produk',
                'image_url' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Ayam beku menjadi pilihan praktis untuk dapur usaha karena stok mudah diatur dan proses olah lebih efisien.',
                'content' => implode("\n\n", [
                    'Ayam beku banyak digunakan oleh katering, restoran, dan retail karena mudah disimpan serta fleksibel untuk berbagai menu.',
                    'Penggunaan produk beku membantu pemilik usaha mengatur stok harian tanpa terlalu sering melakukan pembelian mendadak.',
                    'Dengan supplier yang tepat, pelaku usaha bisa mendapatkan varian ayam sesuai kebutuhan, mulai dari karkas, fillet, sampai potongan tertentu.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Pentingnya Rantai Dingin dalam Pengiriman Produk Frozen',
                'slug' => 'pentingnya-rantai-dingin-dalam-pengiriman-produk-frozen',
                'category' => 'Distribusi',
                'image_url' => 'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Rantai dingin membantu menjaga keamanan pangan dan kualitas produk selama proses pengiriman.',
                'content' => implode("\n\n", [
                    'Rantai dingin adalah proses menjaga produk tetap pada suhu rendah selama penyimpanan dan distribusi.',
                    'Jika suhu tidak stabil, kualitas produk bisa menurun dan risiko kerusakan menjadi lebih tinggi. Karena itu, pengiriman frozen food membutuhkan proses yang disiplin.',
                    'Pengemasan yang baik, jadwal pengiriman yang jelas, dan pengecekan kondisi produk menjadi bagian penting dalam layanan distribusi.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Strategi Menyiapkan Stok Frozen Food untuk Permintaan Harian',
                'slug' => 'strategi-menyiapkan-stok-frozen-food-untuk-permintaan-harian',
                'category' => 'Bisnis',
                'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Perencanaan stok membantu bisnis kuliner menghindari kekurangan bahan dan menjaga operasional tetap lancar.',
                'content' => implode("\n\n", [
                    'Permintaan harian yang berubah-ubah membuat pelaku usaha perlu memiliki perencanaan stok yang praktis.',
                    'Catat produk yang paling sering digunakan, hitung rata-rata pemakaian mingguan, lalu sesuaikan jadwal pembelian dengan kapasitas penyimpanan.',
                    'Supplier frozen food yang responsif dapat membantu menjaga stok tetap tersedia tanpa membuat dapur menyimpan produk terlalu banyak.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Keuntungan Memiliki Supplier Frozen Food Tetap untuk Usaha',
                'slug' => 'keuntungan-memiliki-supplier-frozen-food-tetap-untuk-usaha',
                'category' => 'Kemitraan',
                'image_url' => 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Supplier tetap membantu usaha mendapat kualitas lebih konsisten, komunikasi lebih cepat, dan harga yang lebih terukur.',
                'content' => implode("\n\n", [
                    'Memiliki supplier tetap membuat pemilik usaha lebih mudah mengatur pembelian bahan baku dan menjaga standar menu.',
                    'Hubungan kerja sama yang rutin juga membuat kebutuhan produk, volume, dan jadwal pengiriman lebih mudah disesuaikan.',
                    'Bagi mitra usaha, kejelasan pasokan adalah bagian penting untuk menjaga pelayanan kepada pelanggan tetap stabil.',
                ]),
                'is_published' => true,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Draft Ide Artikel Promo Paket Frozen Food',
                'slug' => 'draft-ide-artikel-promo-paket-frozen-food',
                'category' => 'Draft',
                'image_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Contoh artikel draft untuk melihat status non-publish di dashboard admin.',
                'content' => 'Ini adalah contoh artikel draft untuk kebutuhan demo admin. Artikel ini tidak tampil di halaman website client sampai status publish diaktifkan.',
                'is_published' => false,
                'published_at' => null,
            ],
        ];

        foreach ($articles as $article) {
            Article::query()->updateOrCreate(
                ['slug' => $article['slug']],
                $article,
            );
        }
    }
}
