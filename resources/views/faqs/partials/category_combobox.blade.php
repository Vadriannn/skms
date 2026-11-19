{{-- Reusable Multi-Select Search Bar + Combobox Kategori (Dipakai di create.blade.php & edit.blade.php) --}}
@php
    $selectedIds = is_array($selectedCatIds ?? null) ? array_map('intval', $selectedCatIds) : [];
@endphp

<div class="cat-combo-wrapper" id="faqCatComboWrapper">
    <!-- Search Bar Box (Kategori terpilih tampil sebagai tag di dalam search bar ini) -->
    <div class="cat-combo-control" id="faqCatComboControl">
        <svg class="cat-combo-search-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>

        <!-- Tag kategori terpilih ditampilkan inline di dalam search bar -->
        <div class="cat-combo-tags-inline" id="faqCatInlineTags"></div>

        <input type="text"
               id="faqCatSearchInput"
               class="cat-combo-input"
               placeholder="Cari atau pilih kategori..."
               autocomplete="off">

        <svg class="cat-combo-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>

    <!-- Dropdown Combobox Daftar Kategori -->
    <div class="cat-combo-dropdown" id="faqCatComboDropdown">
        <div class="cat-combo-list" id="faqCatComboList">
            @foreach($categories as $category)
                @php $isSelected = in_array((int) $category->id, $selectedIds, true); @endphp
                <label class="cat-combo-option {{ $isSelected ? 'is-selected' : '' }}"
                       data-name="{{ strtolower($category->nama) }}"
                       data-label="{{ $category->nama }}"
                       data-id="{{ $category->id }}">
                    <div class="cat-combo-option-left">
                        <input type="checkbox"
                               name="category_ids[]"
                               value="{{ $category->id }}"
                               class="faq-cat-checkbox"
                               {{ $isSelected ? 'checked' : '' }}>
                        <span>{{ $category->nama }}</span>
                    </div>
                </label>
            @endforeach

            <div class="cat-combo-empty" id="faqCatComboEmpty">
                Kategori tidak ditemukan.
            </div>
        </div>

        <div class="cat-combo-footer">
            <span id="faqCatComboCounter">{{ count($selectedIds) > 0 ? count($selectedIds) . ' kategori dipilih' : 'Pilih minimal 1 kategori' }}</span>
            <button type="button" class="cat-combo-done-btn" id="faqCatComboDoneBtn">Selesai</button>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    const wrapper = document.getElementById('faqCatComboWrapper');
    if (!wrapper) return;

    const control = document.getElementById('faqCatComboControl');
    const searchInput = document.getElementById('faqCatSearchInput');
    const inlineTags = document.getElementById('faqCatInlineTags');
    const options = Array.from(wrapper.querySelectorAll('.cat-combo-option'));
    const checkboxes = Array.from(wrapper.querySelectorAll('.faq-cat-checkbox'));
    const emptyNotice = document.getElementById('faqCatComboEmpty');
    const counterText = document.getElementById('faqCatComboCounter');
    const doneBtn = document.getElementById('faqCatComboDoneBtn');

    function openDropdown() {
        wrapper.classList.add('is-open');
    }

    function closeDropdown() {
        wrapper.classList.remove('is-open');
    }

    function renderSelectedTags() {
        inlineTags.innerHTML = '';
        let selectedCount = 0;

        options.forEach(opt => {
            const cb = opt.querySelector('.faq-cat-checkbox');
            if (cb.checked) {
                selectedCount++;
                opt.classList.add('is-selected');

                const tag = document.createElement('span');
                tag.className = 'cat-combo-tag';
                tag.innerHTML = `<span>${opt.dataset.label}</span><button type="button" class="cat-combo-tag-remove" title="Hapus kategori">&times;</button>`;
                tag.querySelector('button').addEventListener('click', function (e) {
                    e.stopPropagation();
                    cb.checked = false;
                    renderSelectedTags();
                    searchInput.focus();
                });
                inlineTags.appendChild(tag);
            } else {
                opt.classList.remove('is-selected');
            }
        });

        searchInput.placeholder = selectedCount > 0 ? 'Tambah kategori...' : 'Cari atau pilih kategori...';
        counterText.textContent = selectedCount > 0
            ? `${selectedCount} kategori dipilih`
            : 'Pilih minimal 1 kategori';
    }

    function filterOptions() {
        const q = (searchInput.value || '').trim().toLowerCase();
        let visible = 0;

        options.forEach(opt => {
            const match = opt.dataset.name.includes(q);
            opt.style.display = match ? 'flex' : 'none';
            if (match) visible++;
        });

        emptyNotice.style.display = visible === 0 ? 'block' : 'none';
    }

    // Klik di mana saja di dalam search bar membuka dropdown & fokus input
    control.addEventListener('click', function () {
        openDropdown();
        searchInput.focus();
    });

    searchInput.addEventListener('focus', openDropdown);
    searchInput.addEventListener('input', function () {
        openDropdown();
        filterOptions();
    });

    // Tekan Backspace saat input kosong untuk menghapus tag terakhir
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && searchInput.value === '') {
            const checked = checkboxes.filter(cb => cb.checked);
            if (checked.length > 0) {
                checked[checked.length - 1].checked = false;
                renderSelectedTags();
            }
        } else if (e.key === 'Escape') {
            closeDropdown();
            searchInput.blur();
        }
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            renderSelectedTags();
            // Bersihkan teks pencarian setelah memilih agar mudah pilih lagi
            if (searchInput.value.trim() !== '') {
                searchInput.value = '';
                filterOptions();
            }
        });
    });

    if (doneBtn) {
        doneBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            closeDropdown();
        });
    }

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) {
            closeDropdown();
        }
    });

    renderSelectedTags();
})();
</script>
@endpush
@endonce

