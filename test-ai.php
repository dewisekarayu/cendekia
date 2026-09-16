<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new \App\Http\Controllers\Dosen\AiAssistantController();

$title = "Soal Algoritma";
$tipeSoal = "Bentuk/Format/Jumlah yang diminta: 5 Soal Essay.\n";

$prompt = "Buatkan isi dokumen materi atau soal-soal tugas perkuliahan dengan judul/topik '$title'.\n"
        . $tipeSoal
        . "PENTING:\n"
        . "1. LANGSUNG berikan isinya (daftar soal atau materi penjelasan yang padat dan terstruktur).\n"
        . "2. JIKA Anda membuat soal, DILARANG KERAS menyertakan kunci jawaban, pembahasan, atau 'Jawaban: ...'. Ini adalah dokumen lembar soal murni yang akan dikerjakan mahasiswa.\n"
        . "3. JANGAN menulis ulang Judul Tugas.\n"
        . "4. JANGAN gunakan kalimat pembuka/penutup seperti 'Berikut adalah daftar soal...'.\n"
        . "5. DILARANG KERAS menggunakan format Markdown (seperti **tebal** atau *miring*). Gunakan teks murni biasa.";

$messages = [
    ['role' => 'user', 'content' => $prompt]
];

// We need to use reflection to call private method
$reflection = new ReflectionClass(get_class($controller));
$method = $reflection->getMethod('callAiApi');
$method->setAccessible(true);

try {
    $result = $method->invokeArgs($controller, [$messages]);
    echo "AI RESULT SUCCESS\n";
    $html = "<html><body>$result</body></html>";
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
    $output = $pdf->output();
    echo "PDF GENERATED: " . strlen($output) . " bytes\n";
} catch (\Exception $e) {
    echo "ERROR:\n";
    echo $e->getMessage();
}
