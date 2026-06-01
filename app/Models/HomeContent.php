<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_IMAGE = 'image';

    protected $fillable = [
        'content_key',
        'value',
    ];

    public static function sections(): array
    {
        return [
            [
                'key' => 'profile',
                'label' => 'Hero / Company Profile',
                'fields' => [
                    self::field('profile_kicker', 'Kicker', self::TYPE_TEXT, 'Company Profile'),
                    self::field('profile_title', 'Judul', self::TYPE_TEXT, 'Supplier Frozen Food untuk Mitra Usaha'),
                    self::field('profile_description', 'Deskripsi', self::TYPE_TEXTAREA, 'CV. Juragan Daging Morowali menyediakan daging, ayam, ikan, dan produk frozen food pilihan untuk kebutuhan usaha kuliner, retail, katering, serta pelanggan wilayah Morowali.'),
                    self::field('profile_button_label', 'Label Button', self::TYPE_TEXT, 'More About Us'),
                    self::field('profile_image_1', 'Gambar 1', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=900&q=80'),
                    self::field('profile_image_2', 'Gambar 2', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=900&q=80'),
                    self::field('profile_image_3', 'Gambar 3', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=900&q=80'),
                    self::field('profile_image_4', 'Gambar 4', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=900&q=80'),
                ],
            ],
            [
                'key' => 'products',
                'label' => 'Produk & Layanan',
                'fields' => array_merge([
                    self::field('products_kicker', 'Kicker', self::TYPE_TEXT, 'Produk & Layanan'),
                    self::field('products_title', 'Judul', self::TYPE_TEXT, 'Frozen Food Berkualitas'),
                    self::field('products_description', 'Deskripsi', self::TYPE_TEXTAREA, 'Pilih kategori produk utama kami untuk kebutuhan usaha kuliner, retail, katering, dan mitra distribusi.'),
                ], self::slideFields()),
            ],
            [
                'key' => 'advantages',
                'label' => 'Keunggulan Kami',
                'fields' => array_merge([
                    self::field('advantages_title', 'Judul', self::TYPE_TEXT, 'Keunggulan Kami'),
                    self::field('advantages_description', 'Deskripsi', self::TYPE_TEXTAREA, 'CV. Juragan Daging Morowali menyediakan kebutuhan frozen food untuk mitra usaha dengan stok stabil, harga kompetitif, dan proses distribusi yang rapi.'),
                ], self::advantageFields()),
            ],
            [
                'key' => 'vision',
                'label' => 'Visi & Misi',
                'fields' => [
                    self::field('vision_kicker', 'Kicker', self::TYPE_TEXT, 'Visi & Misi'),
                    self::field('vision_title', 'Judul', self::TYPE_TEXT, 'Vision & Mission'),
                    self::field('vision_description', 'Deskripsi', self::TYPE_TEXTAREA, 'Komitmen kami untuk menjadi penyedia makanan halal, bermutu, dan praktis bagi seluruh mitra.'),
                    self::field('vision_main_image', 'Gambar Utama', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1000&q=80'),
                    self::field('vision_wide_image', 'Gambar Bawah', self::TYPE_IMAGE, 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=1200&q=80'),
                    self::field('vision_badge_number', 'Badge Angka', self::TYPE_TEXT, '100%'),
                    self::field('vision_badge_title', 'Badge Judul', self::TYPE_TEXT, 'Halal & Bermutu'),
                    self::field('vision_badge_subtitle', 'Badge Subjudul', self::TYPE_TEXT, 'General Supplier Frozen Food'),
                    self::field('vision_visi_title', 'Judul Visi', self::TYPE_TEXT, 'Visi'),
                    self::field('vision_visi_description', 'Deskripsi Visi', self::TYPE_TEXTAREA, "Menjadikan Perusahaan Penyedia Makanan yang terjamin kehalalannya, bermutu serta praktis. Menjadi General Supplier yang terdepan dan memberikan yang terbaik bagi pelanggan.\n\nTo become a food supply company that is guaranteed to be halal, quality and practical. Become a leading General Supplier and provide the best for customers."),
                    self::field('vision_misi_title', 'Judul Misi', self::TYPE_TEXT, 'Misi'),
                    self::field('vision_misi_description', 'Deskripsi Misi', self::TYPE_TEXTAREA, "Mengembangkan dan mengenalkan produk makanan lokal yang bermutu. Menyediakan seluruh kebutuhan bagi mitra usaha dengan harga yang pantas dengan kualitas yang terbaik.\n\nDevelop and introduce quality local food products. Providing all the needs for business partners at reasonable prices with the best quality."),
                ],
            ],
            [
                'key' => 'testimonials',
                'label' => 'Testimonial',
                'fields' => array_merge([
                    self::field('testimonials_kicker', 'Kicker', self::TYPE_TEXT, 'Testimonials'),
                    self::field('testimonials_title', 'Judul', self::TYPE_TEXT, 'Apa Kata Mitra Kami'),
                    self::field('testimonials_description', 'Deskripsi', self::TYPE_TEXTAREA, 'Kepercayaan mitra usaha menjadi alasan kami terus menjaga kualitas produk, stok, dan layanan distribusi.'),
                ], self::testimonialFields()),
            ],
            [
                'key' => 'gallery',
                'label' => 'Our Gallery',
                'fields' => array_merge([
                    self::field('gallery_kicker', 'Kicker', self::TYPE_TEXT, 'Our Gallery'),
                    self::field('gallery_title', 'Judul', self::TYPE_TEXT, 'Galeri Produk'),
                ], self::galleryFields()),
            ],
            [
                'key' => 'articles',
                'label' => 'Artikel',
                'fields' => [
                    self::field('articles_title', 'Judul Section', self::TYPE_TEXT, 'Artikel'),
                    self::field('articles_more_label', 'Label Link Semua Artikel', self::TYPE_TEXT, 'Artikel lainnya'),
                ],
            ],
            [
                'key' => 'instagram',
                'label' => 'Instagram',
                'fields' => array_merge([
                    self::field('instagram_kicker', 'Kicker', self::TYPE_TEXT, 'Instagram'),
                    self::field('instagram_title', 'Judul', self::TYPE_TEXT, 'Temukan postingan dan info terbaru lewat Instagram kami'),
                    self::field('instagram_description', 'Deskripsi', self::TYPE_TEXTAREA, 'Ikuti update produk, stok frozen food, dan informasi layanan terbaru dari CV. Juragan Daging Morowali.'),
                    self::field('instagram_button_label', 'Label Button', self::TYPE_TEXT, 'Kunjungi @juragandagingmorowali'),
                    self::field('instagram_link', 'Link Instagram', self::TYPE_TEXT, 'https://www.instagram.com/juragandagingmorowali/'),
                ], self::instagramFields()),
            ],
            [
                'key' => 'contact',
                'label' => 'Kontak',
                'fields' => [
                    self::field('contact_kicker', 'Kicker', self::TYPE_TEXT, 'Hubungi Kami'),
                    self::field('contact_title', 'Judul', self::TYPE_TEXT, 'Siap Menjadi Mitra Anda'),
                    self::field('contact_description', 'Deskripsi', self::TYPE_TEXTAREA, 'Kami siap memenuhi kebutuhan frozen food Anda dengan kualitas terbaik dan layanan yang cepat. Hubungi kami untuk informasi produk, harga, dan kemitraan.'),
                    self::field('contact_card_title', 'Judul Kartu Kontak', self::TYPE_TEXT, 'Kontak Utama'),
                    self::field('contact_email', 'Email', self::TYPE_TEXT, 'juragandagingmorowali@gmail.com'),
                    self::field('contact_phone', 'Telepon', self::TYPE_TEXT, '0855-2268-888'),
                    self::field('contact_address', 'Alamat', self::TYPE_TEXTAREA, 'Northwest Boulevard NV 15 No. 26, Citraland, Kec. Pakal, Surabaya, Jawa Timur 60196'),
                    self::field('contact_google_title', 'Judul Google Business', self::TYPE_TEXT, 'Google Business'),
                    self::field('contact_google_description', 'Deskripsi Google Business', self::TYPE_TEXTAREA, 'JDM Frozen Food, toko makanan beku. Buka sampai pukul 19.00.'),
                    self::field('contact_map_src', 'Google Maps Embed URL', self::TYPE_TEXTAREA, 'https://www.google.com/maps?q=JDM%20Frozen%20Food%2C%20Northwest%20Boulevard%20NV%2015%20No.%2026%2C%20Citraland%2C%20Kec.%20Pakal%2C%20Surabaya%2C%20Jawa%20Timur%2060196&output=embed'),
                    self::field('contact_email_button_label', 'Label Button Email', self::TYPE_TEXT, 'Email Kami'),
                    self::field('contact_phone_button_label', 'Label Button Telepon', self::TYPE_TEXT, 'Telepon'),
                    self::field('contact_whatsapp_button_label', 'Label Button WhatsApp', self::TYPE_TEXT, 'WhatsApp'),
                ],
            ],
            [
                'key' => 'footer',
                'label' => 'Footer',
                'fields' => [
                    self::field('footer_business_name', 'Nama Footer', self::TYPE_TEXT, 'CV. Juragan Daging Morowali'),
                ],
            ],
        ];
    }

    public static function definitions(): array
    {
        return collect(self::sections())
            ->flatMap(fn (array $section): array => array_map(
                fn (array $field): array => array_merge($field, ['section' => $section['key']]),
                $section['fields'],
            ))
            ->values()
            ->all();
    }

    public static function defaultValues(): array
    {
        return collect(self::definitions())
            ->mapWithKeys(fn (array $field): array => [$field['key'] => $field['default'] ?? ''])
            ->all();
    }

    public static function values(): array
    {
        return self::query()
            ->pluck('value', 'content_key')
            ->all();
    }

    public static function syncDefaults(): void
    {
        foreach (self::definitions() as $field) {
            self::query()->firstOrCreate(
                ['content_key' => $field['key']],
                ['value' => $field['default'] ?? ''],
            );
        }
    }

    private static function field(string $key, string $label, string $type, string $default = ''): array
    {
        return compact('key', 'label', 'type', 'default');
    }

    private static function slideFields(): array
    {
        $slides = [
            ['Daging Beku', 'Produk Utama', 'Pilihan daging beku dengan kualitas serat, warna, dan kesegaran yang terjaga untuk kebutuhan usaha.', 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=1000&q=80'],
            ['Ayam Beku', 'Siap Olah', 'Produk ayam beku untuk menu harian, dapur usaha, hingga kebutuhan skala besar.', 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1000&q=80'],
            ['Ikan Beku', 'Fresh Frozen', 'Varian ikan segar beku yang praktis diolah dan menjaga rasa alami sampai ke dapur pelanggan.', 'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=1000&q=80'],
            ['Seafood', 'Pilihan Mitra', 'Kebutuhan seafood beku untuk restoran, katering, dan usaha kuliner dengan suplai yang konsisten.', 'https://images.unsplash.com/photo-1565680018434-b513d7f756f0?auto=format&fit=crop&w=1000&q=80'],
            ['Produk Olahan', 'Frozen Food', 'Ragam produk olahan beku yang praktis untuk operasional dapur dan kebutuhan retail.', 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=1000&q=80'],
        ];

        return collect($slides)->flatMap(function (array $slide, int $index): array {
            $number = $index + 1;

            return [
                self::field("products_slide_{$number}_title", "Slide {$number} Judul", self::TYPE_TEXT, $slide[0]),
                self::field("products_slide_{$number}_meta", "Slide {$number} Meta", self::TYPE_TEXT, $slide[1]),
                self::field("products_slide_{$number}_description", "Slide {$number} Deskripsi", self::TYPE_TEXTAREA, $slide[2]),
                self::field("products_slide_{$number}_image", "Slide {$number} Gambar", self::TYPE_IMAGE, $slide[3]),
            ];
        })->all();
    }

    private static function advantageFields(): array
    {
        $cards = [
            ['Harga Kompetitif', 'Pilihan produk untuk mitra usaha dengan harga yang disesuaikan volume dan kebutuhan operasional.', 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=1000&q=80'],
            ['Pengiriman Aman', 'Pengemasan rapi dan pengiriman terjaga agar produk tetap aman sampai ke lokasi pelanggan.', 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1000&q=80'],
            ['Halal dan Lolos NKV', 'Produk dipasok dari sumber terpercaya dan melewati kontrol kualitas sebelum dikirim ke mitra.', 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1000&q=80'],
        ];

        return collect($cards)->flatMap(function (array $card, int $index): array {
            $number = $index + 1;

            return [
                self::field("advantages_card_{$number}_title", "Card {$number} Judul", self::TYPE_TEXT, $card[0]),
                self::field("advantages_card_{$number}_description", "Card {$number} Deskripsi", self::TYPE_TEXTAREA, $card[1]),
                self::field("advantages_card_{$number}_image", "Card {$number} Gambar", self::TYPE_IMAGE, $card[2]),
            ];
        })->all();
    }

    private static function testimonialFields(): array
    {
        $testimonials = [
            ['Kualitas dagingnya konsisten dan pengirimannya rapi. Sangat membantu operasional dapur kami setiap hari.', 'Rina S.', 'Pemilik Katering', 'https://randomuser.me/api/portraits/women/32.jpg'],
            ['Stok frozen food selalu siap saat kami butuh. Komunikasi juga cepat, jadi pemesanan lebih mudah.', 'Andi P.', 'Owner Warung Makan', 'https://randomuser.me/api/portraits/men/45.jpg'],
            ['Produk diterima dalam kondisi baik dan tetap beku. Harga cocok untuk kebutuhan usaha skala harian.', 'Maya L.', 'Mitra Retail', 'https://randomuser.me/api/portraits/women/68.jpg'],
            ['Kami terbantu karena pilihan produknya lengkap, mulai dari daging, ayam, ikan, sampai produk olahan.', 'Budi H.', 'Pengelola Restoran', 'https://randomuser.me/api/portraits/men/36.jpg'],
            ['Pelayanannya responsif dan pengemasan produknya rapi. Cocok untuk kebutuhan katering acara besar.', 'Sari N.', 'Vendor Event', 'https://randomuser.me/api/portraits/women/21.jpg'],
            ['Kualitas produk stabil, jadi kami lebih mudah menjaga standar menu untuk pelanggan.', 'Fajar R.', 'Chef Operasional', 'https://randomuser.me/api/portraits/men/14.jpg'],
            ['Pemesanan fleksibel dan timnya membantu menyesuaikan kebutuhan potongan untuk dapur kami.', 'Dewi A.', 'Pemilik UMKM Kuliner', 'https://randomuser.me/api/portraits/women/44.jpg'],
            ['Produk halal dan kualitasnya terjaga. Kami merasa aman menjadikannya pemasok rutin.', 'Rahmat T.', 'Mitra Distribusi', 'https://randomuser.me/api/portraits/men/27.jpg'],
            ['Pengiriman aman, barang sesuai pesanan, dan tim cepat memberi update. Sangat praktis untuk usaha.', 'Nadia K.', 'Owner Kedai', 'https://randomuser.me/api/portraits/women/12.jpg'],
        ];

        return collect($testimonials)->flatMap(function (array $testimonial, int $index): array {
            $number = $index + 1;

            return [
                self::field("testimonial_{$number}_text", "Testimonial {$number} Teks", self::TYPE_TEXTAREA, $testimonial[0]),
                self::field("testimonial_{$number}_name", "Testimonial {$number} Nama", self::TYPE_TEXT, $testimonial[1]),
                self::field("testimonial_{$number}_role", "Testimonial {$number} Role", self::TYPE_TEXT, $testimonial[2]),
                self::field("testimonial_{$number}_image", "Testimonial {$number} Foto", self::TYPE_IMAGE, $testimonial[3]),
            ];
        })->all();
    }

    private static function galleryFields(): array
    {
        $images = [
            ['Daging Beku', 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=1400&q=80'],
            ['Ayam', 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1200&q=80'],
            ['Ikan', 'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=1200&q=80'],
            ['Supplier', 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=1200&q=80'],
            ['Frozen Food', 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=1200&q=80'],
        ];

        return collect($images)->flatMap(function (array $image, int $index): array {
            $number = $index + 1;

            return [
                self::field("gallery_image_{$number}", "Galeri {$number} Gambar", self::TYPE_IMAGE, $image[1]),
                self::field("gallery_badge_{$number}", "Galeri {$number} Badge", self::TYPE_TEXT, $image[0]),
            ];
        })->all();
    }

    private static function instagramFields(): array
    {
        $posts = [
            ['https://www.instagram.com/p/DZAJrlCSLhi/', '/images/instagram/jdm-instagram-01.jpg'],
            ['https://www.instagram.com/p/DYzXj1qyD3I/', '/images/instagram/jdm-instagram-02.jpg'],
            ['https://www.instagram.com/p/DYyNakAy47F/', '/images/instagram/jdm-instagram-03.jpg'],
            ['https://www.instagram.com/p/DXxcoSfS2i8/', '/images/instagram/jdm-instagram-04.jpg'],
            ['https://www.instagram.com/p/DXxYGH_Estn/', '/images/instagram/jdm-instagram-05.jpg'],
            ['https://www.instagram.com/p/DXC1gmskuN2/', '/images/instagram/jdm-instagram-06.jpg'],
        ];

        return collect($posts)->flatMap(function (array $post, int $index): array {
            $number = $index + 1;

            return [
                self::field("instagram_post_{$number}_link", "Post {$number} Link", self::TYPE_TEXT, $post[0]),
                self::field("instagram_post_{$number}_image", "Post {$number} Gambar", self::TYPE_IMAGE, $post[1]),
            ];
        })->all();
    }
}
