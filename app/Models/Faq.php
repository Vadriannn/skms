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
        'status',
        'views',
        'helpful',
    ];

    protected $casts = [
        'views'   => 'integer',
        'helpful' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_faq');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function latestReview()
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }

    public function duplicateLogs()
    {
        return $this->hasMany(DuplicateLog::class, 'faq_id');
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    /**
     * Mengetahui jenis/tipe pengajuan:
     * - 'edit': Editan dari FAQ yang sudah pernah terbit/disetujui
     * - 'revision': Revisian dari FAQ yang ditolak validator
     * - 'new': Pengajuan informasi baru
     */
    public function getSubmissionTypeAttribute()
    {
        $hasApproved = $this->reviews->contains('keputusan', 'approved');
        if ($hasApproved) {
            return 'edit';
        }

        $latest = $this->latestReview;
        if ($latest && $latest->keputusan === 'rejected') {
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
}
