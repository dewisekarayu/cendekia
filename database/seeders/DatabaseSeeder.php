<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SemesterSeeder::class,
            ProgramStudiSeeder::class,
            EnhancedMataKuliahSeeder::class,
            EnhancedUserSeeder::class,
            EnhancedKelasPerkuliahanSeeder::class,
            ComprehensiveAcademicSeeder::class,
            KalenderAkademikSeeder::class,
        ]);
    }
}