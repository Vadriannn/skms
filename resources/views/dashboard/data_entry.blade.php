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
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="pertanyaan">
                        Pertanyaan / Judul Informasi <span class="required">*</span>
                    </label>
                    <div class="similarity-input-wrapper">
                        <input type="text" id="pertanyaan" name="pertanyaan" required
                               class="form-control"
                               placeholder="Misal: Bagaimana prosedur pengajuan cuti kuliah?"
                               autocomplete="off">
                        <div id="similaritySpinner" class="similarity-spinner-icon">
                            <span class="spinner-sm"></span>
                            <span>Mengecek TF-IDF...</span>
                        </div>
                    </div>

                    <!-- Kotak Hasil Deteksi Kemiripan Otomatis TF-IDF -->
                    <div id="tfidfFeedbackBox" class="tfidf-feedback-container" style="display: none;"></div>
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
    let similarityTimer = null;
    let similarityAbortController = null;
    let isDuplicateDetected = false;
    let duplicateMaxScore = '0%';
    let duplicateSimilarTitle = '';

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

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
        
        // Reset form & state TF-IDF
        document.getElementById('faqForm').reset();
        clearSimilarityCheck();
    }

    function clearSimilarityCheck() {
        if (similarityTimer) clearTimeout(similarityTimer);
        if (similarityAbortController) similarityAbortController.abort();
        
        document.getElementById('similaritySpinner').style.display = 'none';
        const feedbackBox = document.getElementById('tfidfFeedbackBox');
        feedbackBox.style.display = 'none';
        feedbackBox.innerHTML = '';
        
        isDuplicateDetected = false;
        duplicateMaxScore = '0%';
        duplicateSimilarTitle = '';
    }

    function handleBackdropClick(event) {
        if (event.target.id === 'faqModal') {
            closeFaqModal();
        }
    }

    // Listener Real-time Komparasi TF-IDF saat user mengetik pertanyaan
    const inputPertanyaan = document.getElementById('pertanyaan');
    if (inputPertanyaan) {
        inputPertanyaan.addEventListener('input', function() {
            const query = this.value.trim();

            if (similarityTimer) clearTimeout(similarityTimer);

            if (query.length < 3) {
                clearSimilarityCheck();
                return;
            }

            document.getElementById('similaritySpinner').style.display = 'inline-flex';

            similarityTimer = setTimeout(() => {
                runTfidfComparison(query);
            }, 450);
        });
    }

    function runTfidfComparison(queryText) {
        if (similarityAbortController) {
            similarityAbortController.abort();
        }
        similarityAbortController = new AbortController();

        fetch('{{ route('faqs.check_similarity') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pertanyaan: queryText }),
            signal: similarityAbortController.signal
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('similaritySpinner').style.display = 'none';

            if (!data.has_checked) {
                document.getElementById('tfidfFeedbackBox').style.display = 'none';
                isDuplicateDetected = false;
                return;
            }

            renderTfidfResults(data);
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                document.getElementById('similaritySpinner').style.display = 'none';
            }
        });
    }

    function renderTfidfResults(data) {
        const feedbackBox = document.getElementById('tfidfFeedbackBox');
        feedbackBox.style.display = 'block';

        if (data.is_duplicate) {
            isDuplicateDetected = true;
            duplicateMaxScore = data.percentage;
            duplicateSimilarTitle = data.similar_faq ? data.similar_faq.pertanyaan : '';

            let categoriesHtml = '';
            if (data.similar_faq && data.similar_faq.categories && data.similar_faq.categories.length > 0) {
                categoriesHtml = data.similar_faq.categories.map(c => 
                    `<span style="background: #F1F5F9; color: #475569; padding: 2px 7px; border-radius: 4px; font-size: 11px; font-weight: 500;">${escapeHtml(c)}</span>`
                ).join(' ');
            }

            let statusBadge = '';
            if (data.similar_faq && data.similar_faq.status_label) {
                statusBadge = `<span style="background: #FEF3C7; color: #D97706; padding: 2px 7px; border-radius: 9999px; font-size: 11px; font-weight: 600;">${escapeHtml(data.similar_faq.status_label)}</span>`;
            }

            let otherCandidatesHtml = '';
            if (data.candidates && data.candidates.length > 1) {
                const extraCandidates = data.candidates.slice(1);
                const itemsHtml = extraCandidates.map(cand => `
                    <div class="tfidf-candidate-item">
                        <span class="candidate-q" title="${escapeHtml(cand.pertanyaan)}">
                            &bull; ${escapeHtml(cand.pertanyaan)}
                        </span>
                        <span class="candidate-score">${escapeHtml(cand.percentage)} Mirip</span>
                    </div>
                `).join('');

                otherCandidatesHtml = `
                    <button type="button" class="tfidf-candidates-toggle" onclick="toggleOtherCandidates(event)">
                        <span>Lihat ${extraCandidates.length} kemiripan lainnya</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div id="tfidfExtraCandidates" class="tfidf-candidates-list" style="display: none;">
                        ${itemsHtml}
                    </div>
                `;
            }

            feedbackBox.innerHTML = `
                <div class="tfidf-alert-card duplicate">
                    <div class="tfidf-header">
                        <div class="tfidf-title-area">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Peringatan Kemiripan Teks (TF-IDF)</span>
                        </div>
                        <span class="tfidf-score-badge">Skor: ${escapeHtml(data.percentage)}</span>
                    </div>
                    
                    <div class="tfidf-message">
                        Pertanyaan ini memiliki tingkat kemiripan tinggi dengan informasi yang telah tersimpan di repositori:
                    </div>

                    ${data.similar_faq ? `
                        <div class="tfidf-matched-card">
                            <div class="tfidf-matched-q">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 2px;">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <span>${escapeHtml(data.similar_faq.pertanyaan)}</span>
                            </div>
                            <div class="tfidf-matched-meta">
                                ${statusBadge}
                                ${categoriesHtml}
                            </div>
                            ${data.similar_faq.jawaban ? `
                                <div class="tfidf-matched-excerpt">
                                    ${escapeHtml(data.similar_faq.jawaban)}
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}

                    ${otherCandidatesHtml}

                    <div style="margin-top: 8px; font-size: 11.5px; color: #B45309; font-style: italic;">
                        * Harap pastikan kembali topik yang diajukan tidak menduplikasi informasi di atas.
                    </div>
                </div>
            `;
        } else {
            isDuplicateDetected = false;
            duplicateMaxScore = data.percentage;
            duplicateSimilarTitle = '';

            const isLowScore = data.highest_score > 0;
            const subtitleText = isLowScore 
                ? `Kemiripan tertinggi hanya ${escapeHtml(data.percentage)} (di bawah batas duplikasi 60%). Topik aman diajukan.`
                : `Tidak ditemukan kemiripan kata kunci dengan data di repositori. Informasi ini terdeteksi sebagai data baru.`;

            feedbackBox.innerHTML = `
                <div class="tfidf-alert-card safe">
                    <div class="tfidf-header">
                        <div class="tfidf-title-area">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>Pertanyaan Unik / Belum Ada di Repositori</span>
                        </div>
                        <span class="tfidf-score-badge">Maks: ${escapeHtml(data.percentage)}</span>
                    </div>
                    <div class="tfidf-message" style="margin-bottom: 0;">
                        ${subtitleText}
                    </div>
                </div>
            `;
        }
    }

    function toggleOtherCandidates(event) {
        event.preventDefault();
        const list = document.getElementById('tfidfExtraCandidates');
        if (!list) return;

        const isHidden = list.style.display === 'none';
        list.style.display = isHidden ? 'flex' : 'none';

        const btn = event.currentTarget;
        const arrow = btn.querySelector('svg');
        if (arrow) {
            arrow.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
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

        // Jika terdeteksi kemiripan duplikat via TF-IDF, berikan konfirmasi interaktif
        if (isDuplicateDetected) {
            const confirmed = confirm(
                `PERINGATAN DETEKSI TF-IDF:\n\n` +
                `Pertanyaan ini terdeteksi memiliki tingkat kemiripan ${duplicateMaxScore} dengan FAQ yang sudah ada di repositori:\n` +
                `"${duplicateSimilarTitle}"\n\n` +
                `Apakah Anda yakin tetap ingin melanjutkan penyimpanan informasi ini?`
            );
            if (!confirmed) {
                return;
            }
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
