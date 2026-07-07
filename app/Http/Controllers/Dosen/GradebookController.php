<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GradebookController extends Controller
{
    public function index()
    {
        $kelas = (object) [
            'kode_kelas' => 'IF-44-01'
        ];

        $students = collect([]);

        $totalStudents = $students->count();

        return view('dosen.gradebook', compact(
            'kelas',
            'students',
            'totalStudents'
        ));
    }
}