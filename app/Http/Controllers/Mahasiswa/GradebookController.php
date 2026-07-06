<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index(Request $request)
    {
        $nilaiList = PengumpulanTugas::with('tugas.kelasPerkuliahan.mataKuliah')
            ->where('mahasiswa_id', $request->user()->id)
            ->whereNotNull('nilai')
            ->get();

        return view('mahasiswa.gradebook', compact('nilaiList'));
    }
}