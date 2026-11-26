@extends('layouts.app', [
    'title' => 'Knowledge Gap Analytics - SKMS'
])

@section('content')
<div class="knowledge-gap-page">

    <!-- Page Intro Header -->
    <div class="skmk-page-intro" style="margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span class="badge badge-emerald" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">MODUL ANALITIK PENCARIAN</span>
            </div>
            <h1 class="skmk-page-main-title">Knowledge Gap Analytics</h1>
            <p class="skmk-page-subtitle">Deteksi dan analisis kata kunci pencarian mahasiswa yang belum terjawab atau belum memiliki FAQ relevan di sistem.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('analytics.knowledge_gap.export', request()->query()) }}" class="btn-secondary" style="font-weight: 600; color: #1F1415; background: #FFFFFF; border: 1px solid var(--border-color); display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 8px; text-decoration: none;" title="Ekspor laporan ke format CSV">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Ekspor CSV</span>
            </a>
            <a href="{{ route('faqs.create') }}" class="btn-tambah-faq" style="text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah FAQ baru</span>
            </a>
        </div>
    </div>

    <!-- 1. DASHBOARD KNOWLEDGE GAP (KPI METRIC CARDS) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 26px;">
        <!-- Card 1: Total Pencarian Mahasiswa -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; min-height: 125px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <span class="badge badge-gray" style="font-size: 11px;">Semua Sesi</span>
            </div>
            <div>
                <div style="font-size: 30px; font-weight: 800; color: #1F1415; line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($metrics['total_searches']) }}
                </div>
                <div style="font-size: 13.5px; font-weight: 600; color: #1F1415;">Total Pencarian Mahasiswa</div>
            </div>
        </div>

        <!-- Card 2: Pencarian Tidak Menemukan Jawaban (Gap) -->
        <div style="background: #FFFFFF; border: 1px solid #F5C6C6; border-radius: 12px; padding: 20px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; min-height: 125px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #FDF2F2; color: #C93535; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <span class="badge" style="background: #FDF2F2; color: #C93535; font-size: 11px; font-weight: 600;">
                    {{ $metrics['gap_percentage'] }}% Gap
                </span>
            </div>
            <div>
                <div style="font-size: 30px; font-weight: 800; color: #C93535; line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($metrics['unanswered_searches']) }}
                </div>
                <div style="font-size: 13.5px; font-weight: 600; color: #1F1415;">Pencarian Tanpa FAQ Relevan</div>
            </div>
        </div>

        <!-- Card 3: Jumlah Kata Kunci Belum Terjawab (Unik) -->
        <div style="background: #FFFFFF; border: 1px solid #F5DFB0; border-radius: 12px; padding: 20px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; min-height: 125px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #FFF8EB; color: #B4690E; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="badge badge-amber" style="font-size: 11px; font-weight: 600;">Topik Perlu FAQ</span>
            </div>
            <div>
                <div style="font-size: 30px; font-weight: 800; color: #B4690E; line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($metrics['unanswered_keywords_count']) }}
                </div>
                <div style="font-size: 13.5px; font-weight: 600; color: #1F1415;">Kata Kunci Belum Memiliki FAQ</div>
            </div>
        </div>

        <!-- Card 4: Kata Kunci Paling Sering Tidak Terjawab -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; min-height: 125px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #EEF3FA; color: #345995; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <span class="badge" style="background: #EEF3FA; color: #345995; font-size: 11px; font-weight: 600;">
                    {{ $metrics['top_unanswered_count'] }}x Dicari
                </span>
            </div>
            <div>
                <div style="font-size: 20px; font-weight: 800; color: #1F1415; line-height: 1.2; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $metrics['top_unanswered_keyword'] }}">
                    &ldquo;{{ $metrics['top_unanswered_keyword'] }}&rdquo;
                </div>
                <div style="font-size: 13.5px; font-weight: 600; color: #1F1415;">Kata Kunci Terbanyak Belum Terjawab</div>
            </div>
        </div>
    </div>

    <!-- 2. GRAFIK VISUAL: TOP KATA KUNCI TIDAK TERJAWAB -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; margin-bottom: 26px; box-shadow: var(--shadow-card);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; color: #1F1415; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Grafik Kata Kunci Paling Sering Tidak Terjawab</span>
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px; margin-bottom: 0;">
                    Visualisasi kata kunci mahasiswa yang memiliki tingkat prioritas tinggi untuk segera dibuatkan FAQ resmi.
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #C93535;"></span>
                <span style="font-size: 12px; color: var(--text-muted);">Frekuensi Pencarian Tanpa Hasil</span>
            </div>
        </div>

        @if($chartData['has_data'])
            <div style="position: relative; width: 100%; min-height: 260px; max-height: 320px;">
                <canvas id="unansweredChart"></canvas>
            </div>

            <!-- Fallback Pure CSS Horizontal Bars untuk visual responsif -->
            <div class="chart-bars-list" style="margin-top: 18px; border-top: 1px solid var(--border-subtle); padding-top: 18px;">
                <div style="font-size: 12px; font-weight: 700; color: #6B5B5D; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Rincian Kata Kunci Knowledge Gap Terbanyak:
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @php
                        $maxChartVal = max($chartData['counts'] ?: [1]);
                    @endphp
                    @foreach($chartData['top_list'] as $topItem)
                        @php
                            $barWidth = max(5, min(100, round(($topItem['total_searches'] / $maxChartVal) * 100)));
                        @endphp
                        <div style="display: grid; grid-template-columns: minmax(180px, 260px) 1fr 80px; align-items: center; gap: 14px;">
                            <div style="font-size: 13px; font-weight: 600; color: #1F1415; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $topItem['display_keyword'] }}">
                                {{ $topItem['display_keyword'] }}
                            </div>
                            <div style="background: #F1F5F4; border-radius: 6px; height: 18px; width: 100%; overflow: hidden; position: relative;">
                                <div style="background: linear-gradient(90deg, #F87171 0%, #C93535 100%); height: 100%; width: {{ $barWidth }}%; border-radius: 6px; transition: width 0.6s ease;"></div>
                            </div>
                            <div style="text-align: right; font-size: 12.5px; font-weight: 700; color: #C93535;">
                                {{ $topItem['total_searches'] }} kali
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 48px 20px; background: #FDFBF7; border: 1px dashed #ECE4E5; border-radius: 10px;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#E5A032" stroke-width="1.8" style="margin-bottom: 8px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <div style="font-size: 14px; font-weight: 700; color: #1F1415; margin-bottom: 4px;">Tidak Ada Knowledge Gap Aktif</div>
                <div style="font-size: 12.5px; color: var(--text-muted); max-width: 440px; margin: 0 auto;">
                    Luar biasa! Seluruh riwayat pencarian mahasiswa pada rentang waktu ini telah memiliki FAQ yang relevan di sistem.
                </div>
            </div>
        @endif
    </div>

    <!-- 3. FILTER DAN PENCARIAN -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 20px 24px; margin-bottom: 26px; box-shadow: var(--shadow-card);">
        <form method="GET" action="{{ route('analytics.knowledge_gap') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: flex-end;">
                <!-- Pencarian Kata Kunci -->
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Pencarian Kata Kunci
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Ketik kata kunci pencarian..." class="form-control" style="width: 100%; padding-left: 36px; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="2" style="position: absolute; left: 12px; top: 11px;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Status Keterjawaban
                    </label>
                    <select name="status" class="form-control select-filter" style="width: 100%; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; background-color: #FFFFFF;">
                        <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="unanswered" {{ $filters['status'] === 'unanswered' ? 'selected' : '' }}>Belum Terjawab (Gap)</option>
                        <option value="answered" {{ $filters['status'] === 'answered' ? 'selected' : '' }}>Relevan</option>
                    </select>
                </div>

                <!-- Filter Prioritas -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Tingkat Prioritas
                    </label>
                    <select name="priority" class="form-control select-filter" style="width: 100%; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; background-color: #FFFFFF;">
                        <option value="all" {{ $filters['priority'] === 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                        <option value="high" {{ $filters['priority'] === 'high' ? 'selected' : '' }}>Tinggi (&ge;5x pencarian)</option>
                        <option value="medium" {{ $filters['priority'] === 'medium' ? 'selected' : '' }}>Sedang (2-4x pencarian)</option>
                        <option value="low" {{ $filters['priority'] === 'low' ? 'selected' : '' }}>Rendah (1x pencarian)</option>
                    </select>
                </div>

                <!-- Filter Tanggal Mulai -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Dari Tanggal
                    </label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control" style="width: 100%; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px;">
                </div>

                <!-- Filter Tanggal Selesai -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control" style="width: 100%; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px;">
                </div>

                <!-- Pengurutan -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #1F1415; margin-bottom: 6px;">
                        Urutkan
                    </label>
                    <select name="sort" class="form-control select-filter" style="width: 100%; height: 38px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; background-color: #FFFFFF;">
                        <option value="count_desc" {{ $filters['sort'] === 'count_desc' ? 'selected' : '' }}>Pencarian Terbanyak</option>
                        <option value="count_asc" {{ $filters['sort'] === 'count_asc' ? 'selected' : '' }}>Pencarian Paling Sedikit</option>
                        <option value="date_desc" {{ $filters['sort'] === 'date_desc' ? 'selected' : '' }}>Terakhir Dicari Terbaru</option>
                        <option value="date_asc" {{ $filters['sort'] === 'date_asc' ? 'selected' : '' }}>Terakhir Dicari Terlama</option>
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-primary" style="height: 38px; padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; flex: 1;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                        <span>Terapkan</span>
                    </button>
                    @if($filters['search'] || $filters['status'] !== 'all' || $filters['priority'] !== 'all' || $filters['start_date'] || $filters['end_date'] || $filters['sort'] !== 'count_desc')
                        <a href="{{ route('analytics.knowledge_gap') }}" class="btn-secondary" style="height: 38px; padding: 0 12px; border-radius: 8px; font-size: 13px; color: var(--text-muted); border: 1px solid var(--border-color); background: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Reset filter">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- 4. TABEL KNOWLEDGE GAP -->
    <div class="faq-table-container" style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-card);">
        
        <!-- Table Top Header Info -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 15px; font-weight: 700; color: #1F1415; margin: 0;">
                    Tabel Analitik Kata Kunci
                </h3>
                <span class="badge badge-indigo" style="font-size: 11.5px;">
                    {{ $filteredCount }} kelompok kata kunci ditemukan
                </span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                Kata kunci dengan makna serupa otomatis dikelompokkan oleh mesin NLP Sastrawi
            </div>
        </div>

        <div class="table-responsive">
            <table class="app-table" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Kata Kunci Pencarian &amp; Variasi Mahasiswa</th>
                        <th style="width: 140px; text-align: center;">Jumlah Pencarian</th>
                        <th style="width: 170px;">Tanggal Terakhir</th>
                        <th style="width: 230px;">Status Keterjawaban</th>
                        <th style="width: 130px; text-align: center;">Tingkat Prioritas</th>
                        <th style="width: 130px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clusters as $index => $item)
                        <tr>
                            <!-- No -->
                            <td style="text-align: center; color: var(--text-muted); font-size: 13px;">
                                {{ $clusters->firstItem() + $index }}
                            </td>

                            <!-- Kata Kunci & Variasi -->
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div style="font-weight: 700; font-size: 14.5px; color: #1F1415;">
                                        {{ $item['display_keyword'] }}
                                    </div>
                                    @if(count($item['keyword_variants']) > 1)
                                        <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                            <span style="font-weight: 600;">Variasi diketik:</span>
                                            @foreach($item['keyword_variants'] as $variant => $vCount)
                                                <span style="background: #F1F5F4; padding: 2px 7px; border-radius: 4px; border: 1px solid #ECE4E5; font-size: 11px;">
                                                    &ldquo;{{ $variant }}&rdquo; ({{ $vCount }}x)
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div style="font-size: 11px; color: #948486;">
                                        Token Dasar: <code>{{ $item['cluster_key'] }}</code>
                                    </div>
                                </div>
                            </td>

                            <!-- Jumlah Pencarian -->
                            <td style="text-align: center;">
                                <span class="badge" style="background: #EEF3FA; color: #345995; font-size: 13px; font-weight: 700; padding: 5px 12px;">
                                    {{ $item['total_searches'] }}x
                                </span>
                            </td>

                            <!-- Tanggal Terakhir -->
                            <td>
                                @if($item['last_searched_at'])
                                    <div style="font-weight: 600; font-size: 13px; color: #1F1415;">
                                        {{ $item['last_searched_at']->translatedFormat('d M Y') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">
                                        {{ $item['last_searched_at']->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                @endif
                            </td>

                            <!-- Status Keterjawaban -->
                            <td>
                                @if($item['status'] === 'unanswered')
                                    <span class="badge" style="background: #FDF2F2; color: #C93535; border: 1px solid #F5C6C6; font-size: 12px; padding: 4px 10px;">
                                        Belum Ada FAQ Relevan
                                    </span>
                                    <div style="font-size: 11.5px; color: #C93535; margin-top: 4px;">
                                        &bull; Kemiripan leksikal: {{ $item['similarity_percentage'] }} (&lt; 20%)
                                    </div>
                                @else
                                    <span class="badge badge-emerald" style="font-size: 12px; padding: 4px 10px;">
                                        Relevan
                                    </span>
                                    @if($item['matched_faq'])
                                        <div style="font-size: 11.5px; color: #8B0D18; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 240px;" title="{{ $item['matched_faq']->pertanyaan }}">
                                            &bull; FAQ Terdekat: &ldquo;{{ $item['matched_faq']->pertanyaan }}&rdquo; ({{ $item['similarity_percentage'] }})
                                        </div>
                                    @else
                                        <div style="font-size: 11.5px; color: #8B0D18; margin-top: 4px;">
                                            &bull; Kemiripan: {{ $item['similarity_percentage'] }} (&ge; 20%)
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <!-- Tingkat Prioritas -->
                            <td style="text-align: center;">
                                @if($item['priority'] === 'high')
                                    <span class="badge" style="background: #FDF2F2; color: #C93535; font-size: 11.5px; font-weight: 700; border: 1px solid #F5C6C6;">
                                        Tinggi
                                    </span>
                                @elseif($item['priority'] === 'medium')
                                    <span class="badge badge-amber" style="font-size: 11.5px; font-weight: 700;">
                                        Sedang
                                    </span>
                                @else
                                    <span class="badge badge-gray" style="font-size: 11.5px; font-weight: 600;">
                                        Rendah
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td style="text-align: center;">
                                @if($item['status'] === 'unanswered')
                                    <a href="{{ route('faqs.create', ['pertanyaan' => $item['display_keyword']]) }}" class="btn-primary" style="padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;" title="Buat FAQ baru dari kata kunci ini">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <span>Buat FAQ</span>
                                    </a>
                                @else
                                    @if($item['matched_faq'])
                                        <a href="{{ route('faqs.show', $item['matched_faq']->id) }}" class="btn-secondary" style="padding: 6px 10px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border: 1px solid var(--border-color); background: #FFFFFF; color: #1F1415;" title="Lihat FAQ terkait">
                                            <span>Lihat FAQ</span>
                                            <span>&rarr;</span>
                                        </a>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-muted);">&mdash;</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="1.6">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <div style="font-size: 14px; font-weight: 600; color: #1F1415;">Tidak Ada Data Kata Kunci</div>
                                    <div style="font-size: 12.5px; color: var(--text-muted); max-width: 400px;">
                                        Tidak ditemukan riwayat pencarian yang sesuai dengan kriteria filter yang Anda tentukan. Silakan ubah filter atau tanggal pencarian.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer: Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: var(--text-muted);">
            <div>
                @if($clusters->total() > 0)
                    Menampilkan {{ $clusters->firstItem() }}–{{ $clusters->lastItem() }} dari {{ $clusters->total() }} kelompok kata kunci
                @else
                    Menampilkan 0 kata kunci
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                @if($clusters->onFirstPage())
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #ECE4E5; color: #CBD5E1; cursor: not-allowed;">&lsaquo;</span>
                @else
                    <a href="{{ $clusters->previousPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #ECE4E5; text-decoration: none;">&lsaquo;</a>
                @endif

                @foreach(range(1, max(1, $clusters->lastPage())) as $page)
                    @if($page == $clusters->currentPage())
                        <span class="page-number active" style="min-width: 28px; height: 28px; font-size: 12.5px; background: #8B0D18; color: #FFFFFF; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">{{ $page }}</span>
                    @else
                        <a href="{{ $clusters->url($page) }}" class="page-number" style="min-width: 28px; height: 28px; font-size: 12.5px; color: #6B5B5D; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #ECE4E5; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach

                @if($clusters->hasMorePages())
                    <a href="{{ $clusters->nextPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #ECE4E5; text-decoration: none;">&rsaquo;</a>
                @else
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #ECE4E5; color: #CBD5E1; cursor: not-allowed;">&rsaquo;</span>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
@if($chartData['has_data'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('unansweredChart');
    if (!ctx) return;

    const chartLabels = {!! json_encode($chartData['labels']) !!};
    const chartCounts = {!! json_encode($chartData['counts']) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Frekuensi Dicari (Kali)',
                data: chartCounts,
                backgroundColor: 'rgba(201, 53, 53, 0.85)',
                borderColor: '#C93535',
                borderWidth: 1.5,
                borderRadius: 6,
                maxBarThickness: 38,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1F1415',
                    titleColor: '#FFFFFF',
                    bodyColor: '#E5A032',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Dicari sebanyak ' + context.parsed.y + ' kali';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B5B5D',
                        font: {
                            family: 'Inter, sans-serif',
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6B5B5D',
                        font: {
                            family: 'Inter, sans-serif',
                            size: 12
                        }
                    },
                    grid: {
                        color: '#F3ECEC'
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
