@extends('layouts.app', ['title' => 'Kelola Pengguna - SKMS'])

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Pengguna</h1>
            <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
                Manajemen akun Super Admin, Validator, dan Data Entry sistem SKMS.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
        <!-- Form Tambah / Edit Pengguna (Kiri) -->
        <div class="section-card" style="padding: 24px;">
            <h2 id="userFormTitle" style="font-size: 16px; font-weight: 600; color: var(--text-heading); margin-bottom: 16px;">
                Tambah Pengguna Baru
            </h2>

            <form id="userForm" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div id="userMethodSpoof"></div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 6px;">
                        Nama Lengkap <span style="color: var(--red-text);">*</span>
                    </label>
                    <input type="text" id="userName" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rina Amalia" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('name')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 6px;">
                        Email <span style="color: var(--red-text);">*</span>
                    </label>
                    <input type="email" id="userEmail" name="email" value="{{ old('email') }}" required placeholder="rina@ubaya.ac.id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('email')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 6px;">
                        Password <span id="passwordRequiredStar" style="color: var(--red-text);">*</span>
                    </label>
                    <input type="password" id="userPassword" name="password" required placeholder="Minimal 6 karakter" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    <small id="passwordHint" style="display: none; color: var(--text-muted); font-size: 11px; margin-top: 4px;">Kosongkan jika tidak ingin mengubah password.</small>
                    @error('password')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 22px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 6px;">
                        Peran (Role) <span style="color: var(--red-text);">*</span>
                    </label>
                    <select id="userRole" name="role" class="select-filter" style="width: 100%;" required>
                        <option value="data_entry">Data Entry</option>
                        <option value="validator">Validator</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" id="userSubmitBtn" class="btn-primary" style="flex: 1;">
                        Daftarkan Pengguna
                    </button>
                    <button type="button" id="userCancelBtn" onclick="resetUserForm()" class="btn-secondary" style="display: none;">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Pengguna (Kanan) -->
        <div class="section-card">
            <div class="section-card-header">
                <h2>Daftar Akun Pengguna ({{ $users->total() }})</h2>
            </div>
            <div class="table-responsive">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="text-align: center; width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-heading);">{{ $u->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $u->email }}</div>
                                </td>
                                <td>
                                    @if($u->role === 'super_admin')
                                        <span class="badge" style="background-color: #F3E8FF; color: #7E22CE;">Super Admin</span>
                                    @elseif($u->role === 'validator')
                                        <span class="badge badge-indigo">Validator</span>
                                    @else
                                        <span class="badge badge-amber">Data Entry</span>
                                    @endif
                                </td>
                                <td>
                                    @if($u->is_active)
                                        <span class="badge badge-emerald">Aktif</span>
                                    @else
                                        <span class="badge badge-red">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                                        <button type="button" onclick="editUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ $u->role }}')" class="btn-secondary" style="padding: 5px 9px; font-size: 12px;">
                                            Edit
                                        </button>
                                        @if($u->id !== Auth::id())
                                            <form action="{{ route('users.toggle', $u->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-secondary" style="padding: 5px 9px; font-size: 12px;">
                                                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-secondary" style="padding: 5px 9px; font-size: 12px; color: var(--red-text); border-color: var(--red-border);">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span style="font-size: 11px; color: var(--text-subtle); margin-left: 4px;">(Anda)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div style="padding: 16px;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editUser(id, name, email, role) {
        document.getElementById('userFormTitle').innerText = 'Edit Pengguna';
        document.getElementById('userName').value = name;
        document.getElementById('userEmail').value = email;
        document.getElementById('userRole').value = role;
        
        const passwordInput = document.getElementById('userPassword');
        passwordInput.required = false;
        passwordInput.placeholder = "Kosongkan jika tidak diubah";
        document.getElementById('passwordRequiredStar').style.display = 'none';
        document.getElementById('passwordHint').style.display = 'block';

        const form = document.getElementById('userForm');
        form.action = `/users/${id}`;
        document.getElementById('userMethodSpoof').innerHTML = '@method("PUT")';
        document.getElementById('userSubmitBtn').innerText = 'Perbarui Pengguna';
        document.getElementById('userCancelBtn').style.display = 'inline-flex';
        
        document.getElementById('userName').focus();
    }

    function resetUserForm() {
        document.getElementById('userFormTitle').innerText = 'Tambah Pengguna Baru';
        document.getElementById('userName').value = '';
        document.getElementById('userEmail').value = '';
        document.getElementById('userRole').value = 'data_entry';
        
        const passwordInput = document.getElementById('userPassword');
        passwordInput.required = true;
        passwordInput.value = '';
        passwordInput.placeholder = "Minimal 6 karakter";
        document.getElementById('passwordRequiredStar').style.display = 'inline';
        document.getElementById('passwordHint').style.display = 'none';

        const form = document.getElementById('userForm');
        form.action = "{{ route('users.store') }}";
        document.getElementById('userMethodSpoof').innerHTML = '';
        document.getElementById('userSubmitBtn').innerText = 'Daftarkan Pengguna';
        document.getElementById('userCancelBtn').style.display = 'none';
    }
</script>
@endpush
