@extends('layouts.app', ['title' => 'Riwayat Pengajuan - SKMS'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            @if($isSuperAdmin) Semua Riwayat Pengajuan FAQ @else Riwayat Pengajuan FAQ @endif
        </h1>
        <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
            @if($isSuperAdmin)
                Daftar seluruh draft dan pengajuan informasi FAQ dari semua pengguna.
            @else
                Daftar seluruh draft dan pengajuan informasi FAQ yang telah Anda masukkan ke dalam sistem.
            @endif
        </p>
    </div>
</div>

<div class="section-card">
    <div class="section-card-header">
        <h2>
            @if($isSuperAdmin) Semua Riwayat @else Riwayat Informasi Saya @endif
            ({{ $historyFaqs->total() }})
        </h2>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th>Dibuat Oleh</th>
                    <th>Kategori</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Tanggal Dibuat</th>
                    <th style="text-align: center;">Riwayat Nonaktif / Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historyFaqs as $index => $item)
                    @php
                        $statusBadgeClass = [
                            'draft' => 'background-color: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE;',
                            'pending' => 'background-color: #FEF3C7; color: #D97706; border: 1px solid #FDE68A;',
                            'published' => 'background-color: #D1FAE5; color: #059669; border: 1px solid #A7F3D0;',
                            'rejected' => 'background-color: #FEE2E2; color: #DC2626; border: 1px solid #FECACA;',
                            'inactive' => 'background-color: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;',
                        ][$item->status] ?? '';

                        $statusBadgeText = [
                            'draft' => 'Draft',
                            'pending' => 'Menunggu Validasi',
                            'published' => 'Dipublikasikan',
                            'rejected' => 'Ditolak',
                            'inactive' => 'Nonaktif',
                        ][$item->status] ?? ucfirst($item->status);
                    @endphp
                    <tr>
                        <td>{{ $historyFaqs->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-heading); margin-bottom: 4px; {{ $item->status === 'inactive' ? 'color: #94A3B8; text-decoration: line-through;' : '' }}">
                                {{ $item->pertanyaan }}
                            </div>
                            <div style="color: var(--text-muted); font-size: 13px; line-height: 1.4;">
                                {{ Str::limit(strip_tags($item->jawaban), 90) }}
                            </div>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $item->user?->name ?? '-' }}
                        </td>
                        <td>
                            @foreach($item->categories as $cat)
                                <span style="background-color: #F1F5F9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block;">
                                    {{ $cat->nama }}
                                </span>
                            @endforeach
                        </td>
                        <td style="text-align: center;">
                            <span style="padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; display: inline-block; {{ $statusBadgeClass }}">
                                {{ $statusBadgeText }}
                            </span>
                        </td>
                        <td style="text-align: center; color: var(--text-muted); font-size: 12px;">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="text-align: center; color: var(--text-muted); font-size: 12px;">
                            @if($item->status === 'inactive' && $item->deactivated_at)
                                <div style="color: #DC2626; font-weight: 600;">
                                    Dinonaktifkan: {{ $item->deactivated_at->format('d M Y H:i') }}
                                </div>
                                <div style="font-size: 11px; color: #64748B;">
                                    oleh {{ $item->deactivatedByUser?->name ?? '-' }}
                                </div>
                            @else
                                {{ $item->updated_at->diffForHumans() }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada riwayat aktivitas pengajuan FAQ.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($historyFaqs->hasPages())
        <div class="table-pagination-footer">
            {{ $historyFaqs->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
@endsection
