<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman daftar kategori dan form tambah kategori.
     */
    public function index()
    {
        // Ambil seluruh kategori beserta jumlah FAQ terkait, urutkan dari yang terbaru
        $categories = Category::withCount('faqs')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:100|unique:categories,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max'      => 'Nama kategori maksimal 100 karakter.',
            'nama.unique'   => 'Kategori dengan nama ini sudah ada di database.',
        ]);

        // 2. Generate Slug Otomatis
        $slug = Str::slug($request->nama);

        // Jaga-jaga jika slug kembar
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // 3. Simpan ke Database
        Category::create([
            'nama' => $request->nama,
            'slug' => $slug,
        ]);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$request->nama}\" berhasil ditambahkan!");
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        $nama = $category->nama;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}
