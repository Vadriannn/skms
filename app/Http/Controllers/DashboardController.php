<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika role data_entry atau request parameter view=data_entry
        if (($user && $user->role === 'data_entry') || $request->query('view') === 'data_entry') {
            return $this->dataEntry();
        }

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

    /**
     * Dashboard khusus peran Data Entry
     */
    public function dataEntry()
    {
        $totalDraft = Faq::where('status', 'draft')->count();
        $menungguValidasi = Faq::where('status', 'pending')->count();
        $categories = \App\Models\Category::orderBy('nama', 'asc')->get();

        return view('dashboard.data_entry', compact(
            'totalDraft',
            'menungguValidasi',
            'categories'
        ));
    }
}
