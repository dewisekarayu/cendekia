@extends('layouts.portal')

@section('title', 'Buat Kelas Baru')
@section('activeMenu', 'Kelas Perkuliahan')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Buat Kelas Baru</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <select name="program_studi_id" class="w-full border-gray-300 rounded-lg text-sm" id="programStudiSelect">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach ($programStudiList as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('program_studi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="w-full border-gray-300 rounded-lg text-sm" id="mataKuliahSelect">
                    <option value="">-- Pilih Program Studi terlebih dahulu --</option>
                </select>
                @error('mata_kuliah_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengampu (Utama)</label>
                <select name="dosen_id" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosenList as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->name }} ({{ $dosen->nip_nim }})
                        </option>
                    @endforeach
                </select>
                @error('dosen_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @if ($dosenList->isEmpty())
                    <p class="text-amber-600 text-xs mt-1">Belum ada dosen terdaftar. Tambahkan user dengan role dosen terlebih dahulu.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Team Teaching (Opsional)</label>
                <select name="dosen_pengampu[]" class="w-full border-gray-300 rounded-lg text-sm" multiple size="3">
                    @foreach ($dosenList as $dosen)
                        <option value="{{ $dosen->id }}" {{ in_array($dosen->id, old('dosen_pengampu', [])) ? 'selected' : '' }}>
                            {{ $dosen->name }} ({{ $dosen->nip_nim }})
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-xs mt-1">Tekan Ctrl (Windows) atau Command (Mac) untuk memilih multiple dosen</p>
                @error('dosen_pengampu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester_id" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">-- Pilih Semester --</option>
                    @foreach ($semesterList as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }} {{ $semester->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('semester_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kelas</label>
                <input type="text" name="kode_kelas" value="{{ old('kode_kelas') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="A">
                @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" class="w-full border-gray-300 rounded-lg text-sm">
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('jam_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('jam_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                <input type="text" name="ruangan" value="{{ old('ruangan') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Lab Komputer 1">
                @error('ruangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Akademik</label>
                    <select name="tahun_akademik" class="w-full border-gray-300 rounded-lg text-sm">
                        <option value="">-- Pilih Tahun Akademik --</option>
                        @foreach ($tahunAkademikOptions as $tahun)
                            <option value="{{ $tahun }}" {{ old('tahun_akademik') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                    @error('tahun_akademik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Mahasiswa</label>
                    <input type="number" name="kuota_mahasiswa" value="{{ old('kuota_mahasiswa', 30) }}" min="1" max="200" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('kuota_mahasiswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester_id" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">-- Pilih Semester --</option>
                    @foreach ($semesterList as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }} {{ $semester->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('semester_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button>
                <a href="{{ route('admin.kelas.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programStudiSelect = document.getElementById('programStudiSelect');
    const mataKuliahSelect = document.getElementById('mataKuliahSelect');
    
    // Data mata kuliah dari server (bisa di-fetch via AJAX nanti)
    const mataKuliahData = @json($mataKuliahList);
    
    programStudiSelect.addEventListener('change', function() {
        const selectedProdiId = this.value;
        
        // Clear current options
        mataKuliahSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
        
        if (!selectedProdiId) {
            mataKuliahSelect.innerHTML = '<option value="">-- Pilih Program Studi terlebih dahulu --</option>';
            return;
        }
        
        // Filter mata kuliah berdasarkan program studi
        const filteredMataKuliah = mataKuliahData.filter(mk => mk.program_studi_id == selectedProdiId);
        
        filteredMataKuliah.forEach(mk => {
            const option = document.createElement('option');
            option.value = mk.id;
            option.textContent = `${mk.kode_mk} - ${mk.nama_mk}`;
            mataKuliahSelect.appendChild(option);
        });
        
        // Jika ada old value, set selected
        const oldValue = @json(old('mata_kuliah_id'));
        if (oldValue) {
            mataKuliahSelect.value = oldValue;
        }
    });
    
    // Trigger change on page load jika ada old value untuk program studi
    const oldProdiId = @json(old('program_studi_id'));
    if (oldProdiId) {
        programStudiSelect.value = oldProdiId;
        programStudiSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection