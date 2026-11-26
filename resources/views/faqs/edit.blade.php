@php
    $isRevision = $faq->latestReview && $faq->latestReview->keputusan === 'revision';
    $revisionCount = $faq->reviews->where('keputusan', 'revision')->count();
@endphp

@extends('layouts.app', [
    'title' => ($isRevision ? 'Revisi Informasi FAQ' : 'Edit Informasi FAQ') . ' - SKMS',
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
            {{ $isRevision ? 'Revisi Informasi FAQ' : 'Edit Informasi FAQ' }}
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
                    {{ $isRevision ? 'Formulir Revisi & Pengajuan Ulang' : 'Perbarui Data Informasi FAQ' }}
                </h2>
                <p class="faq-create-subtitle">
                    {{ $isRevision 
                        ? "Perbaiki pertanyaan dan jawaban sesuai catatan validator sebelum diajukan kembali (Revisi ke-{$revisionCount} dari maksimal 2 kali)." 
                        : 'Sesuaikan pertanyaan, jawaban, atau kategori informasi akademik yang bersangkutan.' }}
                </p>
            </div>
            <div>
                @if($isRevision)
                    <span class="status-pill" style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;">
                        Perlu Revisi ({{ $revisionCount }}/2)
                    </span>
                @elseif($faq->status === 'pending')
                    <span class="status-pill status-pending">Menunggu Validasi</span>
                @elseif($faq->status === 'published')
                    <span class="status-pill status-approved">Disetujui</span>
                @else
                    <span class="status-pill status-draft">Draft</span>
                @endif
            </div>
        </div>

        <!-- Alert Catatan Revisi dari Validator -->
        @if($isRevision && $faq->latestReview && $faq->latestReview->catatan)
            <div style="background-color: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 16px 18px; margin-bottom: 24px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #92400E; font-size: 14px; margin-bottom: 4px;">
                            Catatan Revisi dari Validator (Revisi ke-{{ $revisionCount }} dari 2)
                        </div>
                        <div style="color: #78350F; font-size: 14px; line-height: 1.5;">
                            {{ $faq->latestReview->catatan }}
                        </div>
                        <div style="margin-top: 8px; font-size: 12px; color: #B45309; opacity: 0.9;">
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

        <form action="{{ route('faqs.update', $faq->id) }}" method="POST" id="faqEditForm" enctype="multipart/form-data">
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

            <!-- 2. Kategori Informasi (Search Bar + Combobox Inline Tags) -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label" for="faqCatSearchInput">
                    Kategori Informasi (Dapat memilih lebih dari satu) <span class="required">*</span>
                </label>
                @php
                    $selectedCatIds = old('category_ids', $faq->categories->pluck('id')->toArray());
                @endphp
                @include('faqs.partials.category_combobox', [
                    'categories' => $categories,
                    'selectedCatIds' => $selectedCatIds
                ])
                @error('category_ids')
                    <div style="color: #EF4444; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3. Jawaban Informasi -->
            <div class="form-group" style="margin-bottom: 20px;">
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

            <!-- 4. Media & Berkas Pendukung (Opsional) -->
            <div style="background: #F9FBFA; border: 1px solid #ECE4E5; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                    <span style="font-size: 14px; font-weight: 700; color: #1F1415;">Media &amp; Berkas Pendukung (Opsional)</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                    <!-- A. Upload / Ganti File Lampiran -->
                    <div>
                        <label class="form-label" for="file_lampiran" style="margin-bottom: 6px;">
                            Berkas Lampiran / Panduan
                        </label>

                        @if($faq->file_lampiran)
                            <div style="background: #FFFFFF; border: 1px solid #F1CFD3; border-radius: 8px; padding: 10px 14px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2" style="flex-shrink: 0;">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    <span style="font-size: 12.5px; font-weight: 600; color: #1F1415; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $faq->file_nama_asli ?: 'File Lampiran' }}">
                                        {{ $faq->file_nama_asli ?: basename($faq->file_lampiran) }}
                                    </span>
                                </div>
                                <a href="{{ route('faqs.download', $faq->id) }}" target="_blank" style="font-size: 11.5px; font-weight: 600; color: #8B0D18; text-decoration: none; white-space: nowrap;">
                                    Unduh
                                </a>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #C93535; cursor: pointer;">
                                    <input type="checkbox" name="hapus_file" value="1">
                                    <span>Hapus file lampiran saat ini</span>
                                </label>
                            </div>
                        @endif

                        <div style="position: relative;">
                            <input type="file" id="file_lampiran" name="file_lampiran" class="form-control" style="padding: 7px 12px; height: auto; background: #FFFFFF;"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                        </div>
                        <div style="font-size: 11.5px; color: #6B5B5D; margin-top: 5px; line-height: 1.4;">
                            {{ $faq->file_lampiran ? 'Pilih file baru jika ingin mengganti file yang sudah ada.' : 'Format didukung: PDF, Word, Excel, PPT, Gambar, ZIP (Maks. 10 MB).' }}
                        </div>
                        @error('file_lampiran')
                            <div style="color: #EF4444; font-size: 12.5px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- B. Link Video -->
                    <div>
                        <label class="form-label" for="link_video" style="margin-bottom: 6px;">
                            Link Video Panduan / Tutorial
                        </label>
                        <div style="position: relative;">
                            <input type="url" id="link_video" name="link_video" class="form-control" style="padding-left: 36px; background: #FFFFFF;"
                                   placeholder="Contoh: https://www.youtube.com/watch?v=..."
                                   value="{{ old('link_video', $faq->link_video) }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="2" style="position: absolute; left: 12px; top: 11px;">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                        </div>
                        <div style="font-size: 11.5px; color: #6B5B5D; margin-top: 5px; line-height: 1.4;">
                            Masukkan URL video YouTube atau tautan video eksternal lainnya.
                        </div>
                        @error('link_video')
                            <div style="color: #EF4444; font-size: 12.5px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
                    @if($isRevision || $faq->status === 'pending')
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
                        <button type="button" class="btn-modal-submit" onclick="submitEditForm('pending')" style="background-color: #8B0D18;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            <span>{{ $isRevision ? 'Ajukan Revisi ke Validator' : 'Kirim untuk Validasi' }}</span>
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

