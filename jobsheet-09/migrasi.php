<?php
require __DIR__ . '/includes/koneksi.php';

$jsonPath = 'C:/File/Kuliah/Semester3/PemrogramanWeb/jobsheet-06/data/buku.json';
$data = json_decode(file_get_contents($jsonPath), true);

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :stok, :kategori)"
);

$jumlah = 0;
foreach ($data as $buku) {
    $stmt->execute([
        'judul'     => $buku['judul'],
        'pengarang' => $buku['pengarang'],
        'tahun'     => $buku['tahun'],
        'stok'      => $buku['stok'],
        'kategori'  => $buku['kategori'],
    ]);
    $jumlah++;
}

echo "Berhasil migrasi $jumlah data buku dari buku.json ke database.";