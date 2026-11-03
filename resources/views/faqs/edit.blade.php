@extends('layouts.app', [
    'title' => ($faq->status === 'rejected' ? 'Revisi Informasi FAQ' : 'Edit Informasi FAQ') . ' - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ $faq->status === 'published' ? route('faqs.index') : route('faqs.submissions', ['status' => $faq->status]) }}" 
           style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #FFFFFF; border: 1px solid var(--border-color); color: var(--text-body); text-decoration: none;" 
           title="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <h1 class="topbar-page-title" style="margin: 0;">
            {{ $faq->status === 'rejected' ? 'Revisi Informasi FAQ' : 'Edit Informasi FAQ' }}
        </h1>
    </div>
@endsection

@section('content')
<div class="faq-create-container">
    <div class="faq-create-card">
        
        <!-- Header Formulir -->
        <div class="faq-create-header">
            <div>
                <h2 class="faq-create-title">
                    {{ $faq->status === 'rejected' ? 'Formulir Revisi & Pengajuan Ulang' : 'Perbarui Data Informasi FAQ' }}
                </h2>
                <p class="faq-create-subtitle">
                    {{ $faq->status === 'rejected' 
                        ? 'Perbaiki pertanyaan dan jawaban sesuai catatan validator sebelum diajukan kembali.' 
                        : 'Sesuaikan pertanyaan, jawaban, atau kategori informasi akademik yang bersangkutan.' }}
                </p>
            </div>
            <div>
                @if($faq->status === 'rejected')
                    <span class="status-pill status-rejected">Perlu Revisi</span>
                @elseif($faq->status === 'pending')
                    <span class="status-pill status-pending">Menunggu Validasi</span>
                @elseif($faq->status === 'published')
                    <span class="status-pill status-approved">Disetujui</span>
                @else
                    <span class="status-pill status-draft">Draft</span>
                @endif
            </div>
        </div>

        <!-- Alert Alasan Penolakan dari Validator (Jika status rejected) -->
        @if($faq->status === 'rejected' && $faq->latestReview && $faq->latestReview->catatan)
            <div style="background-color: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; padding: 16px 18px; margin-bottom: 24px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #991B1B; font-size: 14px; margin-bottom: 4px;">
                            Alasan Penolakan / Catatan Perbaikan dari Validator
                        </div>
                        <div style="color: #B91C1C; font-size: 14px; line-height: 1.5;">
                            {{ $faq->latestReview->catatan }}
                        </div>
                        <div style="margin-top: 8px; font-size: 12px; color: #7F1D1D; opacity: 0.85;">
                            Diverifikasi oleh: <strong>{{ $faq->latestReview->user ? $faq->latestReview->user->name : 'Validator' }}</strong> &bull; {{ $faq->latestReview->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Alert Peringatan Re-Validation Jika FAQ Sudah Published -->
        @if($faq->status === 'published')
            <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 16px 18px; margin-bottom: 24px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #1E40AF; font-size: 14px; margin-bottom: 4px;">
                            Perhatian: FAQ Ini Sudah Dipublikasikan
                        </div>
                        <div style="color: #1E3A8A; font-size: 13.5px; line-height: 1.5;">
                            @if(Auth::user()->role === 'data_entry')
                                Setiap perubahan yang Anda simpan akan secara otomatis <strong>menarik informasi ini dari publik</strong> dan mengirimkannya ke <strong>antrean Validator untuk diverifikasi ulang</strong> sebelum dapat tayang kembali.
                            @else
                                Sebagai Super Admin, Anda dapat memilih untuk langsung memperbarui publikasi atau mengajukannya ke antrean validasi ulang.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('faqs.update', $faq->id) }}" method="POST" id="faqEditForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="action_type" id="actionTypeInput" value="{{ $faq->status === 'rejected' ? 'resubmit' : 'pending' }}">

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
                           value="{{ old('pertanyaan', $faq->pertanyaan) }}">
                    <div id="similaritySpinner" class="similarity-spinner-icon">
                        <span class="spinner-sm"></span>
                        <span>Mengecek TF-IDF...</span>
                    </div>
                </div>

                <!-- Feedback TF-IDF -->
                <div id="tfidfFeedbackBox" class="tfidf-feedback-container" style="display: none; margin-top: 12px;"></div>
                @error('pertanyaan')
                    <div style="color: #EF4444; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. Kategori Informasi (Multi-Category Selector) -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label">
                    Kategori Informasi (Dapat memilih lebih dari satu) <span class="required">*</span>
                </label>
                @php
                    $selectedCatIds = old('category_ids', $faq->categories->pluck('id')->toArray());
                @endphp
                <div class="multi-category-selector">
                    @foreach($categories as $category)
                        <label class="category-toggle-chip">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="chip-checkbox"
                                   {{ in_array($category->id, $selectedCatIds) ? 'checked' : '' }}>
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
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" for="jawaban">
                    Jawaban / Penjelasan Informasi <span class="required">*</span>
                </label>
                <textarea id="jawaban" name="jawaban" rows="8" required
                          class="form-control"
                          style="line-height: 1.6; resize: vertical;"
                          placeholder="Tuliskan jawaban, alur, persyaratan, atau panduan lengkap mengenai informasi ini...">{{ old('jawaban', $faq->jawaban) }}</textarea>
                @error('jawaban')
                    <div style="color: #EF4444; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 4. Tombol Aksi Bawah -->
            <div class="faq-create-footer">
                <a href="{{ $faq->status === 'published' ? route('faqs.index') : route('faqs.submissions', ['status' => $faq->status]) }}" 
                   class="btn-modal-cancel" 
                   style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Batal / Kembali</span>
                </a>

                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    @if($faq->status === 'rejected')
                        <!-- Opsi 1: Simpan Draft -->
                        <button type="button" class="btn-modal-draft" onclick="submitEditForm('draft')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>Simpan sebagai Draft</span>
                        </button>
                        <!-- Opsi 2: Ajukan Kembali ke Validator -->
                        <button type="button" class="btn-modal-submit" onclick="submitEditForm('resubmit')" style="background-color: #2563EB;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            <span>Ajukan Kembali ke Validator</span>
                        </button>
                    @elseif($faq->status === 'draft')
                        <button type="button" class="btn-modal-draft" onclick="submitEditForm('draft')">
                            <span>Simpan Perubahan Draft</span>
                        </button>
                        <button type="button" class="btn-modal-submit" onclick="submitEditForm('pending')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            <span>Kirim untuk Validasi</span>
                        </button>
                    @elseif($faq->status === 'published')
                        <button type="button" class="btn-modal-draft" onclick="submitEditForm('draft')">
                            <span>Tarik ke Draft</span>
                        </button>
                        @if(Auth::user()->role === 'super_admin')
                            <button type="button" class="btn-modal-draft" onclick="submitEditForm('resubmit')" style="color: #2563EB; border-color: #93C5FD;">
                                <span>Kirim ke Validator (Validasi Ulang)</span>
                            </button>
                            <button type="button" class="btn-modal-submit" onclick="submitEditForm('published')">
                                <span>Simpan Langsung (Tetap Terbit)</span>
                            </button>
                        @else
                            <button type="button" class="btn-modal-submit" onclick="submitEditForm('resubmit')" style="background-color: #2563EB;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                <span>Simpan & Ajukan Validasi Ulang</span>
                            </button>
                        @endif
                    @else
                        <!-- Status pending -->
                        <button type="button" class="btn-modal-draft" onclick="submitEditForm('draft')">
                            <span>Tarik ke Draft</span>
                        </button>
                        <button type="button" class="btn-modal-submit" onclick="submitEditForm('pending')">
                            <span>Simpan Perubahan</span>
                        </button>
                    @endif
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
    const currentFaqId = {{ $faq->id }};

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function checkSimilarityRealtime(text) {
        const query = text.trim();
        const spinner = document.getElementById('similaritySpinner');
        const feedbackBox = document.getElementById('tfidfFeedbackBox');

        if (query.length < 4) {
            feedbackBox.style.display = 'none';
            feedbackBox.innerHTML = '';
            spinner.style.display = 'none';
            return;
        }

        spinner.style.display = 'inline-flex';

        if (similarityAbortController) similarityAbortController.abort();
        similarityAbortController = new AbortController();

        fetch("{{ route('faqs.check_similarity') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pertanyaan: query,
                threshold: 0.60,
                exclude_id: currentFaqId
            }),
            signal: similarityAbortController.signal
        })
        .then(res => res.json())
        .then(data => {
            spinner.style.display = 'none';
            if (!data.has_checked || !data.is_duplicate) {
                feedbackBox.style.display = 'none';
                feedbackBox.innerHTML = '';
                return;
            }

            const sim = data.similar_faq;
            let catsHtml = '';
            if (sim.categories && sim.categories.length > 0) {
                catsHtml = sim.categories.map(c => `<span class="category-pill">${escapeHtml(c)}</span>`).join(' ');
            }

            feedbackBox.innerHTML = `
                <div class="tfidf-alert danger">
                    <div class="tfidf-alert-header">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span class="tfidf-alert-title">Peringatan Duplikasi Pertanyaan (TF-IDF: ${data.percentage})</span>
                        </div>
                        <span class="tfidf-score-badge">&ge; 60% Mirip</span>
                    </div>
                    <p class="tfidf-alert-desc">
                        Pertanyaan ini terdeteksi memiliki tingkat kemiripan tinggi dengan informasi yang sudah ada di repositori:
                    </p>
                    <div class="tfidf-matched-item">
                        <div class="matched-title">&ldquo;${escapeHtml(sim.pertanyaan)}&rdquo;</div>
                        <div class="matched-meta">
                            <span class="status-pill status-${sim.status}">${escapeHtml(sim.status_label)}</span>
                            <div class="category-pills-container" style="display: inline-flex; margin-left: 6px;">
                                ${catsHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            feedbackBox.style.display = 'block';
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                spinner.style.display = 'none';
            }
        });
    }

    document.getElementById('pertanyaan').addEventListener('input', function(e) {
        if (similarityTimer) clearTimeout(similarityTimer);
        similarityTimer = setTimeout(() => {
            checkSimilarityRealtime(e.target.value);
        }, 450);
    });

    function submitEditForm(actionType) {
        document.getElementById('actionTypeInput').value = actionType;
        document.getElementById('faqEditForm').submit();
    }
</script>
@endpush

