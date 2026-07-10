<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AhmadSubagjoKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Membuat kelas untuk Prof Ahmad Subagjo...');

        // Cari atau buat dosen Ahmad Subagjo
        $dosen = \App\Models\User::where('email', 'ahmad.subagjo@example.com')->first();
        
        if (!$dosen) {
            $dosen = \App\Models\User::create([
                'name' => 'Prof Ahmad Subagjo',
                'email' => 'ahmad.subagjo@example.com',
                'password' => bcrypt('password123'),
                'nip_nim' => 'NIP123456789',
                'status' => 'aktif',
            ]);
            $dosen->assignRole('dosen');
            $this->command->info('✓ Dosen Ahmad Subagjo dibuat');
        } else {
            $this->command->info('✓ Dosen Ahmad Subagjo sudah ada');
        }

        // Cari atau buat mahasiswa Dewi Sekar
        $dewiSekar = \App\Models\User::where('email', 'dewi.sekar@example.com')->first();
        
        if (!$dewiSekar) {
            $dewiSekar = \App\Models\User::create([
                'name' => 'Dewi Sekar',
                'email' => 'dewi.sekar@example.com',
                'password' => bcrypt('password123'),
                'nip_nim' => 'NIM001',
                'status' => 'aktif',
            ]);
            $dewiSekar->assignRole('mahasiswa');
            $this->command->info('✓ Mahasiswa Dewi Sekar dibuat');
        } else {
            $this->command->info('✓ Mahasiswa Dewi Sekar sudah ada');
        }

        // Cari atau buat mahasiswa May Lusi
        $mayLusi = \App\Models\User::where('email', 'may.lusi@example.com')->first();
        
        if (!$mayLusi) {
            $mayLusi = \App\Models\User::create([
                'name' => 'May Lusi',
                'email' => 'may.lusi@example.com',
                'password' => bcrypt('password123'),
                'nip_nim' => 'NIM002',
                'status' => 'aktif',
            ]);
            $mayLusi->assignRole('mahasiswa');
            $this->command->info('✓ Mahasiswa May Lusi dibuat');
        } else {
            $this->command->info('✓ Mahasiswa May Lusi sudah ada');
        }

        // Cari Program Studi pertama
        $programStudi = \App\Models\ProgramStudi::first();
        if (!$programStudi) {
            $programStudi = \App\Models\ProgramStudi::create([
                'kode_prodi' => 'TIF',
                'nama_prodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'status' => 'aktif',
            ]);
        }

        // Cari atau buat mata kuliah "Cendekia"
        $mataKuliah = \App\Models\MataKuliah::where('nama_mk', 'Cendekia')->first();
        
        if (!$mataKuliah) {
            $mataKuliah = \App\Models\MataKuliah::create([
                'program_studi_id' => $programStudi->id,
                'kode_mk' => 'CEN101',
                'nama_mk' => 'Cendekia',
                'sks' => 3,
                'semester_ke' => 1,
                'deskripsi' => 'Kelas Cendekia untuk pembelajaran mendalam',
            ]);
            $this->command->info('✓ Mata Kuliah Cendekia dibuat');
        } else {
            $this->command->info('✓ Mata Kuliah Cendekia sudah ada');
        }

        // Cari semester aktif
        $semester = \App\Models\Semester::where('is_active', true)->first();
        if (!$semester) {
            $semester = \App\Models\Semester::create([
                'nama_semester' => 'Semester Ganjil',
                'jenis' => 'ganjil',
                'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                'tanggal_mulai' => now()->startOfYear(),
                'tanggal_selesai' => now()->endOfYear(),
                'is_active' => true,
            ]);
        }

        // Buat kelas Cendekia dengan Prof Ahmad Subagjo sebagai dosen
        $kelas = \App\Models\KelasPerkuliahan::where('mata_kuliah_id', $mataKuliah->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        if (!$kelas) {
            $kelas = \App\Models\KelasPerkuliahan::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'dosen_id' => $dosen->id,
                'program_studi_id' => $programStudi->id,
                'semester_id' => $semester->id,
                'kode_kelas' => 'CEN101-A',
                'tahun_akademik' => date('Y') . '/' . (date('Y') + 1),
                'hari' => 'Senin',
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
                'ruangan' => 'Ruang 101',
                'kuota_mahasiswa' => 50,
                'status_kelas' => 'aktif',
                'is_active' => true,
            ]);
            $this->command->info('✓ Kelas Cendekia dibuat');
        } else {
            $this->command->info('✓ Kelas Cendekia sudah ada');
        }

        // Daftarkan Dewi Sekar ke kelas
        \App\Models\KelasMahasiswa::firstOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $dewiSekar->id,
            ],
            [
                'tanggal_daftar' => now(),
            ]
        );
        $this->command->info('✓ Dewi Sekar terdaftar di kelas Cendekia');

        // Daftarkan May Lusi ke kelas
        \App\Models\KelasMahasiswa::firstOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $mayLusi->id,
            ],
            [
                'tanggal_daftar' => now(),
            ]
        );
        $this->command->info('✓ May Lusi terdaftar di kelas Cendekia');

        $this->command->info('✅ Seeder Ahmad Subagjo Kelas selesai!');
        $this->command->line('');
        $this->command->table(
            ['Info', 'Detail'],
            [
                ['Dosen', 'Prof Ahmad Subagjo (' . $dosen->email . ')'],
                ['Mahasiswa 1', 'Dewi Sekar (' . $dewiSekar->email . ')'],
                ['Mahasiswa 2', 'May Lusi (' . $mayLusi->email . ')'],
                ['Mata Kuliah', 'Cendekia'],
                ['Kode Kelas', 'CEN101-A'],
                ['Hari/Jam', 'Senin, 09:00-11:00'],
                ['Ruangan', 'Ruang 101'],
            ]
        );
    }
}
