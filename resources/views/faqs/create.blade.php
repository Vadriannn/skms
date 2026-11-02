@extends('layouts.app', [
    'title' => 'Tambah Informasi Baru (FAQ) - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('faqs.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #FFFFFF; border: 1px solid var(--border-color); color: var(--text-body); text-decoration: none;" title="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <h1 class="topbar-page-title" style="margin: 0;">Tambah Informasi Baru (FAQ)</h1>
    </div>
@endsection

@section('content')
<div class="faq-create-container">
    <div class="faq-create-card">
        <div class="faq-create-header">
            <div>
                <h2 class="faq-create-title">Formulir Input Informasi FAQ</h2>
                <p class="faq-create-subtitle">Masukkan pertanyaan dan jawaban akademik. Sistem otomatis mengecek potensi duplikasi dengan algoritma TF-IDF.</p>
            </div>
        </div>

        <form action="{{ route('faqs.store') }}" method="POST" id="faqForm">
            @csrf
            <input type="hidden" name="action_type" id="actionTypeInput" value="draft">

            <!-- 1. Pertanyaan + TF-IDF Real-time Check -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label" for="pertanyaan">
                    Pertanyaan / Judul Informasi <span class="required">*</span>
                </label>
                <div class="similarity-input-wrapper">
                    <input type="text" id="pertanyaan" name="pertanyaan" required
                           class="form-control"
                           placeholder="Misal: Bagaimana prosedur pengajuan cuti kuliah semester ganjil?"
                           autocomplete="off"
                           value="{{ old('pertanyaan') }}">
                    <div id="similaritySpinner" class="similarity-spinner-icon">
                        <span class="spinner-sm"></span>
                        <span>Mengecek TF-IDF...</span>
                    </div>
                </div>

                <!-- Kotak Feedback Deteksi Kemiripan Otomatis TF-IDF -->
                <div id="tfidfFeedbackBox" class="tfidf-feedback-container" style="display: none; margin-top: 12px;"></div>
            </div>

            <!-- 2. Kategori Informasi (Multi-Category Selector) -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label">
                    Kategori Informasi (Dapat memilih lebih dari satu) <span class="required">*</span>
                </label>
                <div class="multi-category-selector">
                    @foreach($categories as $category)
                        <label class="category-toggle-chip">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="chip-checkbox"
                                   {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                            <span class="chip-content">
                                <svg class="chip-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                {{ $category->nama }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('category_ids')
                    <div style="color: #EF4444; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3. Jawaban Informasi -->
            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" for="jawaban">
                    Jawaban / Penjelasan Informasi <span class="required">*</span>
                </label>
                <textarea id="jawaban" name="jawaban" rows="8" required
                          class="form-control"
                          style="line-height: 1.6; resize: vertical;"
                          placeholder="Tuliskan jawaban, alur, persyaratan, atau panduan lengkap mengenai informasi ini...">{{ old('jawaban') }}</textarea>
                @error('jawaban')
                    <div style="color: #EF4444; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 4. Tombol Aksi -->
            <div class="faq-create-footer">
                <a href="{{ route('faqs.index') }}" class="btn-modal-cancel" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <button type="button" class="btn-modal-draft" onclick="submitFaqForm('draft')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <span>Simpan sebagai Draft</span>
                    </button>
                    <button type="button" class="btn-modal-submit" onclick="submitFaqForm('pending')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span>Kirim untuk Validasi</span>
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
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>Peringatan Duplikasi Pertanyaan (Kemiripan: ${escapeHtml(data.percentage)})</span>
                        </div>
                        <span class="tfidf-score-badge">&ge; 60% Mirip</span>
                    </div>

                    <div class="tfidf-message">
                        Pertanyaan ini terdeteksi memiliki kemiripan tinggi dengan informasi yang sudah terdaftar di repositori FAQ:
                    </div>

                    <!-- KARTU PREVIEW FAQ PEMBANDING (TERPISAH & JELAS) -->
                    <div class="tfidf-matched-card">
                        <div class="tfidf-matched-topbar">
                            <span class="tfidf-matched-badge-tag">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                FAQ Pembanding #${data.similar_faq.id || ''}
                            </span>
                            ${statusBadge}
                        </div>

                        <!-- 1. Pertanyaan Pembanding -->
                        <div class="tfidf-matched-row">
                            <div class="tfidf-field-label">Pertanyaan di Repositori:</div>
                            <div class="tfidf-matched-q">&ldquo;${escapeHtml(data.similar_faq.pertanyaan)}&rdquo;</div>
                        </div>

                        <!-- 2. Jawaban Pembanding -->
                        ${data.similar_faq.jawaban ? `
                        <div class="tfidf-matched-row">
                            <div class="tfidf-field-label">Cuplikan Jawaban:</div>
                            <div class="tfidf-matched-excerpt">${escapeHtml(data.similar_faq.jawaban)}...</div>
                        </div>` : ''}

                        <!-- 3. Kategori Pembanding -->
                        ${categoriesHtml ? `
                        <div class="tfidf-matched-row" style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #E2E8F0;">
                            <div class="tfidf-field-label" style="margin-bottom: 6px;">Kategori Terkait:</div>
                            <div class="tfidf-matched-meta">
                                ${categoriesHtml}
                            </div>
                        </div>` : ''}
                    </div>

                    ${otherCandidatesHtml}
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
</script>
@endpush

