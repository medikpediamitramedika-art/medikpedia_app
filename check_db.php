<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$medicines = \App\Models\Medicine::select('id', 'nama_obat', 'gambar')->limit(10)->get();

echo "=== DATA MEDICINES ===" . PHP_EOL;
foreach ($medicines as $m) {
    echo "ID: {$m->id} | Nama: {$m->nama_obat} | Gambar: {$m->gambar}" . PHP_EOL;
}
