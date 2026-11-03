@extends('layouts.app', [
    'title' => 'Pengajuan & Draf Informasi - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Pengajuan & Draf Informasi</h1>
@endsection

@section('content')
<div class="faq-page-container">

    <!-- Tab Filter Status (Semua, Disetujui, Perlu Revisi, Menunggu Validasi, Draft) -->
    <div class="submission-tabs">
        <a href="{{ route('faqs.submissions', array_merge(request()->except('status', 'page'), [])) }}" 
           class="sub-tab-btn {{ empty($status) ? 'active' : '' }}">
            <span>Semua Pengajuan</span>
            <span class="sub-tab-badge">{{ $countAll }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'published'])) }}" 
           class="sub-tab-btn {{ $status === 'published' ? 'active' : '' }}"
           style="{{ $status === 'published' ? 'background-color: #10B981; border-color: #10B981;' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>Disetujui</span>
            <span class="sub-tab-badge" style="{{ $countPublished > 0 && $status !== 'published' ? 'background-color: #D1FAE5; color: #047857;' : '' }}">{{ $countPublished }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
           class="sub-tab-btn {{ $status === 'rejected' ? 'active' : '' }}"
           style="{{ $status === 'rejected' ? 'background-color: #DC2626; border-color: #DC2626;' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>Perlu Revisi (Ditolak)</span>
            <span class="sub-tab-badge" style="{{ $countRejected > 0 && $status !== 'rejected' ? 'background-color: #FEE2E2; color: #DC2626;' : '' }}">{{ $countRejected }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
           class="sub-tab-btn {{ $status === 'pending' ? 'active' : '' }}"
           style="{{ $status === 'pending' ? 'background-color: #D97706; border-color: #D97706;' : '' }}">
            <span>Menunggu Validasi</span>
            <span class="sub-tab-badge">{{ $countPending }}</span>
        </a>
        <a href="{{ route('faqs.submissions', array_merge(request()->except('page'), ['status' => 'draft'])) }}" 
           class="sub-tab-btn {{ $status === 'draft' ? 'active' : '' }}">
            <span>Draft</span>
            <span class="sub-tab-badge">{{ $countDraft }}</span>
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
                            <span class="faq-title-text">{{ Str::limit($item->pertanyaan, 120) }}</span>
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

                        <!-- Status Badge (Draft / Menunggu / Ditolak / Disetujui) -->
                        <td class="col-status" style="text-align: center;">
                            @if($item->status === 'draft')
                                <span class="status-pill status-draft">Draft</span>
                            @elseif($item->status === 'pending')
                                <span class="status-pill status-pending">Menunggu</span>
                            @elseif($item->status === 'published')
                                <span class="status-pill status-approved">Disetujui</span>
                            @elseif($item->status === 'rejected')
                                <span class="status-pill status-rejected">Perlu Revisi</span>
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
                                    <button type="button" class="action-menu-item" onclick="showDetailSubModal({{ json_encode($item) }})">
                                        Lihat Detail
                                    </button>
                                    <a href="{{ route('faqs.edit', $item->id) }}" class="action-menu-item" style="text-decoration: none;">
                                        {{ $item->status === 'rejected' ? 'Revisi / Edit' : 'Edit' }}
                                    </a>
                                    @if($item->status === 'published')
                                    <form action="{{ route('faqs.unpublish', $item->id) }}" method="POST" onsubmit="return confirm('Tarik informasi FAQ ini ke Draf? Informasi tidak akan lagi tampil di halaman publik sampai diajukan dan divalidasi kembali.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-menu-item" style="color: #D97706;">
                                            Tarik ke Draf
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('faqs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen informasi ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-menu-item danger">
                                            Hapus
                                        </button>
                                    </form>
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
        const actionBtnContainer = document.getElementById('subDetailActionBtn');
        const historyContainer = document.getElementById('subDetailHistoryContainer');
        const historyList = document.getElementById('subDetailHistoryList');
        
        actionBtnContainer.innerHTML = '';
        historyList.innerHTML = '';

        // Format tanggal helper
        function formatDatetime(dtString) {
            if (!dtString) return '-';
            const dt = new Date(dtString);
            return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
                   dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
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

        // Tombol aksi di bawah modal detail
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
