<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\KnowledgeGapService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class KnowledgeGapController extends Controller
{
    /**
     * Tampilan utama Dashboard & Tabel Analisis Knowledge Gap.
     * Hanya dapat diakses oleh Super Admin.
     */
    public function index(Request $request, KnowledgeGapService $gapService)
    {
        $filters = [
            'start_date' => $request->query('start_date'),
            'end_date'   => $request->query('end_date'),
            'status'     => $request->query('status', 'all'),
            'priority'   => $request->query('priority', 'all'),
            'search'     => $request->query('search', ''),
            'sort'       => $request->query('sort', 'count_desc'),
        ];

        // Jalankan analisis
        $analysis = $gapService->analyze($filters);

        // Paginate hasil klaster kata kunci (10 data per halaman)
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $allClusters = collect($analysis['clusters']);
        $currentItems = $allClusters->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedClusters = new LengthAwarePaginator(
            $currentItems,
            $allClusters->count(),
            $perPage,
            $currentPage,
            [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        return view('analytics.knowledge_gap', [
            'metrics'           => $analysis['metrics'],
            'chartData'         => $analysis['chart_data'],
            'clusters'          => $paginatedClusters,
            'filters'           => $analysis['filters'],
            'totalClusters'     => $analysis['total_clusters'],
            'filteredCount'     => $analysis['filtered_count'],
            'perPage'           => $perPage,
        ]);
    }

    /**
     * Ekspor data Knowledge Gap ke file CSV untuk pelaporan admin.
     */
    public function export(Request $request, KnowledgeGapService $gapService)
    {
        $filters = [
            'start_date' => $request->query('start_date'),
            'end_date'   => $request->query('end_date'),
            'status'     => $request->query('status', 'all'),
            'priority'   => $request->query('priority', 'all'),
            'search'     => $request->query('search', ''),
            'sort'       => $request->query('sort', 'count_desc'),
        ];

        $analysis = $gapService->analyze($filters);
        $clusters = $analysis['clusters'];

        $filename = 'knowledge-gap-analytics-' . date('Y-m-d_His') . '.csv';

        ActivityLog::log(
            Auth::user()->name . ' mengekspor data laporan Knowledge Gap ke CSV',
            'general'
        );

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($clusters) {
            $output = fopen('php://output', 'w');
            // Menulis BOM untuk kompatibilitas Microsoft Excel UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom CSV
            fputcsv($output, [
                'No',
                'Kata Kunci Pencarian',
                'Variasi Pencarian Mahasiswa',
                'Jumlah Pencarian',
                'Tanggal Terakhir Dicari',
                'Status Keterjawaban',
                'Skor Kemiripan Relevansi',
                'Tingkat Prioritas',
                'FAQ Terkait / Terdekat',
            ]);

            $no = 1;
            foreach ($clusters as $c) {
                $variants = implode(', ', array_map(
                    fn($k, $v) => "{$k} ({$v}x)",
                    array_keys($c['keyword_variants']),
                    $c['keyword_variants']
                ));

                fputcsv($output, [
                    $no++,
                    $c['display_keyword'],
                    $variants,
                    $c['total_searches'],
                    $c['last_searched_at'] ? $c['last_searched_at']->format('Y-m-d H:i:s') : '-',
                    $c['status_label'],
                    $c['similarity_percentage'],
                    $c['priority_label'],
                    $c['matched_faq'] ? $c['matched_faq']->pertanyaan : '-',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
