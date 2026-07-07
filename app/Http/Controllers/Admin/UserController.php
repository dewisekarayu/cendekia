<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'mahasiswa');

        $userList = User::role($role)
            ->with('programStudi')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.user.index', compact('userList', 'role'));
    }

    public function create(Request $request)
    {
        $role = $request->get('role', 'mahasiswa');
        $prodiList = ProgramStudi::all();

        return view('admin.user.create', compact('role', 'prodiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip_nim' => 'required|string|max:50|unique:users,nip_nim',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'program_studi_id' => 'nullable|exists:program_studi,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nip_nim' => $validated['nip_nim'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'program_studi_id' => $validated['role'] === 'mahasiswa' ? ($validated['program_studi_id'] ?? null) : null,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.user.index', ['role' => $validated['role']])
            ->with('success', 'Akun ' . $user->name . ' berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $prodiList = ProgramStudi::all();
        $currentRole = $user->getRoleNames()->first() ?? 'mahasiswa';

        return view('admin.user.edit', compact('user', 'prodiList', 'currentRole'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip_nim' => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'program_studi_id' => 'nullable|exists:program_studi,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'nip_nim' => $validated['nip_nim'],
            'email' => $validated['email'],
            'program_studi_id' => $validated['role'] === 'mahasiswa' ? ($validated['program_studi_id'] ?? null) : null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.user.index', ['role' => $validated['role']])
            ->with('success', 'Akun ' . $user->name . ' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $role = $user->getRoleNames()->first() ?? 'mahasiswa';
        $user->delete();

        return redirect()->route('admin.user.index', ['role' => $role])
            ->with('success', 'Akun berhasil dihapus.');
    }
}