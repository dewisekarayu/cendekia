<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->decimal('nilai_kehadiran', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_tugas', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_quiz', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_project', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_uts', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_uas', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_akhir', 5, 2)->nullable()->default(null)->change();
            $table->string('grade', 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->decimal('nilai_kehadiran', 5, 2)->default(0)->change();
            $table->decimal('nilai_tugas', 5, 2)->default(0)->change();
            $table->decimal('nilai_quiz', 5, 2)->default(0)->change();
            $table->decimal('nilai_project', 5, 2)->default(0)->change();
            $table->decimal('nilai_uts', 5, 2)->default(0)->change();
            $table->decimal('nilai_uas', 5, 2)->default(0)->change();
            $table->decimal('nilai_akhir', 5, 2)->default(0)->change();
            $table->string('grade', 2)->default('E')->change();
        });
    }
};
