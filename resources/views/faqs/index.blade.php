@extends('layouts.app', [
    'title' => 'Daftar Informasi - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Daftar Informasi</h1>
@endsection

@section('content')
<div class="faq-page-container">
    <!-- Filter Bar: Search + Category Dropdown (Sesuai Mockup) -->
    <form method="GET" action="{{ route('faqs.index') }}" id="filterForm" class="faq-filter-bar">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

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

    <!-- Table Container (Sesuai Mockup) -->
    <div class="faq-table-container">
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Status</th>
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
                            <span class="faq-title-text">{{ Str::limit($item->pertanyaan, 80) }}</span>
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
                        <td class="col-status">
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
                                    <button type="button" class="action-menu-item" onclick="openEditFaqModal({{ json_encode($item) }})">
                                        Edit
                                    </button>
                                    <form action="{{ route('faqs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi FAQ ini?')">
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
            <div style="margin-top: 20px; font-size: 12px; color: #94A3B8; border-top: 1px solid #F1F5F9; padding-top: 12px;">
                Status: <span id="detailStatus" style="font-weight: 600; text-transform: uppercase;"></span>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: flex-end;">
            <button type="button" class="btn-modal-cancel" onclick="closeModal('detailModal')">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Edit FAQ (Dengan Dukungan Multi-Kategori) -->
<div id="editModal" class="modal-backdrop" style="display: none;" onclick="handleModalBackdrop(event, 'editModal')">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title">Edit Informasi FAQ</h2>
                <p class="modal-subtitle">Perbarui pertanyaan, jawaban, dan pilih satu atau lebih kategori yang sesuai.</p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="editPertanyaan">Pertanyaan / Judul Informasi <span class="required">*</span></label>
                    <input type="text" id="editPertanyaan" name="pertanyaan" required class="form-control">
                </div>

                <!-- Multi-Category Selector -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">
                        Kategori Informasi (Bisa pilih lebih dari satu) <span class="required">*</span>
                    </label>
                    <div class="multi-category-selector" id="editCategoriesContainer">
                        @foreach($categories as $cat)
                            <label class="category-toggle-chip">
                                <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" class="chip-checkbox edit-cat-checkbox" id="edit_cat_{{ $cat->id }}">
                                <span class="chip-content">
                                    <svg class="chip-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    {{ $cat->nama }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="editStatus">Status Informasi</label>
                    <select name="status" id="editStatus" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="pending">Menunggu Validasi</option>
                        <option value="published">Disetujui / Publik</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="editJawaban">Jawaban Lengkap <span class="required">*</span></label>
                    <textarea id="editJawaban" name="jawaban" rows="5" required class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
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
        document.getElementById('detailStatus').textContent = faq.status;

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

        document.getElementById('detailModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function openEditFaqModal(faq) {
        const form = document.getElementById('editForm');
        form.action = `/faqs/${faq.id}`;
        document.getElementById('editPertanyaan').value = faq.pertanyaan;
        document.getElementById('editJawaban').value = faq.jawaban.replace(/<[^>]*>/g, '');
        document.getElementById('editStatus').value = faq.status;

        // Reset dan centang multi-kategori yang sesuai
        document.querySelectorAll('.edit-cat-checkbox').forEach(cb => {
            cb.checked = false;
        });

        if (faq.categories && faq.categories.length > 0) {
            faq.categories.forEach(c => {
                const cb = document.getElementById(`edit_cat_${c.id}`);
                if (cb) cb.checked = true;
            });
        }

        document.getElementById('editModal').style.display = 'flex';
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
            closeModal('editModal');
        }
    });
</script>
@endpush
