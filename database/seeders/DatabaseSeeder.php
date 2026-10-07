<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SemesterSeeder::class,
            ProgramStudiSeeder::class,
            EnhancedUserSeeder::class,
            EnhancedMataKuliahSeeder::class,
            EnhancedKelasPerkuliahanSeeder::class,
            ComprehensiveAcademicSeeder::class,
            KalenderAkademikSeeder::class,
        ]);
    }
}
