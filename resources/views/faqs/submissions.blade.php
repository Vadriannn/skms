@extends('layouts.app', [
    'title' => 'Pengajuan & draf informasi - SKMS'
])

@section('content')
<div class="faq-page-container">

    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Pengajuan &amp; draf informasi</h1>
            <p class="skmk-page-subtitle">Pantau seluruh status pengajuan, perbaiki revisi, dan kelola draf informasi.</p>
        </div>
        @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
        <div>
            <a href="{{ route('faqs.create') }}" class="btn-tambah-faq">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah informasi baru</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Tab Filter Status (Semua, Disetujui, Perlu Revisi, Menunggu Validasi, Draft, Nonaktif) -->
    <div class="submission-tabs">
        <a href="{{ route('faqs.submissions', array_merge(request()->except('status', 'page'), [])) }}" 
           class="sub-tab-btn {{ empty($status) ? 'active' : '' }}">
            <span>Semua pengajuan</span>
            <span class="sub-tab-badge">{{ $countAll }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'published'])) }}" 
           class="sub-tab-btn {{ $status === 'published' ? 'active' : '' }}">
            <span>Disetujui</span>
            <span class="sub-tab-badge">{{ $countPublished }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
           class="sub-tab-btn {{ $status === 'rejected' ? 'active' : '' }}"
           style="{{ $status === 'rejected' ? 'background-color: #C93535; border-color: #C93535;' : '' }}">
            <span>Perlu revisi</span>
            <span class="sub-tab-badge" style="{{ $countRejected > 0 && $status !== 'rejected' ? 'background-color: #FDF2F2; color: #C93535;' : '' }}">{{ $countRejected }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
           class="sub-tab-btn {{ $status === 'pending' ? 'active' : '' }}">
            <span>Menunggu validasi</span>
            <span class="sub-tab-badge">{{ $countPending }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'draft'])) }}" 
           class="sub-tab-btn {{ $status === 'draft' ? 'active' : '' }}">
            <span>Draft</span>
            <span class="sub-tab-badge">{{ $countDraft }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'inactive'])) }}" 
           class="sub-tab-btn {{ $status === 'inactive' ? 'active' : '' }}">
            <span>Nonaktif</span>
            <span class="sub-tab-badge">{{ $countInactive ?? 0 }}</span>
        </a>
    </div>

    <!-- Header Controls: Filter Search + Category + Tombol Tambah Informasi Baru -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; width: 100%; flex-wrap: wrap;">
        <!-- Filter Bar: Search + Category Dropdown -->
        <form method="GET" action="{{ route('faqs.submissions') }}" id="filterForm" class="faq-filter-bar" style="margin-bottom: 0; max-width: 600px; flex: 1; min-width: 280px;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="hidden" name="per_page" id="perPageInput" value="{{ $perPage ?? 10 }}">

            <!-- 1. Search Box -->
            <div class="faq-search-wrapper">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="search" 
                       id="searchInput"
                       value="{{ request('search') }}" 
                       placeholder="Cari pertanyaan..." 
                       class="faq-search-field"
                       autocomplete="off">
            </div>

            <!-- 2. Dropdown Kategori -->
            <div class="faq-select-wrapper">
                <select name="category_id" id="categorySelect" onchange="document.getElementById('filterForm').submit()" class="faq-category-dropdown">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
                <svg class="chevron-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
        </form>

        @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
        <a href="{{ route('faqs.create') }}" class="btn-tambah-faq" title="Tambah Informasi FAQ Baru">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Informasi Baru</span>
        </a>
        @endif
    </div>

    <!-- Table Controls: Tampilkan [10, 25, 50, 100] data & Info Total -->
    <div class="faq-table-controls">
        <div class="faq-per-page-wrapper">
            <span>Tampilkan</span>
            <select name="per_page_select" id="perPageSelect" class="faq-per-page-select" onchange="document.getElementById('perPageInput').value = this.value; document.getElementById('filterForm').submit();">
                <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span>data</span>
        </div>
        <div class="faq-total-info">
            @if($faqs->total() > 0)
                Menampilkan <strong>{{ $faqs->firstItem() }}</strong> - <strong>{{ $faqs->lastItem() }}</strong> dari <strong>{{ $faqs->total() }}</strong> informasi
            @else
                Menampilkan <strong>0</strong> informasi
            @endif
        </div>
    </div>

    <!-- Table Container -->
    <div class="faq-table-container">
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th style="width: 280px;">Kategori</th>
                    <th style="width: 140px; text-align: center;">Status</th>
                    <th style="text-align: right; width: 60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $index => $item)
                    <tr>
                        <!-- No -->
                        <td class="col-no">{{ $faqs->firstItem() + $index }}</td>

                        <!-- Pertanyaan Bersih & Rapi -->
                        <td class="col-pertanyaan">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <a href="{{ route('faqs.show', $item->id) }}" class="faq-title-text" style="text-decoration: none; {{ $item->status === 'inactive' ? 'color: #94A3B8; text-decoration: line-through;' : '' }}">
                                    {{ Str::limit($item->pertanyaan, 120) }}
                                </a>
                                @if($item->latestDuplicateLog && !in_array($item->status, ['published', 'inactive']))
                                    @php
                                        $dupScoreVal = (float) $item->latestDuplicateLog->score;
                                        $dupPct = $dupScoreVal <= 1 ? round($dupScoreVal * 100) : round($dupScoreVal);
                                        $simTitle = $item->latestDuplicateLog->similarFaq?->pertanyaan ?? 'FAQ lain';
                                    @endphp
                                    <span style="background-color: #FEF2F2; color: #DC2626; border: 1px solid #FCA5A5; font-size: 11px; padding: 2px 8px; border-radius: 9999px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;" title="Peringatan duplikasi TF-IDF diabaikan ({{ $dupPct }}% mirip dengan: {{ $simTitle }})">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                        Indikasi Duplikat ({{ $dupPct }}%)
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 11.5px; color: #94A3B8; margin-top: 4px;">
                                Dibuat oleh: <strong>{{ $item->user?->name ?? '-' }}</strong> ({{ $item->created_at->format('d M Y H:i') }})
                                @if($item->status === 'inactive' && $item->deactivated_at)
                                    &bull; <span style="color: #DC2626;">Dinonaktifkan oleh <strong>{{ $item->deactivatedByUser?->name ?? '-' }}</strong> pada {{ $item->deactivated_at->format('d M Y H:i') }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Kategori (Multi-Category Badges) -->
                        <td class="col-kategori">
                            <div class="category-pills-container">
                                @forelse($item->categories as $cat)
                                    <span class="category-pill">{{ $cat->nama }}</span>
                                @empty
                                    <span class="category-pill-empty">-</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Status Badge (Draft / Menunggu / Ditolak / Disetujui / Nonaktif) -->
                        <td class="col-status" style="text-align: center;">
                            @if($item->status === 'draft')
                                <span class="status-pill status-draft">Draft</span>
                            @elseif($item->status === 'pending')
                                <span class="status-pill status-pending">Menunggu</span>
                            @elseif($item->status === 'published')
                                <span class="status-pill status-approved">Disetujui</span>
                            @elseif($item->status === 'rejected')
                                <span class="status-pill status-rejected">Perlu Revisi</span>
                            @elseif($item->status === 'inactive')
                                <span class="status-pill" style="background-color: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">Nonaktif</span>
                            @else
                                <span class="status-pill status-draft">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>

                        <!-- Aksi Dropdown Tiga Titik -->
                        <td class="col-aksi">
                            <div class="action-dropdown-wrapper">
                                <button type="button" class="btn-dots" onclick="toggleDropdown(event, 'sub-menu-{{ $item->id }}')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1.5"></circle>
                                        <circle cx="12" cy="5" r="1.5"></circle>
                                        <circle cx="12" cy="19" r="1.5"></circle>
                                    </svg>
                                </button>

                                <div id="sub-menu-{{ $item->id }}" class="action-menu" style="display: none;">
                                    <a href="{{ route('faqs.show', $item->id) }}" class="action-menu-item" style="text-decoration: none;">
                                        Lihat Detail
                                    </a>
                                    @if($item->status === 'published')
                                        <form action="{{ route('faqs.unpublish', $item->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan FAQ ini dan langsung buat pengajuan FAQ baru sebagai penggantinya?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="create_replacement" value="1">
                                            <button type="submit" class="action-menu-item" style="color: #4F46E5; font-weight: 600;">
                                                Nonaktifkan &amp; Buat Baru
                                            </button>
                                        </form>
                                        <form action="{{ route('faqs.unpublish', $item->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan informasi FAQ ini dari publik?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-menu-item" style="color: #D97706;">
                                                Nonaktifkan FAQ
                                            </button>
                                        </form>
                                    @elseif($item->status === 'inactive')
                                        <a href="{{ route('faqs.create', ['from_faq' => $item->id]) }}" class="action-menu-item" style="text-decoration: none; color: #4F46E5; font-weight: 600;">
                                            Buat FAQ Baru dari Ini
                                        </a>
                                    @else
                                        <a href="{{ route('faqs.edit', $item->id) }}" class="action-menu-item" style="text-decoration: none;">
                                            {{ $item->status === 'rejected' ? 'Revisi / Edit' : 'Edit' }}
                                        </a>
                                        <form action="{{ route('faqs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draf pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-menu-item danger">
                                                Hapus Draf
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94A3B8;">
                            Tidak ada informasi FAQ dalam status ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Khusus -->
    @if($faqs->hasPages())
        <div class="faq-pagination-bar">
            {{-- Tombol Previous (<) --}}
            @if($faqs->onFirstPage())
                <span class="page-nav prev-btn disabled">&lsaquo;</span>
            @else
                <a href="{{ $faqs->previousPageUrl() }}" class="page-nav prev-btn">&lsaquo;</a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach(range(1, $faqs->lastPage()) as $page)
                @if($page == $faqs->currentPage())
                    <span class="page-number active">{{ $page }}</span>
                @else
                    <a href="{{ $faqs->url($page) }}" class="page-number">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Next (>) --}}
            @if($faqs->hasMorePages())
                <a href="{{ $faqs->nextPageUrl() }}" class="page-nav next-btn">&rsaquo;</a>
            @else
                <span class="page-nav next-btn disabled">&rsaquo;</span>
            @endif
        </div>
    @endif
</div>

<!-- Modal Detail FAQ -->
<div id="subDetailModal" class="modal-backdrop" style="display: none;" onclick="handleModalBackdrop(event, 'subDetailModal')">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="subDetailPertanyaan">Detail Informasi</h2>
                <div id="subDetailCategories" class="category-pills-container" style="margin-top: 8px;"></div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('subDetailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Box Peringatan Duplikasi TF-IDF (Jika terdeteksi >= 60% saat diajukan) -->
            <div id="subDetailDuplicateBox" style="display: none; background-color: #FEF2F2; border: 1px solid #FCA5A5; border-left: 5px solid #EF4444; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                <div style="font-size: 13px; font-weight: 700; color: #991B1B; display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span id="subDetailDuplicateTitle">Peringatan Indikasi Duplikasi TF-IDF</span>
                </div>
                <div id="subDetailDuplicateText" style="font-size: 13px; color: #B91C1C; line-height: 1.5;"></div>
            </div>

            <!-- Box Informasi Persetujuan Validator (Hanya muncul jika status published) -->
            <div id="subDetailApprovalBox" style="display: none; background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                <div style="font-size: 13px; font-weight: 700; color: #065F46; display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Informasi Persetujuan Validator</span>
                </div>
                <div id="subDetailApprovalText" style="font-size: 13px; color: #047857; line-height: 1.5;"></div>
                <div id="subDetailApprovalInfo" style="margin-top: 6px; font-size: 11.5px; color: #065F46; opacity: 0.85;"></div>
            </div>

            <!-- Box Alasan Penolakan (Hanya muncul di modal detail jika status rejected) -->
            <div id="subDetailRejectionBox" class="review-reason-box" style="display: none;">
                <div class="review-reason-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Catatan Perbaikan dari Validator:</span>
                </div>
                <div id="subDetailRejectionCatatan" class="review-reason-text"></div>
                <div id="subDetailReviewerInfo" style="margin-top: 6px; font-size: 11.5px; color: #991B1B; opacity: 0.85;"></div>
            </div>

            <div style="font-weight: 600; color: #1E293B; margin-bottom: 6px;">Jawaban / Penjelasan:</div>
            <div id="subDetailJawaban" style="color: #475569; line-height: 1.6; font-size: 14px;"></div>
            
            <!-- Riwayat Pembuatan & Penonaktifan Lengkap -->
            <div style="margin-top: 16px; padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; color: #475569; line-height: 1.6;">
                <div><strong>Dibuat oleh:</strong> <span id="subDetailAuthor">-</span> &bull; <strong>Tanggal Dibuat:</strong> <span id="subDetailCreatedAt">-</span></div>
                <div id="subDetailDeactivatedRow" style="display: none; color: #DC2626; margin-top: 4px;">
                    <strong>Dinonaktifkan oleh:</strong> <span id="subDetailDeactivatedBy">-</span> &bull; <strong>Tanggal Nonaktif:</strong> <span id="subDetailDeactivatedAt">-</span>
                </div>
            </div>

            <!-- Riwayat Review / Validasi Lengkap -->
            <div id="subDetailHistoryContainer" style="display: none; margin-top: 18px; padding-top: 14px; border-top: 1px dashed #E2E8F0;">
                <div style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 8px;">Riwayat Validasi:</div>
                <div id="subDetailHistoryList" style="display: flex; flex-direction: column; gap: 8px;"></div>
            </div>

            <div style="margin-top: 20px; font-size: 12px; color: #94A3B8; border-top: 1px solid #F1F5F9; padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>Status: <span id="subDetailStatus" style="font-weight: 600; text-transform: uppercase;"></span></div>
                <div id="subDetailActionBtn"></div>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: flex-end;">
            <button type="button" class="btn-modal-cancel" onclick="closeModal('subDetailModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentFaqData = null;

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-dropdown-wrapper')) {
            document.querySelectorAll('.action-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    function toggleDropdown(event, menuId) {
        event.stopPropagation();
        const menu = document.getElementById(menuId);
        const isShown = menu.style.display === 'block';
        
        document.querySelectorAll('.action-menu').forEach(m => m.style.display = 'none');
        menu.style.display = isShown ? 'none' : 'block';
    }

    function showDetailSubModal(faq) {
        currentFaqData = faq;
        document.getElementById('subDetailPertanyaan').textContent = faq.pertanyaan;
        document.getElementById('subDetailJawaban').innerHTML = faq.jawaban;
        
        let statusLabel = faq.status;
        if (faq.status === 'draft') statusLabel = 'Draft';
        if (faq.status === 'pending') statusLabel = 'Menunggu Validasi';
        if (faq.status === 'rejected') statusLabel = 'Perlu Revisi (Ditolak)';
        if (faq.status === 'published') statusLabel = 'Disetujui / Publik';
        if (faq.status === 'inactive') statusLabel = 'Nonaktif';
        document.getElementById('subDetailStatus').textContent = statusLabel;

        // Render Kategori
        const catContainer = document.getElementById('subDetailCategories');
        catContainer.innerHTML = '';
        if (faq.categories && faq.categories.length > 0) {
            faq.categories.forEach(c => {
                const badge = document.createElement('span');
                badge.className = 'category-pill';
                badge.textContent = c.nama;
                catContainer.appendChild(badge);
            });
        }

        const approvalBox = document.getElementById('subDetailApprovalBox');
        const rejectionBox = document.getElementById('subDetailRejectionBox');
        const duplicateBox = document.getElementById('subDetailDuplicateBox');
        const actionBtnContainer = document.getElementById('subDetailActionBtn');
        const historyContainer = document.getElementById('subDetailHistoryContainer');
        const historyList = document.getElementById('subDetailHistoryList');
        
        actionBtnContainer.innerHTML = '';
        historyList.innerHTML = '';

        // Tampilkan Peringatan Duplikasi TF-IDF jika ada di duplicate_logs
        if (faq.latest_duplicate_log && faq.status !== 'published') {
            duplicateBox.style.display = 'block';
            const rawScore = parseFloat(faq.latest_duplicate_log.score || 0);
            const pct = rawScore <= 1 ? Math.round(rawScore * 100) : Math.round(rawScore);
            const simQ = faq.latest_duplicate_log.similar_faq ? faq.latest_duplicate_log.similar_faq.pertanyaan : 'FAQ lain di repositori';
            const simId = faq.latest_duplicate_log.similar_faq ? `#${faq.latest_duplicate_log.similar_faq.id}` : '';
            document.getElementById('subDetailDuplicateTitle').textContent = `Peringatan Duplikasi TF-IDF Diabaikan (Kemiripan: ${pct}%)`;
            document.getElementById('subDetailDuplicateText').innerHTML = `Pertanyaan ini terdeteksi mirip dengan <strong>FAQ ${simId}: "${simQ}"</strong> saat diajukan oleh Data Entry.`;
        } else {
            duplicateBox.style.display = 'none';
        }

        // Format tanggal helper
        function formatDatetime(dtString) {
            if (!dtString) return '-';
            const dt = new Date(dtString);
            return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
                   dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        // Info Pembuat & Penonaktifan
        document.getElementById('subDetailAuthor').textContent = (faq.user && faq.user.name) ? faq.user.name : '-';
        document.getElementById('subDetailCreatedAt').textContent = formatDatetime(faq.created_at);
        const deactRow = document.getElementById('subDetailDeactivatedRow');
        if (faq.status === 'inactive' && faq.deactivated_at) {
            deactRow.style.display = 'block';
            document.getElementById('subDetailDeactivatedBy').textContent = (faq.deactivated_by_user && faq.deactivated_by_user.name) ? faq.deactivated_by_user.name : '-';
            document.getElementById('subDetailDeactivatedAt').textContent = formatDatetime(faq.deactivated_at);
        } else {
            deactRow.style.display = 'none';
        }

        // Tampilkan Box Approval jika Disetujui (Published)
        if (faq.status === 'published') {
            approvalBox.style.display = 'block';
            const approvedReview = (faq.reviews || []).slice().reverse().find(r => r.keputusan === 'approved') || faq.latest_review;
            const reviewerName = (approvedReview && approvedReview.user) ? approvedReview.user.name : 'Validator';
            const reviewDate = approvedReview ? formatDatetime(approvedReview.created_at) : formatDatetime(faq.updated_at);
            const reviewNote = (approvedReview && approvedReview.catatan) ? approvedReview.catatan : 'FAQ telah diverifikasi dan disetujui untuk dipublikasikan.';

            document.getElementById('subDetailApprovalText').textContent = reviewNote;
            document.getElementById('subDetailApprovalInfo').textContent = `Disetujui oleh: ${reviewerName} \u2022 Pada ${reviewDate}`;
        } else {
            approvalBox.style.display = 'none';
        }

        // Tampilkan Catatan Validator jika Ditolak
        if (faq.status === 'rejected' && faq.latest_review && faq.latest_review.catatan) {
            rejectionBox.style.display = 'block';
            document.getElementById('subDetailRejectionCatatan').textContent = faq.latest_review.catatan;
            
            const reviewerName = faq.latest_review.user ? faq.latest_review.user.name : 'Validator';
            const rejectDate = formatDatetime(faq.latest_review.created_at);
            document.getElementById('subDetailReviewerInfo').textContent = `Diverifikasi oleh: ${reviewerName} \u2022 Pada ${rejectDate}`;
        } else {
            rejectionBox.style.display = 'none';
        }

        // Render Riwayat Review Lengkap (Timeline)
        if (faq.reviews && faq.reviews.length > 0) {
            historyContainer.style.display = 'block';
            faq.reviews.forEach(rev => {
                const isApproved = rev.keputusan === 'approved';
                const revItem = document.createElement('div');
                revItem.style.padding = '8px 12px';
                revItem.style.borderRadius = '6px';
                revItem.style.fontSize = '12px';
                revItem.style.backgroundColor = isApproved ? '#F0FDF4' : '#FEF2F2';
                revItem.style.border = `1px solid ${isApproved ? '#BBF7D0' : '#FECACA'}`;

                const revHeader = document.createElement('div');
                revHeader.style.display = 'flex';
                revHeader.style.justifyContent = 'space-between';
                revHeader.style.alignItems = 'center';
                revHeader.style.marginBottom = '2px';

                const revBadge = document.createElement('span');
                revBadge.style.fontWeight = '700';
                revBadge.style.color = isApproved ? '#166534' : '#991B1B';
                revBadge.textContent = isApproved ? '\u2713 Disetujui' : '\u2715 Ditolak';

                const revTime = document.createElement('span');
                revTime.style.color = '#64748B';
                revTime.style.fontSize = '11px';
                revTime.textContent = formatDatetime(rev.created_at);

                revHeader.appendChild(revBadge);
                revHeader.appendChild(revTime);
                revItem.appendChild(revHeader);

                const revBody = document.createElement('div');
                revBody.style.color = '#334155';
                const reviewer = rev.user ? rev.user.name : 'Validator';
                revBody.innerHTML = `<strong>Validator:</strong> ${reviewer} ${rev.catatan ? `&bull; <em>"${rev.catatan}"</em>` : ''}`;
                revItem.appendChild(revBody);

                historyList.appendChild(revItem);
            });
        } else {
            historyContainer.style.display = 'none';
        }

        // Tombol aksi di bawah modal detail (FAQ published/inactive tidak boleh diedit)
        if (faq.status === 'published' || faq.status === 'inactive') {
            const btnNew = document.createElement('a');
            btnNew.href = `/faqs/create?from_faq=${faq.id}`;
            btnNew.className = 'btn-modal-submit';
            btnNew.style.fontSize = '12px';
            btnNew.style.padding = '6px 14px';
            btnNew.style.textDecoration = 'none';
            btnNew.textContent = 'Buat FAQ Baru dari Ini';
            actionBtnContainer.appendChild(btnNew);
        } else {
            const btnEdit = document.createElement('a');
            btnEdit.href = `/faqs/${faq.id}/edit`;
            btnEdit.className = 'btn-modal-submit';
            btnEdit.style.fontSize = '12px';
            btnEdit.style.padding = '6px 14px';
            btnEdit.style.textDecoration = 'none';
            
            if (faq.status === 'rejected') {
                btnEdit.style.backgroundColor = '#2563EB';
                btnEdit.textContent = 'Buka Halaman Revisi';
            } else {
                btnEdit.textContent = 'Edit Informasi';
            }
            actionBtnContainer.appendChild(btnEdit);
        }

        document.getElementById('subDetailModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = '';
    }

    function handleModalBackdrop(event, modalId) {
        if (event.target.id === modalId) {
            closeModal(modalId);
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('subDetailModal');
        }
    });
</script>
@endpush
