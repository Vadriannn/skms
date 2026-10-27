<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil user staf (Data Entry - Bayu)
        $dataEntry = User::where('role', 'data_entry')->first() ?? User::first();

        // 2. Buat Kategori (Mencakup Kemahasiswaan & Akademik sesuai mockup)
        $kategoriList = [
            [
                'nama' => 'Kemahasiswaan',
                'slug' => 'kemahasiswaan',
            ],
            [
                'nama' => 'Akademik',
                'slug' => 'akademik',
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

        // Kosongkan relasi FAQ lama agar data seimbang dan akurat
        DB::table('category_faq')->delete();
        Faq::query()->delete();

        // 3. Buat Data FAQ (Mendukung 1 FAQ Banyak Kategori)
        $faqs = [
            // Item 1, 2, 3 Sesuai Persis Tampilan Mockup
            [
                'pertanyaan'     => 'Cara pengajuan cuti...',
                'jawaban'        => '<p>Mahasiswa yang ingin mengajukan cuti akademik harus telah menempuh minimal 2 semester dan bebas tanggungan SPP. Formulir diajukan ke DAA paling lambat 2 minggu sebelum perkuliahan.</p>',
                'status'         => 'draft',
                'views'          => 12,
                'category_slugs' => ['kemahasiswaan'], // Single kategori
            ],
            [
                'pertanyaan'     => 'Syarat ikut yudisium...',
                'jawaban'        => '<p>Syarat yudisium meliputi: telah lulus seluruh mata kuliah wajib (min. 144 SKS), IPK minimal 2.00, tidak ada nilai E, dan naskah skripsi telah disetujui pembimbing serta penguji.</p>',
                'status'         => 'pending',
                'views'          => 28,
                'category_slugs' => ['akademik'], // Single kategori
            ],
            [
                'pertanyaan'     => 'KRS bisa diubah? ...',
                'jawaban'        => '<p>Perubahan KRS (tambah/batal matakuliah) dapat dilakukan selama periode revisi KRS melalui persetujuan Dosen Wali secara online di portal akademik.</p>',
                'status'         => 'published',
                'views'          => 95,
                'category_slugs' => ['akademik'], // Single kategori
            ],

            // CONTOH 1 FAQ DENGAN BANYAK KATEGORI (Multi-Category FAQs)
            [
                'pertanyaan'     => 'Prosedur beasiswa prestasi dan keringanan SPP mahasiswa berprestasi',
                'jawaban'        => '<p>Mahasiswa berprestasi di bidang akademik maupun minat bakat dapat mengajukan pembebasan SPP melalui Biro Kemahasiswaan dengan melampirkan sertifikat resmi dan transkrip nilai.</p>',
                'status'         => 'draft',
                'views'          => 4,
                'category_slugs' => ['kemahasiswaan', 'keuangan-spp'], // 2 Kategori sekaligus
            ],
            [
                'pertanyaan'     => 'Batas waktu revisi KRS, perpanjangan studi, dan cuti akademik',
                'jawaban'        => '<p>Segala urusan perubahan rencana studi, permohonan istirahat kuliah (cuti), dan registrasi ulang semester wajib diselesaikan sebelum batas waktu kalender akademik berakhir.</p>',
                'status'         => 'pending',
                'views'          => 18,
                'category_slugs' => ['akademik', 'registrasi-krs', 'cuti-kelulusan'], // 3 Kategori sekaligus!
            ],
            [
                'pertanyaan'     => 'Konversi kegiatan MBKM Magang & Studi Independen ke mata kuliah',
                'jawaban'        => '<p>Mahasiswa yang lolos program MSIB dapat mengonversi hingga 20 SKS dengan persetujuan Kaprodi dan Dosen Pembimbing Lapangan.</p>',
                'status'         => 'published',
                'views'          => 64,
                'category_slugs' => ['akademik', 'kemahasiswaan'], // 2 Kategori
            ],

            // Sisa Draft untuk melengkapi Total Draft = 8
            [
                'pertanyaan'     => 'Berapa batas maksimal SKS per semester berdasarkan indeks prestasi?',
                'jawaban'        => '<p>IPK di atas 3.00 berhak mengambil beban maksimal 24 SKS, sedangkan IPK 2.50-2.99 dapat mengambil 21 SKS.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['akademik', 'registrasi-krs'], // 2 Kategori
            ],
            [
                'pertanyaan'     => 'Bagaimana mekanisme penggantian dosen pembimbing skripsi?',
                'jawaban'        => '<p>Penggantian dosen pembimbing dapat diajukan apabila terdapat kendala komunikasi atau perpindahan fokus penelitian dengan persetujuan Kaprodi.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['akademik', 'cuti-kelulusan'],
            ],
            [
                'pertanyaan'     => 'Di mana mahasiswa dapat mengajukan surat keterangan aktif kuliah?',
                'jawaban'        => '<p>Surat keterangan aktif kuliah diajukan melalui portal DAA menu Layanan Mahasiswa.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['kemahasiswaan'],
            ],
            [
                'pertanyaan'     => 'Apakah ada mekanisme cicilan pembayaran uang kuliah semester?',
                'jawaban'        => '<p>Dispensasi cicilan SPP dapat diajukan ke Bagian Keuangan dengan menyertakan surat pernyataan orang tua.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['keuangan-spp'],
            ],
            [
                'pertanyaan'     => 'Ketentuan ujian susulan bagi mahasiswa yang berhalangan hadir',
                'jawaban'        => '<p>Ujian susulan hanya diselenggarakan bagi mahasiswa sakit atau tugas kedinasan kampus dengan surat keterangan resmi.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['akademik'],
            ],
            [
                'pertanyaan'     => 'Cara mengakses jurnal internasional IEEE dan ScienceDirect kampus',
                'jawaban'        => '<p>Gunakan akun SSO perpustakaan universitas atau jaringan WiFi kampus untuk mengakses jurnal secara gratis.</p>',
                'status'         => 'draft',
                'views'          => 0,
                'category_slugs' => ['akademik', 'kemahasiswaan'],
            ],

            // Sisa Pending untuk melengkapi Total Pending = 5
            [
                'pertanyaan'     => 'Prosedur legalisir ijazah dan transkrip nilai secara online',
                'jawaban'        => '<p>Layanan legalisir daring tersedia pada portal alumni dengan melampirkan berkas scan ijazah asli beresolusi tinggi.</p>',
                'status'         => 'pending',
                'views'          => 8,
                'category_slugs' => ['cuti-kelulusan', 'kemahasiswaan'],
            ],
            [
                'pertanyaan'     => 'Jadwal pengisian kuesioner evaluasi dosen oleh mahasiswa (EDOM)',
                'jawaban'        => '<p>EDOM dibuka 2 minggu sebelum Ujian Akhir Semester (UAS) dan wajib diisi sebelum mencetak kartu ujian.</p>',
                'status'         => 'pending',
                'views'          => 5,
                'category_slugs' => ['akademik'],
            ],
            [
                'pertanyaan'     => 'Penyesuaian UKT bagi mahasiswa yang hanya menyisakan sidang skripsi',
                'jawaban'        => '<p>Mahasiswa yang hanya menyisakan sidang skripsi dapat mengajukan potongan biaya kuliah sebesar 50% ke bagian keuangan.</p>',
                'status'         => 'pending',
                'views'          => 14,
                'category_slugs' => ['keuangan-spp', 'cuti-kelulusan'],
            ],

            // Sisa Published untuk melengkapi data FAQ aktif
            [
                'pertanyaan'     => 'Tata cara pendaftaran wisuda periode gasal universitas',
                'jawaban'        => '<p>Pendaftaran wisuda dilakukan melalui website resmi wisuda dengan mengunggah bukti bebas pustaka dan naskah publikasi.</p>',
                'status'         => 'published',
                'views'          => 140,
                'category_slugs' => ['cuti-kelulusan', 'kemahasiswaan'],
            ],
            [
                'pertanyaan'     => 'Batas waktu pembayaran SPP dan denda keterlambatan',
                'jawaban'        => '<p>Pembayaran SPP dilakukan via virtual account bank mitra universitas H-7 sebelum masa perwalian KRS.</p>',
                'status'         => 'published',
                'views'          => 88,
                'category_slugs' => ['keuangan-spp'],
            ],
        ];

        foreach ($faqs as $item) {
            $categorySlugs = $item['category_slugs'] ?? [];
            unset($item['category_slugs']);

            $item['user_id'] = $dataEntry->id;

            $faq = Faq::create($item);

            // Hubungkan SATU atau BANYAK kategori ke FAQ (Many-to-Many pivot)
            foreach ($categorySlugs as $slug) {
                if (isset($categories[$slug])) {
                    $faq->categories()->attach($categories[$slug]->id);
                }
            }
        }
    }
}
