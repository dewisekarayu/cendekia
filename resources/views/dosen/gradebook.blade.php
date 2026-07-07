@extends('layouts.portal')

@section('title', 'Gradebook')
@section('activeMenu', 'Gradebook')

@section('content')

<div class="max-w-7xl mx-auto">
	<!-- Header (match My Classes style) -->
	<div class="bg-[#321270] rounded-xl px-8 py-6 relative overflow-hidden mb-6">
		<div class="mb-2">
			<h1 class="text-xl font-bold text-white">Gradebook</h1>
			<p class="text-sm text-white/80 mt-1">Class: {{ $kelas->kode_kelas ?? 'IF-44-01' }} | Semester Genap 2023/2024</p>
		</div>

		<div class="absolute right-6 top-6 flex items-center gap-3">
			<a href="#" class="inline-flex items-center gap-2 bg-white text-sm text-[#321270] px-3 py-2 rounded-md shadow">Export PDF</a>
			<a href="#" class="inline-flex items-center gap-2 bg-white text-sm text-[#321270] px-3 py-2 rounded-md shadow">Export to Excel</a>
		</div>
	</div>

	<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
		<div class="px-6 py-4">
			<div class="flex items-center gap-4">
				<div class="relative">
					<select class="text-sm border-gray-200 rounded-lg py-2 pl-3 pr-8">
						<option>Urutkan berdasarkan...</option>
					</select>
				</div>
				<div class="ml-auto text-sm text-gray-500">Nilai keseluruhan</div>
			</div>
		</div>

		<div class="overflow-x-auto">
			<table class="w-full text-sm">
				<thead>
					<tr class="text-left text-gray-500 text-xs border-t border-b border-gray-100">
						<th class="px-6 py-3">Student</th>
						<th class="px-6 py-3">Nilai keseluruhan</th>
						<th class="px-6 py-3">Tugas 2 - Analisis</th>
						<th class="px-6 py-3">Tugas 1 - Membuat</th>
					</tr>
				</thead>
				<tbody>
					@foreach($students as $student)
						<tr class="border-b last:border-0">
							<td class="px-6 py-4">
								<div class="flex items-center gap-3">
									<div class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden">
										<img src="{{ $student->avatar ?? asset('images/default-avatar.png') }}" alt="" class="w-full h-full object-cover">
									</div>
									<div>
										<div class="font-medium text-gray-800">{{ $student->name }}</div>
										<div class="text-xs text-gray-400">{{ $student->nim ?? '' }}</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-4 font-semibold text-gray-800">{{ $student->overall ?? '-' }}</td>
							<td class="px-6 py-4 text-gray-700">{{ $student->tugas2 ?? '-' }}</td>
							<td class="px-6 py-4 text-gray-700">{{ $student->tugas1 ?? '-' }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between">
			<div class="text-sm text-gray-500">Showing {{ $students->count() }} of {{ $totalStudents ?? $students->count() }} students</div>
			<div class="flex items-center gap-2">
				<a href="#" class="px-3 py-1 bg-white border rounded">‹</a>
				<a href="#" class="px-3 py-1 bg-[#321270] text-white rounded">1</a>
				<a href="#" class="px-3 py-1 bg-white border rounded">2</a>
				<a href="#" class="px-3 py-1 bg-white border rounded">›</a>
			</div>
		</div>
	</div>
</div>

@endsection
