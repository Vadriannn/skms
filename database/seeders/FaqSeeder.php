<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil user staf (Data Entry)
        $dataEntry = User::where('role', 'data_entry')->first() ?? User::first();

        // 2. Buat Kategori
        $kategoriList = [
            [
                'nama' => 'Akademik & Kurikulum',
                'slug' => 'akademik-kurikulum',
            ],
            [
                'nama' => 'Registrasi & KRS',
                'slug' => 'registrasi-krs',
            ],
            [
                'nama' => 'Keuangan & SPP',
                'slug' => 'keuangan-spp',
            ],
            [
                'nama' => 'Cuti & Kelulusan',
                'slug' => 'cuti-kelulusan',
            ],
        ];

        $categories = [];
        foreach ($kategoriList as $k) {
            $categories[$k['slug']] = Category::firstOrCreate(['slug' => $k['slug']], $k);
        }

        // 3. Buat Data FAQ Awal (Data Pembanding)
        $faqs = [
            [
                'pertanyaan' => 'Bagaimana prosedur pengajuan cuti kuliah atau istirahat studi?',
                'jawaban' => '<p>Mahasiswa yang ingin mengajukan cuti akademik harus memenuhi syarat:</p><ul><li>Telah menempuh minimal 2 semester.</li><li>Bebas tanggungan administrasi keuangan (SPP).</li><li>Mengisi formulir cuti di DAA paling lambat 2 minggu sebelum perkuliahan dimulai.</li></ul>',
                'status' => 'published',
                'views' => 45,
                'category_slug' => 'cuti-kelulusan',
            ],
            [
                'pertanyaan' => 'Berapa batas waktu dan cara pembayaran SPP per semester?',
                'jawaban' => '<p>Pembayaran SPP dilakukan melalui virtual account bank mitra universitas. Batas akhir pembayaran adalah H-7 sebelum masa perwalian KRS semester bersangkutan.</p>',
                'status' => 'published',
                'views' => 120,
                'category_slug' => 'keuangan-spp',
            ],
            [
                'pertanyaan' => 'Bagaimana cara mengurus revisi atau keterlambatan pengisian KRS?',
                'jawaban' => '<p>Bagi mahasiswa yang terlambat atau ingin merevisi KRS:</p><ol><li>Temui Dosen Wali untuk persetujuan pembukaan kunci perwalian.</li><li>Lakukan perubahan rencana studi di portal akademik.</li><li>Minta persetujuan akhir Dosen Wali sebelum masa revisi berakhir.</li></ol>',
                'status' => 'published',
                'views' => 88,
                'category_slug' => 'registrasi-krs',
            ],
            [
                'pertanyaan' => 'Apa saja syarat pendaftaran Kerja Praktik (KP) bagi mahasiswa SI?',
                'jawaban' => '<p>Syarat mengikuti Kerja Praktik (KP):</p><ul><li>Telah lulus minimal 100 SKS.</li><li>Indeks Prestasi Kumulatif (IPK) minimal 2.00.</li><li>Telah lulus mata kuliah prasyarat KP.</li><li>Mengajukan surat pengantar dari Program Studi ke instansi tujuan.</li></ul>',
                'status' => 'published',
                'views' => 64,
                'category_slug' => 'akademik-kurikulum',
            ],
            [
                'pertanyaan' => 'Bagaimana syarat dan alur pendaftaran wisuda periode gasal?',
                'jawaban' => '<p>Mahasiswa yang telah dinyatakan lulus yudisium wajib mendaftar wisuda melalui portal universitas dengan mengunggah naskah skripsi final, bebas perpustakaan, dan bukti pembayaran toga.</p>',
                'status' => 'published',
                'views' => 30,
                'category_slug' => 'cuti-kelulusan',
            ],
        ];

        foreach ($faqs as $item) {
            $catSlug = $item['category_slug'];
            unset($item['category_slug']);

            $item['user_id'] = $dataEntry->id;

            $faq = Faq::create($item);

            // Hubungkan ke pivot category_faq
            if (isset($categories[$catSlug])) {
                $faq->categories()->attach($categories[$catSlug]->id);
            }
        }
    }
}
