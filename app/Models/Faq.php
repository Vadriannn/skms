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
    ];

    protected $casts = [
        'views' => 'integer',
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

    public function duplicateLogs()
    {
        return $this->hasMany(DuplicateLog::class, 'faq_id');
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }
}
