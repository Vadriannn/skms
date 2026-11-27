<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqHelpfulVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'faq_id',
        'student_nrp',
        'ip_address',
        'voter_hash',
        'vote_type',
    ];

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }
}

