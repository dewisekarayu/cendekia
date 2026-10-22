<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('penerima', 20)->default('semua')->after('untuk_semua');
        });
    }

    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn('penerima');
        });
    }
};
