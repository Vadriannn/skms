<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pertanyaan',
        'jawaban',
        'file_lampiran',
        'file_nama_asli',
        'link_video',
        'status',
        'views',
        'helpful',
        'not_helpful',
        'deactivated_at',
        'deactivated_by',
    ];

    protected $casts = [
        'views'          => 'integer',
        'helpful'        => 'integer',
        'not_helpful'    => 'integer',
        'deactivated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deactivatedByUser()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_faq');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function questionSubmissions()
    {
        return $this->hasMany(QuestionSubmission::class, 'faq_id');
    }

    public function latestReview()
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }

    public function duplicateLogs()
    {
        return $this->hasMany(DuplicateLog::class, 'faq_id');
    }

    public function latestDuplicateLog()
    {
        return $this->hasOne(DuplicateLog::class, 'faq_id')->latestOfMany('id');
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function helpfulVotes()
    {
        return $this->hasMany(FaqHelpfulVote::class, 'faq_id');
    }

    /**
     * Total feedback respon mahasiswa (Membantu + Tidak Membantu).
     */
    public function getTotalFeedbackAttribute(): int
    {
        return (int) ($this->helpful ?? 0) + (int) ($this->not_helpful ?? 0);
    }

    /**
     * Persentase respon Membantu (0 - 100%).
     */
    public function getHelpfulPercentageAttribute(): float
    {
        $total = $this->total_feedback;
        return $total > 0 ? round((($this->helpful ?? 0) / $total) * 100, 1) : 0.0;
    }

    /**
     * Persentase respon Tidak Membantu (0 - 100%).
     */
    public function getNotHelpfulPercentageAttribute(): float
    {
        $total = $this->total_feedback;
        return $total > 0 ? round((($this->not_helpful ?? 0) / $total) * 100, 1) : 0.0;
    }

    /**
     * Cek apakah FAQ memenuhi kriteria "Kurang / Tidak Membantu":
     * Memiliki feedback (> 0) DAN persentase membantu < 70% (atau tidak membantu > 30%).
     */
    public function getIsUnhelpfulAttribute(): bool
    {
        return $this->total_feedback > 0 && $this->helpful_percentage < 70;
    }

    /**
     * Scope untuk mengambil daftar FAQ yang kurang/tidak membantu (< 70% membantu).
     */
    public function scopeUnhelpful($query)
    {
        return $query->where('status', 'published')
            ->whereRaw('(COALESCE(helpful, 0) + COALESCE(not_helpful, 0)) > 0')
            ->whereRaw('((COALESCE(helpful, 0) * 100) / (COALESCE(helpful, 0) + COALESCE(not_helpful, 0))) < 70');
    }

    /**
     * Hitung berapa kali FAQ ini sudah diminta revisi oleh validator.
     */
    public function getRevisionCountAttribute(): int
    {
        return $this->reviews->where('keputusan', 'revision')->count();
    }

    /**
     * Cek apakah FAQ masih bisa diajukan revisi oleh validator (maksimal 2 kali dan status masih pending).
     */
    public function getCanBeRevisedAttribute(): bool
    {
        return $this->status === 'pending' && $this->revision_count < 2;
    }

    /**
     * Mengetahui jenis/tipe pengajuan:
     * - 'edit': Editan dari FAQ yang sudah pernah terbit/disetujui
     * - 'revision': Revisian dari FAQ yang diminta revisi atau ditolak validator
     * - 'new': Pengajuan informasi baru
     */
    public function getSubmissionTypeAttribute()
    {
        $hasApproved = $this->reviews->contains('keputusan', 'approved');
        if ($hasApproved) {
            return 'edit';
        }

        $latest = $this->latestReview;
        if ($latest && in_array($latest->keputusan, ['revision', 'rejected'])) {
            return 'revision';
        }

        return 'new';
    }

    public function getSubmissionTypeLabelAttribute()
    {
        return match ($this->submission_type) {
            'edit'     => 'Editan (Pembaruan)',
            'revision' => 'Revisi (Perbaikan)',
            default    => 'Pengajuan Baru',
        };
    }

    /**
     * Mengambil URL embed YouTube jika link_video adalah tautan YouTube yang valid.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (empty($this->link_video)) {
            return null;
        }

        $url = trim($this->link_video);
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        return null;
    }

    /**
     * Mengambil ekstensi file lampiran.
     */
    public function getFileExtensionAttribute(): string
    {
        if (empty($this->file_lampiran)) {
            return '';
        }
        return strtolower(pathinfo($this->file_nama_asli ?: $this->file_lampiran, PATHINFO_EXTENSION));
    }
}
