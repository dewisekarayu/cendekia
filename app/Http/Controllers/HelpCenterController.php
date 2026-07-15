<?php

namespace App\Http\Controllers;

use App\Models\HelpTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HelpCenterController extends Controller
{
    /**
     * Kategori yang dipakai di FAQ maupun form tiket.
     */


    /**
     * Data FAQ dummy, dikelompokkan per kategori.
     * Disesuaikan dengan LMS Cendekia (masalah real yang sering terjadi)
     * Nanti tinggal diganti dengan query ke model Faq kalau sudah ada tabelnya.
     */
    protected function dummyFaqs(): array
    {
        return [
            'akun' => [
                [
                    'id' => 1,
                    'category' => 'akun',
                    'question' => 'Akun saya tidak terverifikasi, berapa lama prosesnya?',
                    'answer' => 'Proses verifikasi akun biasanya memakan waktu 1-2 hari kerja. Admin akan mengecek data akademik Anda terlebih dahulu. Pastikan data yang Anda daftarkan sudah sesuai dengan data di sistem akademik kampus.',
                ],
                [
                    'id' => 2,
                    'category' => 'akun',
                    'question' => 'Bagaimana cara reset password?',
                    'answer' => 'Klik tombol "Lupa Password" di halaman login. Masukkan email terdaftar Anda, kemudian cek email untuk tautan reset password. Jika tidak menerima email, periksa folder spam atau hubungi admin.',
                ],
                [
                    'id' => 3,
                    'category' => 'akun',
                    'question' => 'Mengapa profil saya tidak bisa diedit?',
                    'answer' => 'Beberapa field seperti NIM/NPP bersifat read-only karena sudah tersinkronisasi dari sistem akademik. Hubungi bagian akademik jika ingin mengubah data tersebut.',
                ],
                [
                    'id' => 4,
                    'category' => 'akun',
                    'question' => 'Upload avatar/foto profil selalu error, apa penyebabnya?',
                    'answer' => 'Pastikan ukuran file tidak lebih dari 2MB dan format yang didukung (JPG, PNG). Coba refresh halaman dan upload ulang. Jika tetap error, coba browser lain atau hapus cache.',
                ],
            ],
            'absensi' => [
                [
                    'id' => 5,
                    'category' => 'absensi',
                    'question' => 'Halaman absensi tidak muncul atau error "tidak diizinkan"?',
                    'answer' => 'Ini terjadi jika Anda belum terdaftar di kelas tersebut atau dosen belum membuka absensi. Pastikan Anda sudah mendaftar kelas melalui menu Kelas. Hubungi dosen jika masih error.',
                ],
                [
                    'id' => 6,
                    'category' => 'absensi',
                    'question' => 'Absensi sudah saya isi tapi tidak tersimpan, kenapa?',
                    'answer' => 'Pastikan koneksi internet stabil. Tunggu tombol "Simpan" berubah warna (berhasil) sebelum keluar halaman. Jika masih gagal, refresh halaman dan coba lagi.',
                ],
                [
                    'id' => 7,
                    'category' => 'absensi',
                    'question' => 'Absensi saya tiba-tiba hilang atau berubah dari hadir jadi izin?',
                    'answer' => 'Kemungkinan dosen sedang melakukan editing. Tunggu beberapa saat atau refresh halaman. Jika masalah persisten, hubungi dosen atau admin untuk pengecekan.',
                ],
                [
                    'id' => 8,
                    'category' => 'absensi',
                    'question' => 'Tombol absensi tidak bisa diklik saat sesi kelas berlangsung, kenapa?',
                    'answer' => 'Pastikan dosen sudah membuka sesi absensi untuk pertemuan tersebut, dan coba refresh halaman. Jika tombol tetap tidak aktif, coba browser lain atau minta dosen untuk mengecek status sesi.',
                ],
            ],
            'nilai' => [
                [
                    'id' => 9,
                    'category' => 'nilai',
                    'question' => 'Nilai saya belum keluar atau gradeBook masih kosong?',
                    'answer' => 'Dosen mungkin masih belum menginput nilai. Cek kalender akademik untuk deadline pengumpulan nilai. Jika sudah lewat deadline, hubungi dosen untuk pengecekan.',
                ],
                [
                    'id' => 10,
                    'category' => 'nilai',
                    'question' => 'Nilai yang ditampilkan berbeda dengan yang diberitahu dosen?',
                    'answer' => 'Kemungkinan nilai masih dalam proses pembaruan atau ada kesalahan input. Tunggu beberapa jam kemudian refresh halaman. Jika masih berbeda, hubungi dosen langsung.',
                ],
                [
                    'id' => 11,
                    'category' => 'nilai',
                    'question' => 'Nilai akhir saya tidak muncul, bagaimana cara mengeceknya?',
                    'answer' => 'Nilai akhir biasanya muncul setelah semua komponen penilaian (UTS, UAS, tugas) selesai dinilai. Cek kembali minggu berikutnya. Hubungi admin jika sudah saatnya tapi masih kosong.',
                ],
            ],
            'tugas' => [
                [
                    'id' => 12,
                    'category' => 'tugas',
                    'question' => 'Tidak bisa upload tugas, selalu gagal dengan error?',
                    'answer' => 'Pastikan file tidak melampaui ukuran maksimal yang ditentukan dosen (biasanya 10-20MB). Coba format file lain (PDF, DOC). Jika error persisten, coba browser berbeda atau hubungi admin.',
                ],
                [
                    'id' => 13,
                    'category' => 'tugas',
                    'question' => 'Tugas sudah saya upload tapi masih dianggap belum dikumpulkan?',
                    'answer' => 'Tunggu hingga status berubah menjadi "Sudah Dikumpulkan". Jika masih "Belum Dikumpulkan" setelah 5 menit, refresh halaman. Hubungi dosen jika bug persisten.',
                ],
                [
                    'id' => 14,
                    'category' => 'tugas',
                    'question' => 'Batas waktu pengumpulan tugas sudah lewat, masih bisa upload?',
                    'answer' => 'Sistem akan mengunci pengumpulan setelah batas waktu. Hubungi dosen untuk minta perpanjangan atau upload manual melalui email jika diperbolehkan.',
                ],
                [
                    'id' => 15,
                    'category' => 'tugas',
                    'question' => 'Saya tidak melihat tugas baru dari dosen?',
                    'answer' => 'Refresh halaman atau logout-login kembali. Tugas yang baru diupload kadang perlu beberapa saat untuk muncul. Jika masih tidak terlihat, minta dosen untuk memastikan tugas sudah di-publish.',
                ],
            ],
            'kelas' => [
                [
                    'id' => 16,
                    'category' => 'kelas',
                    'question' => 'Tidak bisa akses kelas tertentu, padahal sudah daftar?',
                    'answer' => 'Coba logout dan login kembali. Jika masih tidak bisa, mungkin pendaftaran belum disetujui admin. Hubungi admin atau bagian akademik untuk konfirmasi.',
                ],
                [
                    'id' => 17,
                    'category' => 'kelas',
                    'question' => 'Materi kelas tidak muncul, hanya terlihat pengumuman?',
                    'answer' => 'Dosen mungkin belum upload materi. Cek tanggal posting pengumuman. Jika dosen sudah harusnya upload tapi masih kosong, hubungi dosen untuk konfirmasi.',
                ],
                [
                    'id' => 18,
                    'category' => 'kelas',
                    'question' => 'Video/PDF dari dosen tidak bisa dibuka?',
                    'answer' => 'Cek koneksi internet Anda. Coba buka dengan browser lain. Jika formatnya PDF/MP4, mungkin file terlalu besar atau corrupt. Hubungi dosen untuk re-upload.',
                ],
            ],
            'teknis' => [
                [
                    'id' => 19,
                    'category' => 'teknis',
                    'question' => 'Halaman blank atau tidak muncul apa-apa, hanya loading?',
                    'answer' => 'Coba refresh halaman (F5 atau Ctrl+Shift+R untuk hard refresh). Jika tetap blank, coba browser lain atau device berbeda. Jika masalah berlanjut di semua device, hubungi admin.',
                ],
                [
                    'id' => 20,
                    'category' => 'teknis',
                    'question' => 'Tombol tidak bisa diklik atau responsive?',
                    'answer' => 'Update browser Anda ke versi terbaru. Hapus cache dan cookies browser. Coba di browser incognito. Jika masih bermasalah, hubungi admin dengan screenshot error.',
                ],
                [
                    'id' => 21,
                    'category' => 'teknis',
                    'question' => 'Aplikasi selalu logout otomatis, bagaimana?',
                    'answer' => 'Ini terjadi jika sesi sudah expired (biasanya 24 jam). Login kembali dan pastikan tidak membuka tab lain dengan akun yang sama. Hubungi admin jika auto-logout terjadi dalam waktu singkat.',
                ],
                [
                    'id' => 22,
                    'category' => 'teknis',
                    'question' => 'Chat/forum tidak bisa mengirim pesan atau loading terus?',
                    'answer' => 'Periksa koneksi internet Anda. Coba refresh halaman atau tutup chat kemudian buka lagi. Jika masih error, dosen mungkin sudah menutup forum. Hubungi dosen untuk konfirmasi.',
                ],
            ],
            'lainnya' => [
                [
                    'id' => 25,
                    'category' => 'lainnya',
                    'question' => 'Bagaimana cara menghubungi admin atau support?',
                    'answer' => 'Anda bisa mengirim pertanyaan melalui form "Hubungi Tim Support" di bagian bawah halaman ini. Tim kami akan merespons melalui email dalam waktu 1-2 jam kerja. Atau langsung visit kantor Bagian Akademik.',
                ],
                [
                    'id' => 26,
                    'category' => 'lainnya',
                    'question' => 'Notifikasi dari sistem tidak masuk ke email saya?',
                    'answer' => 'Pastikan email Anda sudah ter-verifikasi di profil. Cek folder spam/junk juga. Jika masih tidak menerima, hubungi admin untuk check status email di database.',
                ],
            ],
        ];
    }

    /**
     * Halaman FAQ premium dengan layout lengkap
     */
    public function faqPage(Request $request)
    {
        $faqs = $this->dummyFaqs();

        return view('help-center.faq', [
            'faqs'        => collect($faqs),
            'adminOnline' => false,
        ]);
    }

    /**
     * Halaman utama pusat bantuan.
     */
    public function index(Request $request)
    {
        $faqs = $this->dummyFaqs();

        return view('help-center.index', [
            'faqs'        => collect($faqs),
            'adminOnline' => false,
        ]);
    }

    /**
     * Endpoint pencarian FAQ (dipanggil via fetch/AJAX).
     */
    public function searchFaq(Request $request)
    {
        $query    = trim((string) $request->query('q', ''));
        $category = $request->query('category');

        $faqs = collect($this->dummyFaqs())->flatten(1);

        if ($category) {
            $faqs = $faqs->where('category', $category);
        }

        if ($query !== '') {
            $needle = Str::lower($query);
            $faqs = $faqs->filter(function ($faq) use ($needle) {
                return Str::contains(Str::lower($faq['question']), $needle)
                    || Str::contains(Str::lower($faq['answer']), $needle);
            });
        }

        return response()->json([
            'results' => $faqs->values(),
        ]);
    }

    /**
     * Detail FAQ individual.
     */
    public function faqDetail(Request $request, $faqId = null)
    {
        $faqId = $faqId ?? $request->route('faq');
        $faqs = collect($this->dummyFaqs())->flatten(1);
        $faq = $faqs->firstWhere('id', $faqId);

        if (!$faq) {
            abort(404, 'FAQ tidak ditemukan');
        }

        return view('help-center.faq-detail', [
            'faq' => (object)$faq,
            'relatedFaqs' => $faqs->where('category', $faq['category'])
                ->where('id', '!=', $faq['id'])
                ->take(3)
                ->values(),
        ]);
    }

    /**
     * Submit feedback untuk FAQ (membantu/tidak membantu).
     */
    public function faqFeedback(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|integer',
            'helpful' => 'required|in:yes,no',
        ]);

        // Dummy: simpan ke session atau database
        // Untuk sekarang cukup return success
        return response()->json([
            'message' => 'Terima kasih atas feedback Anda!',
        ]);
    }

    /**
     * Halaman panduan/tutorial.
     */
    public function guides(Request $request)
    {
        $guides = [
            [
                'id' => 1,
                'title' => 'Panduan Cara Daftar dan Login',
                'slug' => 'panduan-daftar-login',
                'category' => 'akun',
                'image' => 'https://via.placeholder.com/400x250',
                'excerpt' => 'Pelajari cara membuat akun dan login ke portal Cendekia LMS',
                'readTime' => 5,
            ],
            [
                'id' => 2,
                'title' => 'Cara Mengisi Absensi Kelas',
                'slug' => 'panduan-absensi-kelas',
                'category' => 'absensi',
                'image' => 'https://via.placeholder.com/400x250',
                'excerpt' => 'Tutorial cara mengisi kehadiran di setiap sesi kelas',
                'readTime' => 3,
            ],
            [
                'id' => 3,
                'title' => 'Cara Mengumpulkan Tugas Online',
                'slug' => 'panduan-kumpul-tugas',
                'category' => 'tugas',
                'image' => 'https://via.placeholder.com/400x250',
                'excerpt' => 'Panduan lengkap mengumpulkan tugas melalui portal',
                'readTime' => 4,
            ],
        ];

        return view('help-center.guides', [
            'guides' => $guides,
        ]);
    }

    /**
     * Detail panduan individual.
     */
    public function guideDetail(Request $request, $id = null)
    {
        $id = $id ?? $request->route('id');
        
        // Dummy guide content
        $guides = [
            1 => [
                'id' => 1,
                'title' => 'Panduan Cara Daftar dan Login',
                'category' => 'akun',
                'content' => '<h2>Langkah 1: Kunjungi Portal Cendekia</h2>
<p>Buka browser Anda dan akses portal Cendekia di [URL portal]. Klik tombol "Daftar" di halaman awal.</p>

<h2>Langkah 2: Isi Formulir Pendaftaran</h2>
<p>Lengkapi semua data yang diminta:</p>
<ul>
  <li>NIM/NPP (dari sistem akademik)</li>
  <li>Email</li>
  <li>Password (minimal 8 karakter)</li>
  <li>Nama lengkap sesuai KTP</li>
  <li>Nomor telepon</li>
</ul>

<h2>Langkah 3: Verifikasi Email</h2>
<p>Cek email yang terdaftar, buka link verifikasi dari Cendekia. Akun Anda akan aktif setelah diverifikasi admin.</p>

<h2>Langkah 4: Login</h2>
<p>Gunakan email dan password yang sudah Anda daftarkan untuk login. Anda akan diarahkan ke dashboard utama sesuai role (mahasiswa/dosen/admin).</p>',
                'readTime' => 5,
            ],
            2 => [
                'id' => 2,
                'title' => 'Cara Mengisi Absensi Kelas',
                'category' => 'absensi',
                'content' => '<h2>Cara Mengisi Absensi</h2>
<p>Setiap sesi kelas di Cendekia memiliki halaman absensi tersendiri yang dibuka oleh dosen saat kelas berlangsung.</p>

<h2>Langkah 1: Buka Menu Absensi</h2>
<p>Masuk ke kelas yang Anda ikuti, kemudian pilih tab "Absensi".</p>

<h2>Langkah 2: Pastikan Sesi Sudah Dibuka Dosen</h2>
<p>Tombol absensi hanya aktif jika dosen sudah membuka sesi untuk pertemuan tersebut. Jika belum aktif, tunggu beberapa saat atau hubungi dosen.</p>

<h2>Langkah 3: Pilih Status Kehadiran</h2>
<p>Klik tombol absensi, lalu pilih status: Hadir, Izin, atau Sakit. Jika ada keterangan khusus, isikan di kolom catatan.</p>

<h2>Langkah 4: Simpan</h2>
<p>Klik tombol "Simpan" dan tunggu konfirmasi bahwa absensi Anda berhasil tersimpan.</p>',
                'readTime' => 3,
            ],
            3 => [
                'id' => 3,
                'title' => 'Cara Mengumpulkan Tugas Online',
                'category' => 'tugas',
                'content' => '<h2>Panduan Mengumpulkan Tugas</h2>
<p>Follow langkah-langkah berikut untuk mengumpulkan tugas melalui portal.</p>

<h2>Langkah 1: Buka Kelas dan Menu Tugas</h2>
<p>Masuk ke kelas yang relevan, lalu klik tab "Tugas".</p>

<h2>Langkah 2: Pilih Tugas yang Akan Dikumpulkan</h2>
<p>Lihat daftar tugas yang belum dikumpulkan dan klik pada tugas yang ingin Anda kerjakan.</p>

<h2>Langkah 3: Upload File</h2>
<p>Klik tombol "Upload File" dan pilih file dari komputer Anda. Pastikan format dan ukuran sesuai dengan requirements dosen (biasanya PDF, DOC, DOCX, max 10-20MB).</p>

<h2>Langkah 4: Verifikasi dan Submit</h2>
<p>Review file yang akan Anda upload. Jika sudah benar, klik "Kumpulkan" untuk submit tugas.</p>

<h2>Langkah 5: Konfirmasi</h2>
<p>Tunggu status berubah menjadi "Sudah Dikumpulkan". Anda sudah bisa melihat tanggal submit di halaman tugas.</p>',
                'readTime' => 4,
            ],
        ];

        $guide = $guides[$id] ?? null;

        if (!$guide) {
            abort(404, 'Panduan tidak ditemukan');
        }

        return view('help-center.guide-detail', [
            'guide' => $guide,
        ]);
    }

    /**
     * Simpan tiket bantuan baru dari form kontak.
     */
    public function storeTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'subject'    => ['required', 'string', 'max:255'],
            'category'   => ['required', 'string'],
            'message'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('help-tickets', 'public');
        }

        $ticket = HelpTicket::create([
            'user_id'         => auth()->id(),
            'name'            => $data['name'],
            'email'           => $data['email'],
            'subject'         => $data['subject'],
            'category'        => $data['category'],
            'message'         => $data['message'],
            'attachment_path' => $attachmentPath,
            'status'          => 'open',
        ]);

        return response()->json([
            'message' => 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda melalui email.',
            'ticket'  => $ticket,
        ]);
    }
}