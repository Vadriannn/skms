@extends('layouts.app', ['title' => 'Manajemen Kategori'])

@section('content')
<h3>Manajemen Kategori FAQ (Super Admin)</h3>
<p>Halaman untuk menambah dan mengelola kategori pertanyaan akademik.</p>

<!-- Form Tambah Kategori -->
<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <fieldset>
        <legend>Tambah Kategori Baru</legend>
        <p>
            <label for="nama">Nama Kategori:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Registrasi & KRS">
            @error('nama')
                <br><span style="color: red;">{{ $message }}</span>
            @enderror
        </p>
        <button type="submit">Simpan Kategori</button>
    </fieldset>
</form>

<br>

<!-- Tabel Daftar Kategori -->
<h3>Daftar Kategori Tersimpan</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Kategori</th>
            <th>Slug URL</th>
            <th>Jumlah FAQ</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $index => $cat)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td><strong>{{ $cat->nama }}</strong></td>
                <td><code>{{ $cat->slug }}</code></td>
                <td align="center">{{ $cat->faqs_count }}</td>
                <td align="center">
                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" align="center">Belum ada kategori tersimpan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
