@extends('layouts.app', [
    'title' => 'Dashboard Data Entry - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Dashboard Data Entry</h1>
@endsection

@section('content')
<div class="data-entry-container">
    <!-- Grid 2 Kartu Statistik -->
    <div class="data-entry-stats-grid">
        <!-- Kartu 1: Total Draft -->
        <a href="{{ route('faqs.index', ['status' => 'draft']) }}" class="de-stat-card blue" title="Klik untuk melihat seluruh draft">
            <div class="de-stat-content">
                <div class="de-stat-label">Total Draft</div>
                <div class="de-stat-value">{{ $totalDraft ?? 8 }}</div>
            </div>
            <div class="de-stat-icon blue">
                <!-- Icon Message Bubble with Inner Heart -->
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#687AC3" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <path d="M12 7.8c-1-1.2-2.7-.7-2.7 1 0 1.2 1.5 2.4 2.7 3.4 1.2-1 2.7-2.2 2.7-3.4 0-1.7-1.7-2.2-2.7-1z"></path>
                </svg>
            </div>
        </a>

        <!-- Kartu 2: Menunggu Validasi -->
        <a href="{{ route('faqs.index', ['status' => 'pending']) }}" class="de-stat-card amber" title="Klik untuk melihat informasi menunggu validasi">
            <div class="de-stat-content">
                <div class="de-stat-label">Menunggu Validasi</div>
                <div class="de-stat-value">{{ $menungguValidasi ?? 5 }}</div>
            </div>
            <div class="de-stat-icon amber">
                <!-- Icon Hourglass -->
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#DDA236" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 22h14"></path>
                    <path d="M5 2h14"></path>
                    <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                    <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                </svg>
            </div>
        </a>

        <!-- Tombol Tambah Informasi Baru (Full Width Grid) -->
        <div class="de-action-wrapper">
            <button type="button" class="btn-add-info" onclick="openFaqModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Informasi Baru</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah Informasi Baru -->
<div id="faqModal" class="modal-backdrop" style="display: none;" onclick="handleBackdropClick(event)">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title">Tambah Informasi Baru (FAQ)</h2>
                <p class="modal-subtitle">Isi data pertanyaan dan jawaban akademik untuk disimpan sebagai draft atau diajukan ke validator.</p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeFaqModal()">&times;</button>
        </div>

        <form action="{{ route('faqs.store') }}" method="POST" id="faqForm">
            @csrf
            <input type="hidden" name="action_type" id="actionTypeInput" value="draft">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="pertanyaan">
                        Pertanyaan / Judul Informasi <span class="required">*</span>
                    </label>
                    <input type="text" id="pertanyaan" name="pertanyaan" required
                           class="form-control"
                           placeholder="Misal: Bagaimana prosedur pengajuan cuti kuliah?">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label">
                        Kategori Informasi (Dapat memilih lebih dari satu) <span class="required">*</span>
                    </label>
                    <div class="multi-category-selector">
                        @foreach($categories as $category)
                            <label class="category-toggle-chip">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="chip-checkbox">
                                <span class="chip-content">
                                    <svg class="chip-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    {{ $category->nama }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jawaban">
                        Jawaban / Penjelasan Informasi <span class="required">*</span>
                    </label>
                    <textarea id="jawaban" name="jawaban" rows="6" required
                              class="form-control"
                              placeholder="Tuliskan jawaban atau panduan lengkap mengenai informasi ini..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeFaqModal()">Batal</button>
                <div class="modal-footer-actions">
                    <button type="button" class="btn-modal-draft" onclick="submitFaqForm('draft')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Draft
                    </button>
                    <button type="button" class="btn-modal-submit" onclick="submitFaqForm('pending')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Kirim untuk Validasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openFaqModal() {
        const modal = document.getElementById('faqModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            document.getElementById('pertanyaan').focus();
        }, 100);
    }

    function closeFaqModal() {
        const modal = document.getElementById('faqModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function handleBackdropClick(event) {
        if (event.target.id === 'faqModal') {
            closeFaqModal();
        }
    }

    function submitFaqForm(type) {
        const form = document.getElementById('faqForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const checkedCats = form.querySelectorAll('input[name="category_ids[]"]:checked');
        if (checkedCats.length === 0) {
            alert('Silakan pilih minimal satu kategori informasi.');
            return;
        }

        document.getElementById('actionTypeInput').value = type;
        form.submit();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeFaqModal();
        }
    });
</script>
@endpush
