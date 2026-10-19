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
}
