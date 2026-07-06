<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = $request->user()->kelasDiikuti()->with('mataKuliah')->get();

        return view('mahasiswa.schedule', compact('kelasList'));
    }
}