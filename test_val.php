<?php require 'vendor/autoload.php'; require 'bootstrap/app.php'; $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
$data = ["kelas_perkuliahan_id" => 1, "tanggal_pengganti" => "2026-10-12", "jam_mulai" => "08:00", "jam_selesai" => "10:00", "ruangan_pengganti" => "R1", "alasan_pengganti" => ""];
$rules = ["kelas_perkuliahan_id" => "required", "tanggal_pengganti" => "required|date|after_or_equal:today", "jam_mulai" => "required|string|max:10", "jam_selesai" => "required|string|max:10|after:jam_mulai", "ruangan_pengganti" => "required|string|max:100", "alasan_pengganti" => "nullable|string|max:1000"];
$validator = Validator::make($data, $rules);
echo json_encode(["passes" => $validator->passes(), "errors" => $validator->errors()->toArray()]);

