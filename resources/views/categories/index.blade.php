@extends('layouts.app', ['title' => 'Manajemen Kategori - SKMS'])

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kategori Informasi</h1>
            <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
                Kelola taksonomi dan pengelompokan pertanyaan FAQ akademik dan kemahasiswaan.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
        <!-- Form Tambah / Edit Kategori (Kiri) -->
        <div class="section-card" style="padding: 24px;">
            <h2 id="formHeader" style="font-size: 16px; font-weight: 600; color: var(--text-heading); margin-bottom: 16px;">
                Tambah Kategori Baru
            </h2>

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div id="methodSpoof"></div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 8px;">
                        Nama Kategori <span style="color: var(--red-text);">*</span>
                    </label>
                    <input type="text" id="namaInput" name="nama" value="{{ old('nama') }}" required placeholder="Misal: Keuangan & SPP" style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('nama')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" id="submitBtn" class="btn-primary" style="flex: 1;">
                        Simpan Kategori
                    </button>
                    <button type="button" id="cancelEditBtn" onclick="resetForm()" class="btn-secondary" style="display: none;">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Kategori (Kanan) -->
        <div class="section-card">
            <div class="section-card-header">
                <h2>Daftar Kategori Tersimpan ({{ $categories->count() }})</h2>
            </div>
            <div class="table-responsive">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug URL</th>
                            <th style="text-align: center;">Jumlah FAQ</th>
                            <th style="text-align: center; width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $cat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong style="color: var(--text-heading);">{{ $cat->nama }}</strong>
                                </td>
                                <td>
                                    <code style="background: var(--bg-page); padding: 3px 6px; border-radius: 4px; font-size: 12px; color: var(--text-muted);">
                                        {{ $cat->slug }}
                                    </code>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-indigo">
                                        {{ $cat->faqs_count }} FAQ
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <button type="button" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->nama) }}')" class="btn-secondary" style="padding: 6px 10px; font-size: 12px;">
                                            Edit
                                        </button>
                                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Seluruh relasi FAQ ke kategori ini akan dilepas.');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-secondary" style="padding: 6px 10px; font-size: 12px; color: var(--red-text); border-color: var(--red-border);">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    Belum ada kategori tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editCategory(id, nama) {
        document.getElementById('formHeader').innerText = 'Edit Kategori';
        document.getElementById('namaInput').value = nama;
        document.getElementById('namaInput').focus();
        
        const form = document.getElementById('categoryForm');
        form.action = `/categories/${id}`;
        document.getElementById('methodSpoof').innerHTML = '@method("PUT")';
        document.getElementById('submitBtn').innerText = 'Perbarui Kategori';
        document.getElementById('cancelEditBtn').style.display = 'inline-flex';
    }

    function resetForm() {
        document.getElementById('formHeader').innerText = 'Tambah Kategori Baru';
        document.getElementById('namaInput').value = '';
        const form = document.getElementById('categoryForm');
        form.action = "{{ route('categories.store') }}";
        document.getElementById('methodSpoof').innerHTML = '';
        document.getElementById('submitBtn').innerText = 'Simpan Kategori';
        document.getElementById('cancelEditBtn').style.display = 'none';
    }
</script>
@endpush
