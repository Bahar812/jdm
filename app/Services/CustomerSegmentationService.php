<?php

namespace App\Services;

use App\Models\Order;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerSegmentationService
{
    private const MAX_CLUSTER_COUNT = 5;

    public function analyze(?CarbonImmutable $asOf = null, ?CarbonImmutable $periodStart = null, ?CarbonImmutable $periodEnd = null): array
    {
        $periodStart = $periodStart?->startOfDay();
        $periodEnd = $periodEnd?->endOfDay();
        $asOf ??= ($periodEnd ?? CarbonImmutable::now())->endOfDay();

        $customers = $this->buildRfmCustomers($asOf, $periodStart, $periodEnd);
        $customerCount = $customers->count();

        if ($customerCount === 0) {
            return $this->emptyReport($asOf, $periodStart, $periodEnd);
        }

        $customers = $this->normalizeCustomers($customers);
        $points = $customers->pluck('normalized')->all();
        $assignments = array_fill(0, $customerCount, 0);
        $testedClusterScores = [];
        $bestSilhouetteScore = 0.0;

        if ($customerCount === 2) {
            $result = $this->kMeans($points, 2);
            $assignments = $result['assignments'];
            $testedClusterScores[] = [
                'cluster_count' => 2,
                'score' => 0.0,
            ];
        } elseif ($customerCount > 2) {
            $maxClusterCount = min(self::MAX_CLUSTER_COUNT, $customerCount - 1);
            $bestAssignments = $assignments;

            for ($clusterCount = 2; $clusterCount <= $maxClusterCount; $clusterCount++) {
                $result = $this->kMeans($points, $clusterCount);
                $silhouetteScore = $this->silhouetteScore($points, $result['assignments']);

                $testedClusterScores[] = [
                    'cluster_count' => $clusterCount,
                    'score' => round($silhouetteScore, 3),
                ];

                if ($silhouetteScore > $bestSilhouetteScore) {
                    $bestSilhouetteScore = $silhouetteScore;
                    $bestAssignments = $result['assignments'];
                }
            }

            $assignments = $bestAssignments;
        }

        $customers = $customers->values()
            ->map(function (array $customer, int $index) use ($assignments): array {
                $customer['cluster'] = $assignments[$index] ?? 0;

                return $customer;
            });

        [$segments, $labelsByCluster] = $this->summarizeSegments($customers);

        $customers = $customers
            ->map(function (array $customer) use ($labelsByCluster): array {
                $label = $labelsByCluster[$customer['cluster']] ?? [
                    'number' => 1,
                    'label' => 'Pelanggan',
                    'badge_class' => 'bg-slate-100 text-slate-700',
                ];

                $customer['cluster_number'] = $label['number'];
                $customer['segment_label'] = $label['label'];
                $customer['badge_class'] = $label['badge_class'];

                return $customer;
            })
            ->sort(fn (array $left, array $right): int => [
                $left['cluster_number'],
                -$left['monetary'],
                $left['recency'],
            ] <=> [
                $right['cluster_number'],
                -$right['monetary'],
                $right['recency'],
            ])
            ->values();

        $topCustomer = $customers
            ->sortByDesc('monetary')
            ->values()
            ->first();

        return [
            'generatedAt' => $asOf,
            'period' => [
                'start' => $periodStart,
                'end' => $periodEnd,
                'label' => $this->periodLabel($periodStart, $periodEnd),
                'is_filtered' => $periodStart !== null || $periodEnd !== null,
            ],
            'totalCustomers' => $customerCount,
            'clusterCount' => $segments->count(),
            'silhouetteScore' => round($bestSilhouetteScore, 3),
            'testedClusterScores' => $testedClusterScores,
            'averages' => [
                'recency' => round((float) $customers->avg('recency'), 1),
                'frequency' => round((float) $customers->avg('frequency'), 1),
                'monetary' => (int) round((float) $customers->avg('monetary')),
            ],
            'segments' => $segments,
            'clusterDistribution' => $this->clusterDistribution($segments, $customerCount),
            'topCustomer' => $topCustomer,
            'customers' => $customers,
        ];
    }

    private function buildRfmCustomers(CarbonImmutable $asOf, ?CarbonImmutable $periodStart, ?CarbonImmutable $periodEnd): Collection
    {
        $timestampExpression = 'COALESCE(paid_at, created_at)';
        $customerKeyExpression = $this->customerKeyExpression();

        $query = Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->selectRaw($customerKeyExpression.' as customer_key')
            ->selectRaw('MAX(customer_name) as customer_name')
            ->selectRaw('MAX(customer_email) as customer_email')
            ->selectRaw('MAX(customer_phone) as customer_phone')
            ->selectRaw('MIN('.$timestampExpression.') as first_order_at')
            ->selectRaw('MAX('.$timestampExpression.') as last_order_at')
            ->selectRaw('COUNT(*) as frequency')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as monetary')
            ->groupByRaw($customerKeyExpression);

        if ($periodStart || $periodEnd) {
            $this->applyTransactionPeriod($query, $periodStart, $periodEnd);
        }

        return $query
            ->get()
            ->map(function (object $customer) use ($asOf): array {
                $lastOrderAt = CarbonImmutable::parse($customer->last_order_at);
                $recencyDays = (int) max(0, floor($lastOrderAt->startOfDay()->diffInDays($asOf->startOfDay(), false)));

                return [
                    'key' => $customer->customer_key,
                    'customer_name' => $customer->customer_name,
                    'customer_email' => $customer->customer_email,
                    'customer_phone' => $customer->customer_phone,
                    'first_order_at' => CarbonImmutable::parse($customer->first_order_at),
                    'last_order_at' => $lastOrderAt,
                    'recency' => $recencyDays,
                    'frequency' => (int) $customer->frequency,
                    'monetary' => (int) $customer->monetary,
                ];
            })
            ->values();
    }

    private function applyTransactionPeriod(Builder $query, ?CarbonImmutable $periodStart, ?CarbonImmutable $periodEnd): void
    {
        $query->where(function (Builder $query) use ($periodStart, $periodEnd): void {
            $query
                ->where(function (Builder $paidAtQuery) use ($periodStart, $periodEnd): void {
                    $paidAtQuery->whereNotNull('paid_at');

                    if ($periodStart) {
                        $paidAtQuery->where('paid_at', '>=', $periodStart);
                    }

                    if ($periodEnd) {
                        $paidAtQuery->where('paid_at', '<=', $periodEnd);
                    }
                })
                ->orWhere(function (Builder $createdAtQuery) use ($periodStart, $periodEnd): void {
                    $createdAtQuery->whereNull('paid_at');

                    if ($periodStart) {
                        $createdAtQuery->where('created_at', '>=', $periodStart);
                    }

                    if ($periodEnd) {
                        $createdAtQuery->where('created_at', '<=', $periodEnd);
                    }
                });
        });
    }

    private function normalizeCustomers(Collection $customers): Collection
    {
        $bounds = [
            'recency' => [
                'min' => (float) $customers->min('recency'),
                'max' => (float) $customers->max('recency'),
            ],
            'frequency' => [
                'min' => (float) $customers->min('frequency'),
                'max' => (float) $customers->max('frequency'),
            ],
            'monetary' => [
                'min' => (float) $customers->min('monetary'),
                'max' => (float) $customers->max('monetary'),
            ],
        ];

        return $customers->values()
            ->map(function (array $customer) use ($bounds): array {
                $normalizedRecency = $this->normalizeValue((float) $customer['recency'], $bounds['recency']);
                $normalizedFrequency = $this->normalizeValue((float) $customer['frequency'], $bounds['frequency']);
                $normalizedMonetary = $this->normalizeValue((float) $customer['monetary'], $bounds['monetary']);

                $customer['normalized'] = [
                    $normalizedRecency,
                    $normalizedFrequency,
                    $normalizedMonetary,
                ];
                $customer['rfm'] = [
                    'recency_score' => $this->scoreFromNormalized(1 - $normalizedRecency),
                    'frequency_score' => $this->scoreFromNormalized($normalizedFrequency),
                    'monetary_score' => $this->scoreFromNormalized($normalizedMonetary),
                ];
                $customer['rfm']['score'] = $customer['rfm']['recency_score']
                    + $customer['rfm']['frequency_score']
                    + $customer['rfm']['monetary_score'];

                return $customer;
            });
    }

    private function kMeans(array $points, int $clusterCount): array
    {
        $pointCount = count($points);
        $clusterCount = max(1, min($clusterCount, $pointCount));
        $centroids = $this->initialCentroids($points, $clusterCount);
        $assignments = array_fill(0, $pointCount, 0);

        for ($iteration = 0; $iteration < 50; $iteration++) {
            $changed = false;

            foreach ($points as $index => $point) {
                $cluster = $this->nearestCentroid($point, $centroids);

                if ($assignments[$index] !== $cluster) {
                    $assignments[$index] = $cluster;
                    $changed = true;
                }
            }

            $nextCentroids = [];

            for ($cluster = 0; $cluster < $clusterCount; $cluster++) {
                $members = [];

                foreach ($assignments as $pointIndex => $assignedCluster) {
                    if ($assignedCluster === $cluster) {
                        $members[] = $points[$pointIndex];
                    }
                }

                $nextCentroids[$cluster] = $members === []
                    ? $points[$this->farthestPointIndex($points, $centroids)]
                    : $this->meanPoint($members);
            }

            if (! $changed && $this->centroidsAreEqual($centroids, $nextCentroids)) {
                break;
            }

            $centroids = $nextCentroids;
        }

        return [
            'assignments' => $assignments,
            'centroids' => $centroids,
        ];
    }

    private function initialCentroids(array $points, int $clusterCount): array
    {
        $firstIndex = 0;
        $bestScore = null;

        foreach ($points as $index => $point) {
            $score = $point[1] + $point[2] - $point[0];

            if ($bestScore === null || $score > $bestScore) {
                $bestScore = $score;
                $firstIndex = $index;
            }
        }

        $centroids = [$points[$firstIndex]];
        $usedIndexes = [$firstIndex => true];

        while (count($centroids) < $clusterCount) {
            $nextIndex = null;
            $largestDistance = null;

            foreach ($points as $index => $point) {
                if (isset($usedIndexes[$index])) {
                    continue;
                }

                $nearestDistance = min(array_map(
                    fn (array $centroid): float => $this->distance($point, $centroid),
                    $centroids
                ));

                if ($largestDistance === null || $nearestDistance > $largestDistance) {
                    $largestDistance = $nearestDistance;
                    $nextIndex = $index;
                }
            }

            if ($nextIndex === null) {
                break;
            }

            $centroids[] = $points[$nextIndex];
            $usedIndexes[$nextIndex] = true;
        }

        return $centroids;
    }

    private function silhouetteScore(array $points, array $assignments): float
    {
        $pointCount = count($points);

        if ($pointCount < 2 || count(array_unique($assignments)) < 2) {
            return 0.0;
        }

        $scores = [];

        foreach ($points as $index => $point) {
            $cluster = $assignments[$index];
            $sameClusterDistances = [];
            $otherClusterDistances = [];

            foreach ($points as $otherIndex => $otherPoint) {
                if ($index === $otherIndex) {
                    continue;
                }

                $distance = $this->distance($point, $otherPoint);

                if ($assignments[$otherIndex] === $cluster) {
                    $sameClusterDistances[] = $distance;
                } else {
                    $otherClusterDistances[$assignments[$otherIndex]][] = $distance;
                }
            }

            if ($sameClusterDistances === []) {
                $scores[] = 0.0;

                continue;
            }

            $a = array_sum($sameClusterDistances) / count($sameClusterDistances);
            $b = min(array_map(
                fn (array $distances): float => array_sum($distances) / count($distances),
                $otherClusterDistances
            ));
            $denominator = max($a, $b);

            $scores[] = $denominator > 0 ? ($b - $a) / $denominator : 0.0;
        }

        return array_sum($scores) / count($scores);
    }

    private function summarizeSegments(Collection $customers): array
    {
        $global = [
            'avg_recency' => (float) $customers->avg('recency'),
            'avg_frequency' => (float) $customers->avg('frequency'),
            'avg_monetary' => (float) $customers->avg('monetary'),
            'max_recency' => max(1, (int) $customers->max('recency')),
            'max_frequency' => max(1, (int) $customers->max('frequency')),
            'max_monetary' => max(1, (int) $customers->max('monetary')),
        ];

        $segments = $customers->groupBy('cluster')
            ->map(function (Collection $members, int $cluster) use ($global): array {
                $avgRecency = (float) $members->avg('recency');
                $avgFrequency = (float) $members->avg('frequency');
                $avgMonetary = (float) $members->avg('monetary');
                $profile = $this->classifySegment($avgRecency, $avgFrequency, $avgMonetary, $global);

                return [
                    'cluster' => $cluster,
                    'label' => $profile['label'],
                    'strategy_title' => $profile['strategy_title'],
                    'recommendation' => $profile['recommendation'],
                    'badge_class' => $profile['badge_class'],
                    'customer_count' => $members->count(),
                    'avg_recency' => round($avgRecency, 1),
                    'avg_frequency' => round($avgFrequency, 1),
                    'avg_monetary' => (int) round($avgMonetary),
                    'total_revenue' => (int) $members->sum('monetary'),
                    'quality_score' => $this->segmentQualityScore($avgRecency, $avgFrequency, $avgMonetary, $global),
                ];
            })
            ->sortByDesc('quality_score')
            ->values();

        $labelsByCluster = [];

        $segments = $segments->map(function (array $segment, int $index) use (&$labelsByCluster): array {
            $segment['cluster_number'] = $index + 1;
            $labelsByCluster[$segment['cluster']] = [
                'number' => $segment['cluster_number'],
                'label' => $segment['label'],
                'badge_class' => $segment['badge_class'],
            ];

            return $segment;
        });

        return [$segments, $labelsByCluster];
    }

    private function clusterDistribution(Collection $segments, int $customerCount): Collection
    {
        $totalRevenue = max(1, (int) $segments->sum('total_revenue'));

        return $segments
            ->map(fn (array $segment): array => [
                'cluster_number' => $segment['cluster_number'],
                'label' => $segment['label'],
                'badge_class' => $segment['badge_class'],
                'customer_count' => $segment['customer_count'],
                'customer_share' => $customerCount > 0 ? round(($segment['customer_count'] / $customerCount) * 100, 1) : 0.0,
                'total_revenue' => $segment['total_revenue'],
                'revenue_share' => round(($segment['total_revenue'] / $totalRevenue) * 100, 1),
            ])
            ->values();
    }

    private function classifySegment(float $avgRecency, float $avgFrequency, float $avgMonetary, array $global): array
    {
        $recent = $avgRecency <= $global['avg_recency'];
        $frequent = $avgFrequency >= $global['avg_frequency'];
        $valuable = $avgMonetary >= $global['avg_monetary'];
        $lowFrequency = $avgFrequency <= max(1.5, $global['avg_frequency'] * 0.65);
        $oldPurchase = $avgRecency > $global['avg_recency'];

        if ($recent && $frequent && $valuable) {
            return [
                'label' => 'High Value Customer',
                'strategy_title' => 'VIP Loyalty',
                'recommendation' => 'Berikan prioritas stok premium, bonus kecil atau cashback loyalti, dan early access paket daging premium tanpa terlalu sering memberi diskon besar.',
                'badge_class' => 'bg-emerald-100 text-emerald-700',
            ];
        }

        if (! $recent && $valuable) {
            return [
                'label' => 'High Value Risiko Churn',
                'strategy_title' => 'Win-back Personal',
                'recommendation' => 'Follow up lewat WhatsApp dengan voucher comeback khusus, free ongkir untuk repeat order besar, atau penawaran kontrak pasokan mingguan.',
                'badge_class' => 'bg-amber-100 text-amber-700',
            ];
        }

        if ($recent && ! $frequent && $valuable) {
            return [
                'label' => 'Pelanggan Potensial',
                'strategy_title' => 'Naikkan Repeat Order',
                'recommendation' => 'Kirim rekomendasi produk pelengkap berdasarkan order pertama dan voucher pembelian kedua dalam 7 hari.',
                'badge_class' => 'bg-cyan-100 text-cyan-700',
            ];
        }

        if ($recent && ! $frequent) {
            return [
                'label' => 'Pelanggan Baru Aktif',
                'strategy_title' => 'Second Purchase',
                'recommendation' => 'Dorong pembelian kedua dengan voucher follow-up, paket starter, atau rekomendasi menu sesuai produk yang pertama dibeli.',
                'badge_class' => 'bg-sky-100 text-sky-700',
            ];
        }

        if ($frequent) {
            return [
                'label' => 'Pembeli Rutin Hemat',
                'strategy_title' => 'Bundling dan Upsell',
                'recommendation' => 'Tawarkan bundling hemat dengan add-on bumbu, saus, frozen food, atau upgrade ke produk premium agar nilai order naik.',
                'badge_class' => 'bg-violet-100 text-violet-700',
            ];
        }

        if ($oldPurchase && $lowFrequency) {
            return [
                'label' => 'Pelanggan Jarang Beli',
                'strategy_title' => 'Bundling Reaktivasi',
                'recommendation' => 'Tawarkan bundling hemat, gratis ongkir dengan minimum belanja, dan reminder stok untuk mendorong belanja ulang.',
                'badge_class' => 'bg-rose-100 text-rose-700',
            ];
        }

        return [
            'label' => 'Perlu Retensi',
            'strategy_title' => 'Retensi Ringan',
            'recommendation' => 'Aktifkan reminder pembelian ulang, promo terbatas, dan rekomendasi paket sesuai riwayat produk yang pernah dibeli.',
            'badge_class' => 'bg-rose-100 text-rose-700',
        ];
    }

    private function segmentQualityScore(float $avgRecency, float $avgFrequency, float $avgMonetary, array $global): float
    {
        $recencyScore = 1 - min(1, $avgRecency / $global['max_recency']);
        $frequencyScore = min(1, $avgFrequency / $global['max_frequency']);
        $monetaryScore = min(1, $avgMonetary / $global['max_monetary']);

        return $recencyScore + $frequencyScore + $monetaryScore;
    }

    private function normalizeValue(float $value, array $bounds): float
    {
        $range = $bounds['max'] - $bounds['min'];

        if ($range <= 0) {
            return 0.0;
        }

        return ($value - $bounds['min']) / $range;
    }

    private function scoreFromNormalized(float $value): int
    {
        return max(1, min(5, (int) round(1 + ($value * 4))));
    }

    private function customerKeyExpression(): string
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            return "CASE WHEN user_id IS NOT NULL THEN CONCAT('user:', user_id) WHEN customer_email IS NOT NULL AND customer_email <> '' THEN CONCAT('email:', LOWER(customer_email)) ELSE CONCAT('phone:', COALESCE(customer_phone, 'unknown')) END";
        }

        if ($driver === 'pgsql') {
            return "CASE WHEN user_id IS NOT NULL THEN 'user:' || user_id::text WHEN customer_email IS NOT NULL AND customer_email <> '' THEN 'email:' || LOWER(customer_email) ELSE 'phone:' || COALESCE(customer_phone, 'unknown') END";
        }

        return "CASE WHEN user_id IS NOT NULL THEN 'user:' || user_id WHEN customer_email IS NOT NULL AND customer_email <> '' THEN 'email:' || LOWER(customer_email) ELSE 'phone:' || COALESCE(customer_phone, 'unknown') END";
    }

    private function nearestCentroid(array $point, array $centroids): int
    {
        $nearestCluster = 0;
        $nearestDistance = null;

        foreach ($centroids as $cluster => $centroid) {
            $distance = $this->distance($point, $centroid);

            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestCluster = $cluster;
            }
        }

        return $nearestCluster;
    }

    private function farthestPointIndex(array $points, array $centroids): int
    {
        $farthestIndex = 0;
        $farthestDistance = 0.0;

        foreach ($points as $index => $point) {
            $nearestDistance = min(array_map(
                fn (array $centroid): float => $this->distance($point, $centroid),
                $centroids
            ));

            if ($nearestDistance > $farthestDistance) {
                $farthestDistance = $nearestDistance;
                $farthestIndex = $index;
            }
        }

        return $farthestIndex;
    }

    private function meanPoint(array $points): array
    {
        $dimensions = count($points[0]);
        $mean = array_fill(0, $dimensions, 0.0);

        foreach ($points as $point) {
            foreach ($point as $dimension => $value) {
                $mean[$dimension] += $value;
            }
        }

        foreach ($mean as $dimension => $value) {
            $mean[$dimension] = $value / count($points);
        }

        return $mean;
    }

    private function centroidsAreEqual(array $left, array $right): bool
    {
        foreach ($left as $index => $centroid) {
            if (! isset($right[$index])) {
                return false;
            }

            if ($this->distance($centroid, $right[$index]) > 0.000001) {
                return false;
            }
        }

        return true;
    }

    private function distance(array $left, array $right): float
    {
        $sum = 0.0;

        foreach ($left as $dimension => $value) {
            $sum += ($value - $right[$dimension]) ** 2;
        }

        return sqrt($sum);
    }

    private function periodLabel(?CarbonImmutable $periodStart, ?CarbonImmutable $periodEnd): string
    {
        if ($periodStart && $periodEnd) {
            return $periodStart->isSameDay($periodEnd)
                ? $periodStart->format('d M Y')
                : $periodStart->format('d M Y').' - '.$periodEnd->format('d M Y');
        }

        if ($periodStart) {
            return 'Mulai '.$periodStart->format('d M Y');
        }

        if ($periodEnd) {
            return 'Sampai '.$periodEnd->format('d M Y');
        }

        return 'Semua periode';
    }

    private function emptyReport(CarbonImmutable $asOf, ?CarbonImmutable $periodStart, ?CarbonImmutable $periodEnd): array
    {
        return [
            'generatedAt' => $asOf,
            'period' => [
                'start' => $periodStart,
                'end' => $periodEnd,
                'label' => $this->periodLabel($periodStart, $periodEnd),
                'is_filtered' => $periodStart !== null || $periodEnd !== null,
            ],
            'totalCustomers' => 0,
            'clusterCount' => 0,
            'silhouetteScore' => 0.0,
            'testedClusterScores' => [],
            'averages' => [
                'recency' => 0,
                'frequency' => 0,
                'monetary' => 0,
            ],
            'segments' => collect(),
            'clusterDistribution' => collect(),
            'topCustomer' => null,
            'customers' => collect(),
        ];
    }
}
