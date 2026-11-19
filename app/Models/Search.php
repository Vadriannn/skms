<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrp',
        'kata_kunci',
        'faq_id',
    ];

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }

    /**
     * Scope untuk pencarian yang tidak menghasilkan FAQ langsung saat dicari.
     */
    public function scopeUnanswered($query)
    {
        return $query->whereNull('faq_id');
    }

    /**
     * Scope untuk pencarian yang terhubung dengan FAQ.
     */
    public function scopeAnswered($query)
    {
        return $query->whereNotNull('faq_id');
    }
}
