<?php

namespace App\Services;

use App\Models\Faq;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\Stemmer\StemmerInterface;

/**
 * Service untuk Mendeteksi Kemiripan Pertanyaan Menggunakan Algoritma
 * TF-IDF (Term Frequency - Inverse Document Frequency) dan Cosine Similarity,
 * serta Terintegrasi dengan Stemmer Bahasa Indonesia (Sastrawi).
 */
class TfIdfService
{
    /**
     * Mesin Stemmer Sastrawi untuk ekstraksi kata dasar bahasa Indonesia.
     */
    protected ?StemmerInterface $stemmer = null;

    /**
     * Kamus Stopwords (Kata Sambung / Kata Umum) Bahasa Indonesia
     * yang dibuang karena tidak memiliki informasi pembeda yang unik.
     */
    protected array $stopwords = [
        'yang', 'di', 'ke', 'dari', 'pada', 'dalam', 'untuk', 'dengan', 'dan', 'atau',
        'ini', 'itu', 'juga', 'sudah', 'saya', 'anda', 'apakah', 'bagaimana', 'apa',
        'siapa', 'kapan', 'dimana', 'mengapa', 'kenapa', 'bisa', 'ada', 'tidak', 'hal',
        'para', 'bagi', 'oleh', 'saja', 'terkait', 'tentang', 'adalah', 'sebagai',
        'agar', 'supaya', 'akan', 'telah', 'bila', 'jika', 'kalau', 'mau', 'ingin',
        'halo', 'admin', 'kak', 'pak', 'bu', 'mohon', 'tolong', 'info', 'tanya',
        'tersebut', 'setiap', 'secara', 'harus'
    ];

    public function __construct()
    {
        // Inisialisasi library Stemmer Sastrawi
        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
    }

    // =========================================================================
    // TAHAP 1: PREPROCESSING & NORMALISASI TEKS
    // =========================================================================

    /**
     * Membersihkan teks mentah menjadi array kumpulan kata dasar bersih.
     *
     * @param string $text
     * @return array
     */
    public function tokenize(string $text): array
    {
        // [Langkah 1.1] Case Folding: Mengubah seluruh huruf menjadi huruf kecil
        $text = mb_strtolower($text, 'UTF-8');

        // [Langkah 1.2] Pembersihan: Menghapus tag HTML (jika ada)
        $text = strip_tags($text);

        // [Langkah 1.3] Pembersihan: Menghapus tanda baca, angka, dan simbol (hanya menyisakan huruf)
        $text = preg_replace('/[^\p{L}\s]/u', ' ', $text);

        // [Langkah 1.4] Stemming Sastrawi: Mengubah kata berimbuhan ke kata dasar
        // Contoh: 'mengajukan' & 'pengajuan' -> 'aju'
        if ($this->stemmer) {
            $text = $this->stemmer->stem($text);
        }

        // [Langkah 1.5] Tokenisasi: Memecah kalimat menjadi kumpulan kata tunggal
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        // [Langkah 1.6] Stopwords Removal: Membuang kata sambung yang tidak bermakna
        $cleanWords = array_filter($words, function ($word) {
            return !in_array($word, $this->stopwords) && mb_strlen($word) > 1;
        });

        return array_values($cleanWords);
    }

    // =========================================================================
    // TAHAP 2: PEMBOBOTAN TF-IDF
    // =========================================================================

    /**
     * Menghitung nilai Inverse Document Frequency (IDF) untuk setiap kata unik.
     * Kata yang langka/spesifik mendapat bobot besar, kata yang sering muncul mendapat bobot kecil.
     *
     * Rumus: IDF(t) = ln((total_dokumen + 1) / (df + 1)) + 1
     *
     * @param array $allDocuments Kumpulan seluruh dokumen (pertanyaan baru + FAQ lama)
     * @return array Peta nilai IDF [kata => nilai_idf]
     */
    public function calculateIdf(array $allDocuments): array
    {
        $totalDocs = count($allDocuments);
        $df = []; // Document Frequency: Berapa banyak dokumen yang mengandung kata X

        foreach ($allDocuments as $tokens) {
            $uniqueTokensInDoc = array_unique($tokens);
            foreach ($uniqueTokensInDoc as $token) {
                $df[$token] = ($df[$token] ?? 0) + 1;
            }
        }

        $idf = [];
        foreach ($df as $term => $count) {
            $idf[$term] = log(($totalDocs + 1) / ($count + 1)) + 1.0;
        }

        return $idf;
    }

    /**
     * Membentuk vektor bobot TF-IDF untuk satu dokumen berdasarkan kosa katanya.
     *
     * Rumus: Bobot = Term Frequency (TF) * Inverse Document Frequency (IDF)
     *
     * @param array $tokens Kumpulan kata pada dokumen tersebut
     * @param array $idf Peta nilai IDF seluruh korpus
     * @return array Vektor bobot [kata => bobot_tfidf]
     */
    public function createTfIdfVector(array $tokens, array $idf): array
    {
        $tf = array_count_values($tokens);
        $vector = [];

        foreach ($tf as $term => $freq) {
            $vector[$term] = $freq * ($idf[$term] ?? 1.0);
        }

        return $vector;
    }

    // =========================================================================
    // TAHAP 3: PERHITUNGAN KOSINUS KEMIRIPAN (COSINE SIMILARITY)
    // =========================================================================

    /**
     * Mengukur sudut kemiripan antara dua vektor dokumen.
     *
     * Rumus: Cosine(A, B) = (A . B) / (||A|| * ||B||)
     *
     * @param array $vecA Vektor dokumen A
     * @param array $vecB Vektor dokumen B
     * @return float Nilai kemiripan antara 0.00 (berbeda total) hingga 1.00 (identik)
     */
    public function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        // [Langkah 3.1] Menghitung Dot Product (Perkalian silang kata-kata yang sama)
        foreach ($vecA as $term => $valA) {
            $valB = $vecB[$term] ?? 0.0;
            $dotProduct += $valA * $valB;
            $normA += $valA * $valA;
        }

        // [Langkah 3.2] Menghitung Panjang Vektor B
        foreach ($vecB as $term => $valB) {
            $normB += $valB * $valB;
        }

        // Jika salah satu vektor kosong/nol, kemiripan = 0
        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        // [Langkah 3.3] Menghitung Nilai Kosinus Akhir
        return (float) ($dotProduct / (sqrt($normA) * sqrt($normB)));
    }

    // =========================================================================
    // TAHAP 4: ALUR UTAMA DETEKSI DUPLIKASI (CHECK SIMILARITY)
    // =========================================================================

    /**
     * Membandingkan pertanyaan input baru dengan seluruh FAQ yang ada di database.
     *
     * @param string $newQuestion Pertanyaan baru yang diketik Data Entry
     * @param float $threshold Batas ambang skor duplikat (default: 0.60 atau 60%)
     * @param int|null $excludeId ID FAQ yang diabaikan (jika sedang mengedit)
     * @return array Hasil evaluasi duplikasi dan daftar kandidat teratas
     */
    public function checkSimilarity(string $newQuestion, float $threshold = 0.60, ?int $excludeId = null): array
    {
        // ---------------------------------------------------------------------
        // [Langkah 4.1] Ambil Seluruh Data FAQ Lama dari Database
        // ---------------------------------------------------------------------
        $query = Faq::query();
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $existingFaqs = $query->get();

        if ($existingFaqs->isEmpty()) {
            return $this->formatEmptyResponse();
        }

        // ---------------------------------------------------------------------
        // [Langkah 4.2] Lakukan Preprocessing (Tahap 1) ke Seluruh Dokumen
        // ---------------------------------------------------------------------
        $queryTokens = $this->tokenize($newQuestion);

        if (empty($queryTokens)) {
            return $this->formatEmptyResponse();
        }

        $documents = [];
        foreach ($existingFaqs as $faq) {
            $documents[$faq->id] = $this->tokenize($faq->pertanyaan);
        }

        // ---------------------------------------------------------------------
        // [Langkah 4.3] Hitung Nilai IDF Seluruh Korpus (Tahap 2)
        // ---------------------------------------------------------------------
        $allDocs = array_merge([0 => $queryTokens], $documents);
        $idf = $this->calculateIdf($allDocs);

        // Vektor TF-IDF untuk pertanyaan baru
        $queryVector = $this->createTfIdfVector($queryTokens, $idf);

        // ---------------------------------------------------------------------
        // [Langkah 4.4] Bandingkan dengan Setiap FAQ Lama via Cosine Similarity (Tahap 3)
        // ---------------------------------------------------------------------
        $candidates = [];
        $highestScore = 0.0;
        $mostSimilarFaq = null;

        foreach ($existingFaqs as $faq) {
            $docTokens = $documents[$faq->id] ?? [];
            if (empty($docTokens)) {
                continue;
            }

            // Vektor TF-IDF untuk FAQ lama pembanding
            $docVector = $this->createTfIdfVector($docTokens, $idf);

            // Hitung skor kemiripan kosinus
            $score = $this->cosineSimilarity($queryVector, $docVector);

            if ($score > 0.0) {
                $candidates[] = [
                    'faq'        => $faq,
                    'score'      => round($score, 4),
                    'percentage' => round($score * 100, 1) . '%',
                ];

                if ($score > $highestScore) {
                    $highestScore = $score;
                    $mostSimilarFaq = $faq;
                }
            }
        }

        // ---------------------------------------------------------------------
        // [Langkah 4.5] Urutkan Hasil dari Skor Tertinggi ke Terendah
        // ---------------------------------------------------------------------
        usort($candidates, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // ---------------------------------------------------------------------
        // [Langkah 4.6] Evaluasi Batas Ambang (Threshold) & Kembalikan Respons
        // ---------------------------------------------------------------------
        return [
            'highest_score' => round($highestScore, 4),
            'percentage'    => round($highestScore * 100, 1) . '%',
            'is_duplicate'  => $highestScore >= $threshold,
            'similar_faq'   => $mostSimilarFaq,
            'candidates'    => array_slice($candidates, 0, 5), // Ambil 5 teratas
        ];
    }

    /**
     * Format struktur respons kosong jika tidak ada data atau input kosong.
     */
    protected function formatEmptyResponse(): array
    {
        return [
            'highest_score' => 0.0,
            'percentage'    => '0%',
            'is_duplicate'  => false,
            'similar_faq'   => null,
            'candidates'    => [],
        ];
    }
}
