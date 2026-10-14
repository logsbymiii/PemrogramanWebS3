<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$email = trim($_POST['email'] ?? '');

$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

if ($no_anggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email wajib diisi dengan format yang benar.";
}

if ($no_hp !== '' && !preg_match('/^[0-9+\- ]+$/', $no_hp)) {
    $errors[] = "No. HP hanya boleh berisi angka, spasi, tanda plus (+), dan tanda hubung (-).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

require __DIR__ . '/../includes/koneksi.php';

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp, email)
         VALUES (:nama, :no_anggota, :alamat, :no_hp, :email)
         RETURNING id"
    );
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'alamat'     => $alamat,
        'no_hp'      => $no_hp,
        'email'      => $email,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Anggota sudah terdaftar, gunakan nomor lain.'];
    header('Location: tambah.php');
    exit;
}
