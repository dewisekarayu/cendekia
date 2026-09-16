<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use Illuminate\Http\Request;

class AktivitasPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = AktivitasPengguna::with(['user', 'kelasPerkuliahan']);

        // Filter berdasarkan peran (admin, dosen, mahasiswa)
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->role($request->role); // Spatie permission helper
            });
        }

        // Pencarian deskripsi
        if ($request->has('search') && $request->search != '') {
            $query->where('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // Urutkan dari yang terbaru
        $logs = $query->latest('terjadi_pada')->paginate(20);

        return view('admin.aktivitas.index', compact('logs'));
    }
}
