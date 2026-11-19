<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSubmission extends Model
{
    use HasFactory;

    protected $table = 'question_submissions';

    protected $fillable = [
        'nrp',
        'pertanyaan',
        'status',
        'note',
        'faq_id',
        'user_id',
    ];

    /**
     * Email kampus mahasiswa otomatis dari 9 digit NRP UBAYA.
     */
    public function getStudentEmailAttribute(): string
    {
        return 's' . $this->nrp . '@student.ubaya.ac.id';
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
