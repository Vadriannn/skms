<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPengguna = User::count();
        $totalPertanyaan = Faq::count();
        $menungguValidasi = Faq::where('status', 'pending')->count();
        $dipublikasikan = Faq::where('status', 'published')->count();

        $aktivitasTerbaru = ActivityLog::with('user')->latest()->take(5)->get();

        if ($aktivitasTerbaru->isEmpty()) {
            ActivityLog::create([
                'aktivitas'  => 'Admin menambahkan kategori baru',
                'tipe'       => 'kategori',
                'created_at' => now()->subDay(),
            ]);

            $aktivitasTerbaru = ActivityLog::latest()->take(5)->get();
        }

        return view('dashboard.admin', compact(
            'totalPengguna',
            'totalPertanyaan',
            'menungguValidasi',
            'dipublikasikan',
            'aktivitasTerbaru'
        ));
    }
}
