<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\KelasPerkuliahan;
use Illuminate\Database\Seeder;

class AbsensiTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Membuat data absensi untuk test...');

        // Cari kelas Cendekia
        $kelas = KelasPerkuliahan::where('kode_kelas', 'CEN101-A')->first();
        
        if (!$kelas) {
            $this->command->error('Kelas CEN101-A tidak ditemukan!');
            return;
        }

        // Buat absensi untuk hari ini
        $absensi = Absensi::firstOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'tanggal' => today(),
            ],
            [
                'pertemuan_ke' => 1,
                'rangkuman' => 'Pertemuan pertama - Pengenalan Cendekia',
                'berita_acara' => null,
            ]
        );

        $this->command->info('✓ Absensi untuk hari ini sudah dibuat');
        $this->command->info('  Kelas: ' . $kelas->mataKuliah->nama_mk);
        $this->command->info('  Tanggal: ' . $absensi->tanggal->format('d-m-Y'));
        $this->command->info('  Pertemuan: ' . $absensi->pertemuan_ke);
    }
}
