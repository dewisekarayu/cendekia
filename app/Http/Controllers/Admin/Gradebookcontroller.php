<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;

class Gradebookcontroller extends Controller
{
    public function index(Request $request)
    {
        $students = NilaiAkhir::with(['mahasiswa', 'kelasPerkuliahan.mataKuliah'])
            ->when($request->filled('kelas_id'), fn ($query) => $query->where('kelas_perkuliahan_id', $request->integer('kelas_id')))
            ->orderByDesc('nilai_akhir')
            ->paginate(15)
            ->withQueryString();

        return view('admin.gradebook.index', compact('students'));
    }
}
