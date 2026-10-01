<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can export dosen data to pdf and excel', function () {
    $this->seed([RoleSeeder::class, UserSeeder::class]);

    $admin = User::factory()->create([
        'name' => 'Admin Cendekia',
        'email' => 'admin-export-dosen@example.com',
    ]);
    $admin->assignRole('admin');

    // Test PDF export
    $responsePdf = $this->actingAs($admin)->get(route('admin.dosen.export.pdf'));
    $responsePdf->assertOk();
    $responsePdf->assertHeader('content-type', 'application/pdf');

    // Test Excel export
    $responseExcel = $this->actingAs($admin)->get(route('admin.dosen.export.excel'));
    $responseExcel->assertOk();
});

test('admin can export mahasiswa data to pdf and excel', function () {
    $this->seed([RoleSeeder::class, UserSeeder::class]);

    $admin = User::factory()->create([
        'name' => 'Admin Cendekia',
        'email' => 'admin-export-mhs@example.com',
    ]);
    $admin->assignRole('admin');

    // Test PDF export
    $responsePdf = $this->actingAs($admin)->get(route('admin.mahasiswa.export.pdf'));
    $responsePdf->assertOk();
    $responsePdf->assertHeader('content-type', 'application/pdf');

    // Test Excel export
    $responseExcel = $this->actingAs($admin)->get(route('admin.mahasiswa.export.excel'));
    $responseExcel->assertOk();
});
