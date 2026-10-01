<?php

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\KelasPerkuliahan;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use App\Jobs\SendTugasBaru;
use App\Jobs\SendMateriBaru;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('notifications are only sent if user preferences are enabled', function () {
    // Seed role agar user->assignRole('mahasiswa') bekerja
    $this->seed(RoleSeeder::class);
    
    Queue::fake();
    
    // 1. Buat Data Akademik Sederhana (ringan, tanpa seeder lengkap)
    $prodi = ProgramStudi::create([
        'kode_prodi' => 'IF',
        'nama_prodi' => 'Informatika',
        'jenjang' => 'S1'
    ]);
    
    $semester = Semester::create([
        'nama_semester' => 'Ganjil 2025/2026',
        'jenis' => 'Ganjil',
        'tahun_ajaran' => '2025/2026',
        'tanggal_mulai' => '2025-09-01',
        'tanggal_selesai' => '2026-02-28',
        'is_active' => true
    ]);
    
    $mk = MataKuliah::create([
        'program_studi_id' => $prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'Pemrograman Web',
        'sks' => 3,
        'semester_ke' => 1
    ]);
    
    $dosen = User::factory()->create();
    $dosen->assignRole('dosen');
    
    $studentWithNotif = User::factory()->create();
    $studentWithNotif->assignRole('mahasiswa');
    
    $studentWithoutNotif = User::factory()->create();
    $studentWithoutNotif->assignRole('mahasiswa');
    
    $kelas = KelasPerkuliahan::create([
        'mata_kuliah_id' => $mk->id,
        'dosen_id' => $dosen->id,
        'program_studi_id' => $prodi->id,
        'semester_id' => $semester->id,
        'kode_kelas' => 'IF-A',
        'tahun_akademik' => '2025/2026',
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'ruangan' => 'L.302',
        'kuota_mahasiswa' => 30,
        'status_kelas' => 'aktif',
        'is_active' => true
    ]);
    
    // Enroll mahasiswa ke kelas
    $kelas->mahasiswa()->attach($studentWithNotif->id);
    $kelas->mahasiswa()->attach($studentWithoutNotif->id);
    
    // Konfigurasi Preferensi Notifikasi
    $pref1 = NotificationPreference::forUser($studentWithNotif->id);
    $pref1->update([
        'tugas_baru' => true,
        'materi_baru' => true,
    ]);
    
    $pref2 = NotificationPreference::forUser($studentWithoutNotif->id);
    $pref2->update([
        'tugas_baru' => false,
        'materi_baru' => false,
    ]);
    
    // ==========================================
    // UJI KASUS 1: Notifikasi Tugas Baru
    // ==========================================
    $tugas = Tugas::create([
        'kelas_perkuliahan_id' => $kelas->id,
        'judul' => 'Tugas Baru Test',
        'deskripsi' => 'Deskripsi Tugas',
        'pertemuan_ke' => 1,
        'deadline' => now()->addDays(7),
        'nilai_maksimal' => 100,
    ]);
    
    NotificationService::notifyTugasBaru($tugas, $dosen);
    
    // Mahasiswa 1 harus menerima notifikasi tugas baru
    Queue::assertPushed(SendTugasBaru::class, function ($job) use ($studentWithNotif) {
        return $job->mahasiswa->id === $studentWithNotif->id;
    });
    
    // Mahasiswa 2 tidak boleh menerima notifikasi tugas baru
    Queue::assertNotPushed(SendTugasBaru::class, function ($job) use ($studentWithoutNotif) {
        return $job->mahasiswa->id === $studentWithoutNotif->id;
    });

    // ==========================================
    // UJI KASUS 2: Notifikasi Materi Baru
    // ==========================================
    $materi = Materi::create([
        'kelas_perkuliahan_id' => $kelas->id,
        'judul' => 'Materi Baru Test',
        'deskripsi' => 'Deskripsi Materi',
        'pertemuan_ke' => 1,
    ]);
    
    NotificationService::notifyMateriBaru($materi, $dosen);
    
    // Mahasiswa 1 harus menerima notifikasi materi baru
    Queue::assertPushed(SendMateriBaru::class, function ($job) use ($studentWithNotif) {
        return $job->mahasiswa->id === $studentWithNotif->id;
    });
    
    // Mahasiswa 2 tidak boleh menerima notifikasi materi baru
    Queue::assertNotPushed(SendMateriBaru::class, function ($job) use ($studentWithoutNotif) {
        return $job->mahasiswa->id === $studentWithoutNotif->id;
    });
});
