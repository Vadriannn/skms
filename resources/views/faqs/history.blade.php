@extends('layouts.app', ['title' => 'Riwayat Pengajuan - SKMS'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Riwayat Pengajuan FAQ</h1>
        <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">
            Daftar seluruh draft dan pengajuan informasi FAQ yang telah Anda masukkan ke dalam sistem.
        </p>
    </div>
</div>

<div class="section-card">
    <div class="section-card-header">
        <h2>Riwayat Informasi Saya ({{ $historyFaqs->total() }})</h2>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Tanggal Dibuat</th>
                    <th style="text-align: center;">Terakhir Diperbarui</th>
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
                        ][$item->status] ?? '';

                        $statusBadgeText = [
                            'draft' => 'Draft',
                            'pending' => 'Menunggu Validasi',
                            'published' => 'Dipublikasikan',
                            'rejected' => 'Ditolak',
                        ][$item->status] ?? ucfirst($item->status);
                    @endphp
                    <tr>
                        <td>{{ $historyFaqs->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-heading); margin-bottom: 4px;">
                                {{ $item->pertanyaan }}
                            </div>
                            <div style="color: var(--text-muted); font-size: 13px; line-height: 1.4;">
                                {{ Str::limit(strip_tags($item->jawaban), 90) }}
                            </div>
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
                            {{ $item->updated_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada riwayat aktivitas pengajuan FAQ.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($historyFaqs->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color);">
            {{ $historyFaqs->links() }}
        </div>
    @endif
</div>
@endsection
