@extends('layouts.app', [
    'title' => 'Daftar Informasi (FAQs) - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Daftar Informasi (FAQs)</h1>
@endsection

@section('content')
<div class="faq-page-container">
    <!-- Header Controls: Filter Search + Category + Tombol Tambah Informasi Baru -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; width: 100%; flex-wrap: wrap;">
        <!-- Filter Bar: Search + Category Dropdown (Sesuai Mockup) -->
        <form method="GET" action="{{ route('faqs.index') }}" id="filterForm" class="faq-filter-bar" style="margin-bottom: 0; max-width: 600px; flex: 1; min-width: 280px;">
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

    <!-- Table Container (Sesuai Mockup) -->
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

                        <!-- Pertanyaan -->
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

                        <!-- Status Badge (Draft / Menunggu / Disetujui / Ditolak) -->
                        <td class="col-status" style="text-align: center;">
                            @if($item->status === 'draft')
                                <span class="status-pill status-draft">Draft</span>
                            @elseif($item->status === 'pending')
                                <span class="status-pill status-pending">Menunggu</span>
                            @elseif($item->status === 'published')
                                <span class="status-pill status-approved">Disetujui</span>
                            @elseif($item->status === 'rejected')
                                <span class="status-pill status-rejected">Ditolak</span>
                            @else
                                <span class="status-pill status-draft">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>

                        <!-- Aksi Dropdown Tiga Titik -->
                        <td class="col-aksi">
                            <div class="action-dropdown-wrapper">
                                <button type="button" class="btn-dots" onclick="toggleDropdown(event, 'menu-{{ $item->id }}')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1.5"></circle>
                                        <circle cx="12" cy="5" r="1.5"></circle>
                                        <circle cx="12" cy="19" r="1.5"></circle>
                                    </svg>
                                </button>

                                <div id="menu-{{ $item->id }}" class="action-menu" style="display: none;">
                                    <button type="button" class="action-menu-item" onclick="showDetailModal({{ json_encode($item) }})">
                                        Lihat Detail
                                    </button>
                                    <a href="{{ route('faqs.edit', $item->id) }}" class="action-menu-item" style="text-decoration: none;">
                                        Edit
                                    </a>
                                    <form action="{{ route('faqs.unpublish', $item->id) }}" method="POST" onsubmit="return confirm('Tarik informasi FAQ ini ke Draf? Informasi tidak akan lagi tampil di halaman publik sampai diajukan dan divalidasi kembali.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-menu-item" style="color: #D97706;">
                                            Tarik ke Draf
                                        </button>
                                    </form>
                                    <form action="{{ route('faqs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen informasi FAQ ini? Tindakan ini tidak dapat dibatalkan.')">
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
                            Tidak ada informasi ditemukan untuk kata kunci atau kategori yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Khusus (Sesuai Mockup: < 1 2 3 >) -->
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
<div id="detailModal" class="modal-backdrop" style="display: none;" onclick="handleModalBackdrop(event, 'detailModal')">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="detailPertanyaan">Detail Informasi</h2>
                <div id="detailCategories" class="category-pills-container" style="margin-top: 8px;"></div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="font-weight: 600; color: #1E293B; margin-bottom: 6px;">Jawaban / Penjelasan:</div>
            <div id="detailJawaban" style="color: #475569; line-height: 1.6; font-size: 14px;"></div>
            <div style="margin-top: 20px; font-size: 12px; color: #94A3B8; border-top: 1px solid #F1F5F9; padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>Status: <span id="detailStatus" style="font-weight: 600; text-transform: uppercase;"></span></div>
                <div id="detailEditBtnContainer"></div>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: flex-end;">
            <button type="button" class="btn-modal-cancel" onclick="closeModal('detailModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

    function showDetailModal(faq) {
        document.getElementById('detailPertanyaan').textContent = faq.pertanyaan;
        document.getElementById('detailJawaban').innerHTML = faq.jawaban;
        document.getElementById('detailStatus').textContent = faq.status === 'published' ? 'Disetujui / Publik' : faq.status;

        const catContainer = document.getElementById('detailCategories');
        catContainer.innerHTML = '';
        if (faq.categories && faq.categories.length > 0) {
            faq.categories.forEach(c => {
                const badge = document.createElement('span');
                badge.className = 'category-pill';
                badge.textContent = c.nama;
                catContainer.appendChild(badge);
            });
        }

        const editContainer = document.getElementById('detailEditBtnContainer');
        editContainer.innerHTML = `
            <a href="/faqs/${faq.id}/edit" class="btn-modal-submit" style="font-size: 12px; padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>Edit Informasi</span>
            </a>
        `;

        document.getElementById('detailModal').style.display = 'flex';
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
            closeModal('detailModal');
        }
    });
</script>
@endpush
