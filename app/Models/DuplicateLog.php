<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuplicateLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faq_id',
        'similar_faq_id',
        'score',
        'checked_at',
    ];

    protected $casts = [
        'score' => 'float',
        'checked_at' => 'datetime',
    ];

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_id');
    }

    public function similarFaq()
    {
        return $this->belongsTo(Faq::class, 'similar_faq_id');
    }
}
