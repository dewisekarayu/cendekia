<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Simpan dulu nilai status lama (boolean) sebelum kolom diubah
        $oldStatuses = [];
        if (Schema::hasColumn('users', 'status')) {
            $oldStatuses = DB::table('users')->pluck('status', 'id')->toArray();

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // 2. Buat ulang kolom status sebagai string (aktif/cuti/non_aktif)
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('aktif')->after('email');

            if (!Schema::hasColumn('users', 'telepon')) {
                $table->string('telepon')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'angkatan')) {
                $table->unsignedSmallInteger('angkatan')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('angkatan');
            }
        });

        // 3. Migrasikan nilai lama: true/1 -> 'aktif', false/0 -> 'non_aktif'
        foreach ($oldStatuses as $id => $oldValue) {
            DB::table('users')->where('id', $id)->update([
                'status' => $oldValue ? 'aktif' : 'non_aktif',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('users', 'angkatan')) {
                $table->dropColumn('angkatan');
            }
            if (Schema::hasColumn('users', 'telepon')) {
                $table->dropColumn('telepon');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('email');
        });
    }
};