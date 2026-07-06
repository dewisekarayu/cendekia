<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ForumDiskusi;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $kelasIds = $request->user()->kelasDiikuti()->pluck('kelas_perkuliahan.id');

        $forumList = ForumDiskusi::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->with(['kelasPerkuliahan.mataKuliah', 'pembuat'])
            ->latest()
            ->get();

        return view('mahasiswa.forums', compact('forumList'));
    }
}