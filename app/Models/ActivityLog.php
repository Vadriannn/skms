<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aktivitas',
        'tipe',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper statis untuk merekam aktivitas secara cepat.
     */
    public static function log(string $aktivitas, string $tipe = 'general', ?int $userId = null): self
    {
        return self::create([
            'user_id'   => $userId ?? auth()->id(),
            'aktivitas' => $aktivitas,
            'tipe'      => $tipe,
        ]);
    }
}
