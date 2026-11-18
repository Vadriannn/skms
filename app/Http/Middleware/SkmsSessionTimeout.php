<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware SKMS Session Timeout.
 *
 * Jika pengguna tidak aktif selama 3 menit, sesi akan di-regenerasi
 * sehingga mahasiswa berikutnya dianggap pengunjung baru
 * dan mendapat identitas voter_hash yang berbeda.
 */
class SkmsSessionTimeout
{
    /**
     * Batas waktu inaktivitas dalam detik (3 menit = 180 detik).
     */
    protected int $timeout = 180;

    public function handle(Request $request, Closure $next): Response
    {
        $lastActivity = session('skms_last_activity');

        // Jika sudah melewati batas waktu inaktivitas, regenerasi sesi
        if ($lastActivity && (time() - $lastActivity) > $this->timeout) {
            session()->forget(['student_nrp', 'student_name', 'helpful_faqs', 'skms_last_activity']);
            session()->regenerate();
        }

        // Perbarui timestamp aktivitas terakhir
        session(['skms_last_activity' => time()]);

        return $next($request);
    }
}
