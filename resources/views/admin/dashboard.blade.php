@extends('layouts.admin')

@section('admin_kicker', 'Overview')
@section('admin_title', 'Analytics Dashboard')

@section('admin_content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Revenue</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($paidOrderCount) }} order lunas</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Jumlah Order</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalOrderCount) }}</p>
            <p class="mt-2 text-xs text-slate-500">Rata-rata Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Penjualan Hari Ini</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($todayOrderCount) }} order lunas</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Penjualan Minggu Ini</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($weekRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($weekOrderCount) }} order lunas</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Client Sales</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($salesClientCount) }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($dealClientCount) }} deal, {{ number_format($prospectClientCount) }} prospek</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Follow Up Sales</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ number_format($dueFollowUpClientCount) }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($followUpClientCount) }} client berstatus follow up</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Artikel</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($articleCount) }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($publishedArticleCount) }} publish di website</p>
        </div>
    </div>

    <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white p-5">
        @php
            $rfmBadgeClasses = [
                'High Value Customer' => 'bg-emerald-100 text-emerald-700',
                'High Value Risiko Churn' => 'bg-amber-100 text-amber-700',
                'Pelanggan Potensial' => 'bg-cyan-100 text-cyan-700',
                'Pelanggan Baru Aktif' => 'bg-sky-100 text-sky-700',
                'Pembeli Rutin Hemat' => 'bg-violet-100 text-violet-700',
                'Pelanggan Jarang Beli' => 'bg-rose-100 text-rose-700',
                'Perlu Retensi' => 'bg-rose-100 text-rose-700',
            ];
        @endphp
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Segmentasi Pelanggan RFM</h2>
                <p class="mt-1 max-w-2xl text-sm text-slate-500">Analisis pola pembelian dari order lunas memakai normalisasi RFM dan clustering K-Means.</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Periode {{ $customerSegmentation['period']['label'] }}</p>
            </div>
            <div class="w-full lg:max-w-xl">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="grid gap-2 sm:grid-cols-[1fr_1fr_auto_auto]">
                    <input
                        type="date"
                        name="segment_start"
                        value="{{ $segmentationFilters['segment_start'] }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-slate-500"
                        aria-label="Tanggal mulai segmentasi"
                    >
                    <input
                        type="date"
                        name="segment_end"
                        value="{{ $segmentationFilters['segment_end'] }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-slate-500"
                        aria-label="Tanggal akhir segmentasi"
                    >
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white hover:bg-slate-700">Filter</button>
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.14em] text-slate-600 hover:border-slate-400">Reset</a>
                </form>
            </div>
        </div>

        @if ($customerSegmentation['totalCustomers'] === 0)
            <p class="mt-5 rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                Segmentasi akan tampil setelah ada order lunas dari pelanggan pada periode yang dipilih.
            </p>
        @else
            <div class="mt-5 grid gap-5 xl:grid-cols-[0.92fr_1.08fr]">
                <div class="space-y-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="relative rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 pb-10">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-indigo-700">Customer Dianalisis</p>
                            <p class="mt-1 text-2xl font-bold text-indigo-950">{{ number_format($customerSegmentation['totalCustomers']) }}</p>
                            <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-indigo-100 bg-white/90 text-xs font-bold text-indigo-700 shadow-sm transition hover:border-indigo-300 hover:bg-white" data-info-trigger data-info-title="Customer Dianalisis" data-info-body="Jumlah pelanggan yang masuk ke perhitungan segmentasi. Pelanggan dihitung jika memiliki order lunas pada periode yang dipilih.">
                                <span aria-hidden="true">i</span>
                                <span class="sr-only">Penjelasan customer dianalisis</span>
                            </button>
                        </div>
                        <div class="relative rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 pb-10">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-amber-700">Rata-rata Frekuensi</p>
                            <p class="mt-1 text-2xl font-bold text-amber-950">{{ number_format($customerSegmentation['averages']['frequency'], 1, ',', '.') }} order</p>
                            <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-amber-100 bg-white/90 text-xs font-bold text-amber-700 shadow-sm transition hover:border-amber-300 hover:bg-white" data-info-trigger data-info-title="Rata-rata Frekuensi" data-info-body="Frekuensi adalah seberapa sering pelanggan melakukan order lunas. Nilai ini menunjukkan rata-rata jumlah order per pelanggan pada periode analisis.">
                                <span aria-hidden="true">i</span>
                                <span class="sr-only">Penjelasan rata-rata frekuensi</span>
                            </button>
                        </div>
                        <div class="relative rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 pb-10">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-700">Rata-rata Monetary</p>
                            <p class="mt-1 text-xl font-bold text-emerald-950">Rp {{ number_format($customerSegmentation['averages']['monetary'], 0, ',', '.') }}</p>
                            <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-emerald-100 bg-white/90 text-xs font-bold text-emerald-700 shadow-sm transition hover:border-emerald-300 hover:bg-white" data-info-trigger data-info-title="Rata-rata Monetary" data-info-body="Monetary adalah nilai total belanja pelanggan. Card ini menampilkan rata-rata omzet dari pelanggan yang dianalisis, dihitung dari order yang sudah lunas.">
                                <span aria-hidden="true">i</span>
                                <span class="sr-only">Penjelasan rata-rata monetary</span>
                            </button>
                        </div>
                        <div class="relative rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pb-10">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Transaksi Tertinggi</p>
                            <p class="mt-1 truncate text-sm font-bold text-slate-950">{{ $customerSegmentation['topCustomer']['customer_name'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">Rp {{ number_format($customerSegmentation['topCustomer']['monetary'], 0, ',', '.') }}</p>
                            <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:bg-white" data-info-trigger data-info-title="Transaksi Tertinggi" data-info-body="Menampilkan pelanggan dengan nilai belanja terbesar pada periode analisis. Data ini membantu admin melihat pelanggan yang paling besar kontribusi omzetnya.">
                                <span aria-hidden="true">i</span>
                                <span class="sr-only">Penjelasan transaksi tertinggi</span>
                            </button>
                        </div>
                    </div>

                    <div class="relative rounded-xl border border-slate-100 px-4 py-4 pb-11">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Distribusi Cluster</h3>
                                <p class="mt-1 text-xs text-slate-500">Jumlah customer dan kontribusi revenue tiap cluster.</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                {{ number_format($customerSegmentation['clusterCount']) }} cluster
                            </span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @foreach ($customerSegmentation['clusterDistribution'] as $distribution)
                                <div class="grid gap-2 sm:grid-cols-[150px_1fr_90px] sm:items-center">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900">Cluster {{ $distribution['cluster_number'] }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $distribution['label'] }}</p>
                                    </div>
                                    <div>
                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                            <div
                                                class="h-2.5 rounded-full bg-slate-900"
                                                style="width: {{ max(4, $distribution['customer_share']) }}%;"
                                            ></div>
                                        </div>
                                        <p class="mt-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                                            Revenue {{ number_format($distribution['revenue_share'], 1, ',', '.') }}%
                                        </p>
                                    </div>
                                    <div class="text-xs font-bold text-slate-900 sm:text-right">
                                        {{ number_format($distribution['customer_count']) }} customer
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:text-slate-900" data-info-trigger data-info-title="Distribusi Cluster" data-info-body="Cluster adalah kelompok pelanggan dengan pola pembelian yang mirip. Distribusi ini menunjukkan jumlah pelanggan di tiap kelompok dan persentase kontribusi revenue dari kelompok tersebut.">
                            <span aria-hidden="true">i</span>
                            <span class="sr-only">Penjelasan distribusi cluster</span>
                        </button>
                    </div>

                    @if (! empty($customerSegmentation['testedClusterScores']))
                        <div class="relative rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 pb-10">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <span class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Uji Silhouette</span>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-700">
                                    Best {{ number_format($customerSegmentation['silhouetteScore'], 3) }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($customerSegmentation['testedClusterScores'] as $clusterScore)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs text-slate-600">
                                        K={{ $clusterScore['cluster_count'] }}: {{ number_format($clusterScore['score'], 3) }}
                                    </span>
                                @endforeach
                            </div>
                            <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:text-slate-900" data-info-trigger data-info-title="Silhouette Score" data-info-body="Silhouette score dipakai untuk menilai kualitas pembagian cluster. Nilainya berkisar dari -1 sampai 1. Makin mendekati 1 berarti pelanggan dalam cluster makin mirip satu sama lain dan makin berbeda dari cluster lain.">
                                <span aria-hidden="true">i</span>
                                <span class="sr-only">Penjelasan silhouette score</span>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="relative rounded-xl border border-slate-100 p-4 pb-11">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Strategi Marketing per Cluster</h3>
                            <p class="mt-1 text-xs text-slate-500">Ringkasan strategi dibuat lebih pendek agar dashboard mudah dipindai.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                            {{ number_format(count($customerSegmentation['segments'])) }} segment
                        </span>
                    </div>
                    <div class="grid gap-3 xl:max-h-[545px] xl:overflow-y-auto xl:pr-2">
                        @foreach ($customerSegmentation['segments'] as $segment)
                            <div class="relative rounded-xl border border-slate-100 bg-white px-4 py-3 pb-10 shadow-[0_8px_20px_rgba(15,23,42,0.04)]">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <span class="rounded-full {{ $rfmBadgeClasses[$segment['label']] ?? 'bg-slate-100 text-slate-700' }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                            Cluster {{ $segment['cluster_number'] }}
                                        </span>
                                        <h3 class="mt-2 truncate text-sm font-bold text-slate-900">{{ $segment['label'] }}</h3>
                                        <p class="mt-1 text-xs text-slate-500">{{ number_format($segment['customer_count']) }} customer</p>
                                    </div>
                                    <p class="shrink-0 text-right text-xs font-bold text-slate-900">Rp {{ number_format($segment['total_revenue'], 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                                    <div class="rounded-lg bg-slate-50 px-2 py-2">
                                        <p class="font-semibold text-slate-500">R</p>
                                        <p class="mt-1 font-bold text-slate-900">{{ number_format($segment['avg_recency'], 1, ',', '.') }} hr</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 px-2 py-2">
                                        <p class="font-semibold text-slate-500">F</p>
                                        <p class="mt-1 font-bold text-slate-900">{{ number_format($segment['avg_frequency'], 1, ',', '.') }}x</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 px-2 py-2">
                                        <p class="font-semibold text-slate-500">M</p>
                                        <p class="mt-1 truncate font-bold text-slate-900">Rp {{ number_format($segment['avg_monetary'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 rounded-lg bg-slate-50 px-3 py-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Strategi</p>
                                    <p class="mt-1 text-sm font-bold text-slate-900">{{ $segment['strategy_title'] }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit($segment['recommendation'], 120) }}</p>
                                </div>
                                <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:text-slate-900" data-info-trigger data-info-title="Segment {{ $segment['label'] }}" data-info-body="Card ini menjelaskan satu kelompok pelanggan. R berarti recency atau jarak dari order terakhir, F berarti frequency atau seberapa sering order, dan M berarti monetary atau nilai belanja. Strategi di bawahnya adalah saran tindakan marketing untuk kelompok ini.">
                                    <span aria-hidden="true">i</span>
                                    <span class="sr-only">Penjelasan segment {{ $segment['label'] }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:text-slate-900" data-info-trigger data-info-title="Strategi Marketing per Cluster" data-info-body="Bagian ini merangkum rekomendasi tindakan untuk tiap cluster pelanggan. Tujuannya agar admin bisa menentukan pendekatan follow up, promosi, retensi, atau prioritas layanan berdasarkan pola belanja pelanggan.">
                        <span aria-hidden="true">i</span>
                        <span class="sr-only">Penjelasan strategi marketing per cluster</span>
                    </button>
                </div>
            </div>

            <div class="relative mt-5 rounded-xl border border-slate-100 pb-10">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Customer Sample</h3>
                        <p class="mt-1 text-xs text-slate-500">10 customer pertama dari hasil analisis RFM.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                        Top 10
                    </span>
                </div>
                <div class="max-h-[360px] overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-white">
                            <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                                <th class="px-3 py-3">Customer</th>
                                <th class="px-3 py-3">Segment</th>
                                <th class="px-3 py-3">Recency</th>
                                <th class="px-3 py-3">Frequency</th>
                                <th class="px-3 py-3">Monetary</th>
                                <th class="px-3 py-3">RFM</th>
                                <th class="px-3 py-3">Order Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerSegmentation['customers']->take(10) as $customer)
                                <tr class="border-b border-slate-100">
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900">{{ $customer['customer_name'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $customer['customer_phone'] ?: $customer['customer_email'] }}</p>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="rounded-full {{ $rfmBadgeClasses[$customer['segment_label']] ?? 'bg-slate-100 text-slate-700' }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                            Cluster {{ $customer['cluster_number'] }}
                                        </span>
                                        <p class="mt-1 text-xs text-slate-500">{{ $customer['segment_label'] }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">{{ number_format($customer['recency']) }} hari</td>
                                    <td class="px-3 py-3 text-slate-600">{{ number_format($customer['frequency']) }} order</td>
                                    <td class="px-3 py-3 font-semibold text-slate-900">Rp {{ number_format($customer['monetary'], 0, ',', '.') }}</td>
                                    <td class="px-3 py-3 text-slate-600">
                                        R{{ $customer['rfm']['recency_score'] }}
                                        F{{ $customer['rfm']['frequency_score'] }}
                                        M{{ $customer['rfm']['monetary_score'] }}
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">{{ $customer['last_order_at']->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="absolute bottom-3 right-3 inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-400 hover:text-slate-900" data-info-trigger data-info-title="Customer Sample dan Skor RFM" data-info-body="Tabel ini menampilkan contoh pelanggan dari hasil segmentasi. RFM adalah singkatan dari Recency, Frequency, dan Monetary. Skor R/F/M membantu melihat pelanggan mana yang baru belanja, sering belanja, dan punya nilai belanja tinggi.">
                    <span aria-hidden="true">i</span>
                    <span class="sr-only">Penjelasan customer sample dan skor RFM</span>
                </button>
            </div>
        @endif
    </section>

    <div data-info-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="dashboard-info-modal-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Penjelasan</p>
                    <h3 id="dashboard-info-modal-title" data-info-modal-title class="mt-1 text-lg font-bold text-slate-900">Informasi</h3>
                </div>
                <button type="button" data-info-modal-close class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-lg leading-none text-slate-500 hover:border-slate-400 hover:text-slate-900" aria-label="Tutup popup">
                    &times;
                </button>
            </div>
            <p data-info-modal-body class="mt-4 text-sm leading-6 text-slate-600"></p>
            <div class="mt-5 flex justify-end">
                <button type="button" data-info-modal-close class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white hover:bg-slate-700">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Monitoring Aktivitas Sales</h2>
                <p class="mt-1 text-sm text-slate-500">Aktivitas terbaru dari client resto dan cafe.</p>
            </div>
            <a href="{{ route('admin.sales-clients.index') }}" class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500 hover:text-slate-900">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                        <th class="px-3 py-3">Client</th>
                        <th class="px-3 py-3">Jenis</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3">Aktivitas</th>
                        <th class="px-3 py-3">Follow Up</th>
                        <th class="px-3 py-3">Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesActivities as $activity)
                        @php
                            $client = $activity->client;

                            if (! $client) {
                                continue;
                            }
                        @endphp
                        <tr class="border-b border-slate-100">
                            <td class="px-3 py-3">
                                <a href="{{ route('admin.sales-clients.edit', $client) }}" class="font-semibold text-slate-900 hover:underline">{{ $client->business_name }}</a>
                                <p class="mt-1 text-xs text-slate-500">{{ $client->phone ?: '-' }}</p>
                            </td>
                            <td class="px-3 py-3 text-slate-600">{{ \App\Models\SalesClient::businessTypeLabel($client->business_type) }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full {{ \App\Models\SalesClient::statusBadgeClass($activity->status) }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                    {{ \App\Models\SalesClient::statusLabel($activity->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-slate-600">
                                <p>{{ \Illuminate\Support\Str::limit($activity->description ?: '-', 80) }}</p>
                                <p class="mt-1 text-[10px] uppercase tracking-[0.12em] text-slate-400">{{ $activity->activity_date?->format('d M Y') }}</p>
                            </td>
                            <td class="px-3 py-3 text-slate-600">{{ $activity->next_follow_up_at?->format('d M Y') ?: '-' }}</td>
                            <td class="px-3 py-3 text-slate-600">{{ $activity->user?->name ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-slate-500">Belum ada aktivitas sales.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6 grid items-start gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Grafik Penjualan Harian</h2>
                    <p class="mt-1 text-sm text-slate-500">Revenue order lunas dalam 7 hari terakhir.</p>
                </div>
                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-right">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-emerald-700">Total</p>
                    <p class="text-sm font-bold text-emerald-700">Rp {{ number_format($dailySalesChart['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <div class="flex h-56 min-w-[640px] items-end gap-3 border-b border-slate-200 pb-5">
                    @foreach ($dailySalesChart['entries'] as $entry)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                            <p class="h-8 text-center text-[10px] font-semibold leading-tight text-slate-500">
                                Rp {{ number_format($entry['revenue'], 0, ',', '.') }}
                            </p>
                            <div class="flex h-32 w-full items-end justify-center">
                                <div
                                    class="w-10 rounded-t-xl bg-slate-900 transition hover:bg-emerald-600"
                                    style="height: {{ $entry['height'] }}%;"
                                    title="{{ $entry['orders'] }} order - Rp {{ number_format($entry['revenue'], 0, ',', '.') }}"
                                ></div>
                            </div>
                            <p class="text-xs font-semibold text-slate-700">{{ $entry['label'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $entry['orders'] }} order</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Aktivitas Order</h2>
                    <p class="mt-1 text-sm text-slate-500">Notifikasi order terbaru dibuat ringkas.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Live</span>
            </div>

            <details class="group mt-4 rounded-xl border border-slate-100 bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-sm font-semibold text-slate-900 [&::-webkit-details-marker]:hidden">
                    <span>Lihat daftar aktivitas</span>
                    <span class="inline-flex items-center gap-2 text-xs text-slate-500">
                        {{ number_format($orderActivities->count()) }} aktivitas
                        <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </summary>

                <div class="max-h-[310px] overflow-y-auto border-t border-slate-100 bg-white p-3">
                    <div class="space-y-3">
                        @forelse ($orderActivities as $activity)
                            @php
                                $order = $activity->order;

                                if (! $order) {
                                    continue;
                                }

                                if ($activity->from_status === null && $activity->from_payment_status === null) {
                                    $activityTitle = 'Order baru diterima';
                                    $activityText = $order->customer_name.' membuat order '.$order->order_number;
                                } elseif ($activity->from_payment_status !== $activity->to_payment_status) {
                                    $activityTitle = 'Update pembayaran';
                                    $activityText = $order->order_number.' menjadi '.\App\Models\Order::paymentStatusLabel((string) $activity->to_payment_status);
                                } else {
                                    $activityTitle = 'Update status order';
                                    $activityText = $order->order_number.' menjadi '.\App\Models\Order::statusLabel((string) $activity->to_status);
                                }
                            @endphp
                            <a href="{{ route('admin.orders.show', $order) }}" class="block rounded-xl border border-slate-100 px-3 py-3 transition hover:border-slate-300 hover:bg-slate-50">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900">{{ $activityTitle }}</p>
                                        <p class="mt-1 text-xs leading-5 text-slate-600">{{ $activityText }}</p>
                                        <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">{{ $activity->created_at?->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="rounded-xl bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">Belum ada aktivitas order.</p>
                        @endforelse
                    </div>
                </div>
            </details>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Grafik Penjualan Mingguan</h2>
                    <p class="mt-1 text-sm text-slate-500">Akumulasi revenue order lunas per minggu.</p>
                </div>
                <div class="rounded-xl bg-slate-50 px-3 py-2 text-right">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">6 Minggu</p>
                    <p class="text-sm font-bold text-slate-900">Rp {{ number_format($weeklySalesChart['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($weeklySalesChart['entries'] as $entry)
                    <div class="grid gap-2 sm:grid-cols-[120px_1fr_120px] sm:items-center">
                        <div>
                            <p class="text-xs font-semibold text-slate-700">{{ $entry['label'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $entry['orders'] }} order</p>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: {{ $entry['height'] }}%;"></div>
                        </div>
                        <p class="text-sm font-bold text-slate-900 sm:text-right">Rp {{ number_format($entry['revenue'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            @php
                $maxSoldQuantity = max(1, (int) $topProducts->max('total_quantity'));
            @endphp
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Produk Terlaris</h2>
                    <p class="mt-1 text-sm text-slate-500">Berdasarkan kuantitas dari order lunas.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Top 5</span>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($topProducts as $product)
                    @php
                        $quantityPercent = max(5, (int) round(((int) $product->total_quantity / $maxSoldQuantity) * 100));
                    @endphp
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $product->product_name }}</p>
                                <p class="mt-1 text-xs text-slate-500">Revenue Rp {{ number_format((int) $product->total_revenue, 0, ',', '.') }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ number_format((int) $product->total_quantity) }} terjual</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-slate-900" style="width: {{ $quantityPercent }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">Belum ada produk terjual dari order lunas.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Order Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500 hover:text-slate-900">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-3 py-3">Order</th>
                            <th class="px-3 py-3">Customer</th>
                            <th class="px-3 py-3">Item</th>
                            <th class="px-3 py-3">Total</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 font-semibold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-3 py-3 text-slate-600">{{ $order->customer_name }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $order->items_count }}</td>
                                <td class="px-3 py-3 font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">
                                        {{ \App\Models\Order::statusLabel($order->status) }}
                                    </span>
                                    <p class="mt-1 text-[10px] uppercase tracking-[0.12em] text-slate-500">{{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}</p>
                                </td>
                                <td class="px-3 py-3">
                                    @if ($order->isPaid())
                                        <a href="{{ route('admin.orders.invoice', $order) }}" class="admin-btn-neutral px-3 py-2 text-[10px]">PDF</a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-slate-500">Belum ada order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Payment Status</h2>
                <div class="mt-4 space-y-3">
                    @foreach (['paid', 'pending', 'failed', 'expired'] as $status)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $status }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ (int) ($salesByStatus[$status] ?? 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Info Operasional</h2>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-slate-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Member</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($memberCount) }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Produk</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($productCount) }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Artikel</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($articleCount) }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-amber-700">Pending</p>
                        <p class="mt-1 text-xl font-bold text-amber-700">{{ number_format($pendingOrders) }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-700">Lunas</p>
                        <p class="mt-1 text-xl font-bold text-emerald-700">{{ number_format($paidOrderCount) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Low Stock Alert</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span class="text-sm text-slate-700">{{ $product->name }}</span>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-amber-700">
                                Stok {{ $product->stock }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Semua stok masih aman.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
