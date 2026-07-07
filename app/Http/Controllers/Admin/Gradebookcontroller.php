
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GradebookController extends Controller
{
    public function index()
    public function index(Request $request)
    {
        $kelas = (object) [
            'kode_kelas' => 'IF-44-01'
        ];
        $kelasList = $request->user()->kelasDiampu()->with('mataKuliah')->get();
        $kelas = $kelasList->firstWhere('id', (int) $request->query('kelas_id')) ?? $kelasList->first();

        $students = collect([]);
        $students = $kelas
            ? NilaiAkhir::with('mahasiswa')
                ->where('kelas_perkuliahan_id', $kelas->id)
                ->orderByDesc('nilai_akhir')
                ->paginate(15)
                ->withQueryString()
            : collect();

        $totalStudents = $students->count();
        $totalStudents = $kelas ? KelasPerkuliahan::withCount('mahasiswa')->find($kelas->id)?->mahasiswa_count : 0;

        return view('dosen.gradebook', compact(
            'kelasList',
            'kelas',
            'students',
            'totalStudents'
        ));
    }
}
}