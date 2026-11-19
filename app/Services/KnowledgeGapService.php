<?php

namespace App\Services;

use App\Models\Faq;
use App\Models\Search;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Service untuk Menganalisis Knowledge Gap pada SKMS:
 * - Mendeteksi kata kunci pencarian mahasiswa yang belum mendapatkan jawaban.
 * - Mengukur relevansi jawaban menggunakan algoritma TF-IDF & Cosine Similarity khusus FAQ 'published'.
 * - Mengelompokkan kata kunci serupa (clustering berbasis stemming & stopword Sastrawi).
 * - Menghitung metrik analitik, status keterjawaban, dan tingkat prioritas secara konsisten.
 */
class KnowledgeGapService
{
    protected TfIdfService $tfIdf;

    /**
     * Ambang batas skor Cosine Similarity agar sebuah pencarian dianggap "Terjawab" secara relevan.
     * Nilai 0.20 (20%) merupakan indikator kemiripan leksikal kueri pendek terhadap korpus FAQ.
     */
    public const RELEVANCE_THRESHOLD = 0.20;

    public function __construct(TfIdfService $tfIdf)
    {
        $this->tfIdf = $tfIdf;
    }

    /**
     * Menjalankan analisis komprehensif Knowledge Gap berdasarkan filter request.
     *
     * @param array $filters [start_date, end_date, status, priority, search, sort]
     * @return array
     */
    public function analyze(array $filters = []): array
    {
        $startDate = !empty($filters['start_date']) ? Carbon::parse($filters['start_date'])->startOfDay() : null;
        $endDate   = !empty($filters['end_date'])   ? Carbon::parse($filters['end_date'])->endOfDay()   : null;
        $statusFilter   = $filters['status'] ?? 'all';     // all, unanswered, answered
        $priorityFilter = $filters['priority'] ?? 'all';   // all, high, medium, low
        $searchKeyword  = trim((string) ($filters['search'] ?? ''));
        $sortBy         = $filters['sort'] ?? 'count_desc'; // count_desc, count_asc, date_desc, date_asc

        // 1. Ambil data pencarian sesuai filter rentang tanggal (filter data kosong sejak query)
        $query = Search::with(['faq' => function ($q) {
            $q->select('id', 'pertanyaan', 'status');
        }])
        ->whereNotNull('kata_kunci')
        ->where('kata_kunci', '!=', '');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        // Ambil data pencarian dan pastikan kata kunci kosong atau spasi murni disaring total
        $allSearches = $query->latest('created_at')->get()->filter(function ($s) {
            return trim((string) $s->kata_kunci) !== '';
        })->values();

        // 2. Kelompokkan (Clustering) kata kunci agar tidak terjadi duplikasi kueri yang bermakna sama
        $clusters = $this->clusterSearches($allSearches);

        // 3. Hitung metrik ringkasan (KPI Dashboard) sebelum filter tabel diterapkan
        $metrics = $this->calculateMetrics($clusters);

        // 4. Terapkan filter tabel (Pencarian teks, Status, Prioritas, dan Sorting)
        $filteredClusters = $this->filterAndSortClusters(
            $clusters,
            $searchKeyword,
            $statusFilter,
            $priorityFilter,
            $sortBy
        );

        // 5. Siapkan data grafik untuk kata kunci yang paling sering tidak terjawab (Top Unanswered)
        $chartData = $this->prepareChartData($clusters);

        return [
            'metrics'        => $metrics,
            'clusters'       => $filteredClusters,
            'chart_data'     => $chartData,
            'filters'        => [
                'start_date' => $startDate ? $startDate->format('Y-m-d') : '',
                'end_date'   => $endDate   ? $endDate->format('Y-m-d')   : '',
                'status'     => $statusFilter,
                'priority'   => $priorityFilter,
                'search'     => $searchKeyword,
                'sort'       => $sortBy,
            ],
            'total_clusters' => count($clusters),
            'filtered_count' => count($filteredClusters),
        ];
    }

    /**
     * Mengelompokkan kata kunci pencarian menggunakan tokenisasi dan stemmer Sastrawi.
     */
    protected function clusterSearches(Collection $searches): array
    {
        $clusters = [];

        foreach ($searches as $search) {
            $rawKeyword = trim((string) $search->kata_kunci);
            if ($rawKeyword === '') {
                continue; // Jangan masukkan kueri kosong ke dalam klaster analitik
            }

            // Dapatkan representasi token dasar yang bersih
            $clusterKey = $this->generateClusterKey($rawKeyword);

            if (!isset($clusters[$clusterKey])) {
                $clusters[$clusterKey] = [
                    'cluster_key'       => $clusterKey,
                    'display_keyword'   => $rawKeyword,
                    'keyword_variants'  => [],
                    'total_searches'    => 0,
                    'last_searched_at'  => $search->created_at,
                    'search_records'    => [],
                    'has_linked_faq'    => false,
                    'linked_faqs'       => [],
                ];
            }

            // Perbarui data klaster
            $clusters[$clusterKey]['total_searches']++;
            $clusters[$clusterKey]['keyword_variants'][$rawKeyword] = ($clusters[$clusterKey]['keyword_variants'][$rawKeyword] ?? 0) + 1;

            if ($search->created_at && ($clusters[$clusterKey]['last_searched_at'] === null || $search->created_at > $clusters[$clusterKey]['last_searched_at'])) {
                $clusters[$clusterKey]['last_searched_at'] = $search->created_at;
            }

            // Catat riwayat penautan FAQ untuk kebutuhan investigasi admin
            if ($search->faq_id && $search->faq && $search->faq->status === 'published') {
                $clusters[$clusterKey]['has_linked_faq'] = true;
                $clusters[$clusterKey]['linked_faqs'][$search->faq_id] = $search->faq->pertanyaan;
            }

            $clusters[$clusterKey]['search_records'][] = [
                'id'         => $search->id,
                'nrp'        => $search->nrp,
                'kata_kunci' => $search->kata_kunci,
                'faq_id'     => $search->faq_id,
                'created_at' => $search->created_at,
            ];
        }

        // Tentukan kata kunci representasi terpopuler & evaluasi status relevansi murni dengan TF-IDF
        foreach ($clusters as $key => &$cluster) {
            // Tentukan variasi kata kunci yang paling sering diketik mahasiswa
            arsort($cluster['keyword_variants']);
            $cluster['display_keyword'] = array_key_first($cluster['keyword_variants']);

            // Evaluasi kemiripan terhadap korpus FAQ yang HANYA berstatus 'published'
            $evaluation = $this->evaluateRelevance($cluster['display_keyword']);

            $cluster['similarity_score']      = $evaluation['score'];
            $cluster['similarity_percentage'] = $evaluation['percentage'];
            $cluster['matched_faq']           = $evaluation['matched_faq'];

            // Penentuan Status Murni berdasarkan hasil Cosine Similarity (Threshold 0.20):
            // - Terjawab: jika skor Cosine Similarity >= 0.20 dengan FAQ published
            // - Belum Terjawab: jika skor Cosine Similarity < 0.20
            // CATATAN: has_linked_faq TIDAK BOLEH meng-override status jika skor di bawah threshold
            if ($evaluation['is_relevant']) {
                $cluster['status']       = 'answered';
                $cluster['status_label'] = 'Terjawab';
            } else {
                $cluster['status']       = 'unanswered';
                $cluster['status_label'] = 'Belum Terjawab';
            }

            // Tentukan tingkat prioritas berdasarkan frekuensi pencarian
            $cluster['priority']       = $this->determinePriority($cluster['total_searches'], $cluster['status']);
            $cluster['priority_label'] = match ($cluster['priority']) {
                'high'   => 'Tinggi',
                'medium' => 'Sedang',
                default  => 'Rendah',
            };
        }
        unset($cluster);

        return array_values($clusters);
    }

    /**
     * Membuat kunci klaster unik dari kata kunci teks.
     * Menggunakan tokenisasi pembersihan dan stemming Sastrawi dari TfIdfService.
     */
    public function generateClusterKey(string $text): string
    {
        $tokens = $this->tfIdf->tokenize($text);

        if (!empty($tokens)) {
            // Urutkan token unik secara alfabetis agar urutan kata tidak mempengaruhi kelompok
            $uniqueTokens = array_values(array_unique($tokens));
            sort($uniqueTokens);
            return implode(' ', $uniqueTokens);
        }

        // Fallback jika seluruh kata berupa stopword atau simbol
        $clean = mb_strtolower(trim($text), 'UTF-8');
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', '', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);
        return trim($clean) ?: 'pencarian-lainnya';
    }

    /**
     * Mengevaluasi kemiripan kata kunci KHUSUS terhadap seluruh FAQ 'published' saat ini menggunakan Cosine Similarity.
     */
    protected function evaluateRelevance(string $keyword): array
    {
        // Jalankan pengecekan kemiripan TF-IDF khusus terhadap FAQ berstatus published
        $similarity = $this->tfIdf->checkSimilarityAgainstPublished($keyword, self::RELEVANCE_THRESHOLD);

        $highestScore = (float) ($similarity['highest_score'] ?? 0.0);
        $matchedFaq   = $similarity['similar_faq'] ?? null;

        // Validasi ekstra: pastikan FAQ yang cocok memang berstatus published
        if ($matchedFaq && $matchedFaq->status !== 'published') {
            $highestScore = 0.0;
            $matchedFaq = null;
        }

        return [
            'score'       => $highestScore,
            'percentage'  => round($highestScore * 100, 1) . '%',
            'is_relevant' => $highestScore >= self::RELEVANCE_THRESHOLD && $matchedFaq !== null,
            'matched_faq' => $matchedFaq,
        ];
    }

    /**
     * Menentukan tingkat prioritas kata kunci berdasarkan frekuensi pencarian.
     */
    protected function determinePriority(int $count, string $status): string
    {
        if ($count >= 5 || ($status === 'unanswered' && $count >= 3)) {
            return 'high';
        }

        if ($count >= 2) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Menghitung indikator performa utama (KPI) untuk Dashboard Knowledge Gap.
     * Dijamin konsisten: total_searches = unanswered_searches + answered_searches.
     */
    protected function calculateMetrics(array $clusters): array
    {
        $unansweredSearches = 0;
        $answeredSearches = 0;
        $unansweredKeywordsCount = 0;
        $answeredKeywordsCount = 0;
        $topUnanswered = null;
        $maxUnansweredCount = 0;

        foreach ($clusters as $cluster) {
            if ($cluster['status'] === 'unanswered') {
                $unansweredKeywordsCount++;
                $unansweredSearches += $cluster['total_searches'];

                if ($cluster['total_searches'] > $maxUnansweredCount) {
                    $maxUnansweredCount = $cluster['total_searches'];
                    $topUnanswered = $cluster['display_keyword'];
                }
            } else {
                $answeredKeywordsCount++;
                $answeredSearches += $cluster['total_searches'];
            }
        }

        // Konsistensi total: jumlah dari seluruh pencarian yang dianalisis
        $totalSearches = $unansweredSearches + $answeredSearches;

        $gapPercentage = $totalSearches > 0 
            ? round(($unansweredSearches / $totalSearches) * 100, 1) 
            : 0.0;

        return [
            'total_searches'            => $totalSearches,
            'unanswered_searches'       => $unansweredSearches,
            'answered_searches'         => $answeredSearches,
            'unanswered_keywords_count' => $unansweredKeywordsCount,
            'answered_keywords_count'   => $answeredKeywordsCount,
            'gap_percentage'            => $gapPercentage,
            'top_unanswered_keyword'    => $topUnanswered ?: 'Belum ada',
            'top_unanswered_count'      => $maxUnansweredCount,
        ];
    }

    /**
     * Memfilter dan mengurutkan daftar klaster kata kunci.
     */
    protected function filterAndSortClusters(
        array $clusters,
        string $searchKeyword,
        string $statusFilter,
        string $priorityFilter,
        string $sortBy
    ): array {
        $result = array_filter($clusters, function ($cluster) use ($searchKeyword, $statusFilter, $priorityFilter) {
            // Filter Pencarian Teks (Kata Kunci atau variasinya)
            if ($searchKeyword !== '') {
                $keywordMatch = Str::contains(mb_strtolower($cluster['display_keyword']), mb_strtolower($searchKeyword));
                $variantsMatch = false;
                foreach (array_keys($cluster['keyword_variants']) as $variant) {
                    if (Str::contains(mb_strtolower($variant), mb_strtolower($searchKeyword))) {
                        $variantsMatch = true;
                        break;
                    }
                }
                if (!$keywordMatch && !$variantsMatch) {
                    return false;
                }
            }

            // Filter Status (Konsisten dengan status hasil Cosine Similarity)
            if ($statusFilter === 'unanswered' && $cluster['status'] !== 'unanswered') {
                return false;
            }
            if ($statusFilter === 'answered' && $cluster['status'] !== 'answered') {
                return false;
            }

            // Filter Prioritas
            if ($priorityFilter !== 'all' && $cluster['priority'] !== $priorityFilter) {
                return false;
            }

            return true;
        });

        // Urutkan data
        usort($result, function ($a, $b) use ($sortBy) {
            return match ($sortBy) {
                'count_asc'  => $a['total_searches'] <=> $b['total_searches'],
                'date_asc'   => $a['last_searched_at'] <=> $b['last_searched_at'],
                'date_desc'  => $b['last_searched_at'] <=> $a['last_searched_at'],
                default      => $b['total_searches'] <=> $a['total_searches'], // count_desc
            };
        });

        return $result;
    }

    /**
     * Mempersiapkan dataset grafik untuk Top 7 Kata Kunci Paling Sering Tidak Terjawab.
     */
    protected function prepareChartData(array $clusters): array
    {
        // Filter hanya yang statusnya belum terjawab (Konsisten dengan tabel & metrics)
        $unanswered = array_filter($clusters, fn($c) => $c['status'] === 'unanswered');

        // Urutkan berdasarkan total_searches terbanyak
        usort($unanswered, fn($a, $b) => $b['total_searches'] <=> $a['total_searches']);

        // Ambil top 7 kata kunci
        $topList = array_slice($unanswered, 0, 7);

        $labels = [];
        $counts = [];

        foreach ($topList as $item) {
            $labels[] = Str::limit($item['display_keyword'], 25);
            $counts[] = $item['total_searches'];
        }

        return [
            'has_data' => !empty($counts),
            'labels'   => $labels,
            'counts'   => $counts,
            'top_list' => $topList,
        ];
    }
}
