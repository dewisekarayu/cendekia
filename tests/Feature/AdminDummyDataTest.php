<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seed creates many dummy users and shows them in admin pages', function () {
    $this->seed([RoleSeeder::class, UserSeeder::class]);

    expect(User::role('mahasiswa')->count())->toBe(100)
        ->and(User::role('dosen')->count())->toBe(40);

    $admin = User::factory()->create([
        'name' => 'Admin Cendekia',
        'email' => 'admin-dashboard@example.com',
    ]);
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('100')
        ->assertSee('40');

    $this->actingAs($admin)
        ->get('/admin/mahasiswa')
        ->assertOk()
        ->assertSee('Manajemen Mahasiswa')
        ->assertSee('data mahasiswa');

    $this->actingAs($admin)
        ->get('/admin/dosen')
        ->assertOk()
        ->assertSee('Manajemen Dosen')
        ->assertSee('data dosen');
});
