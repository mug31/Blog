<?php
require_once 'koneksi.php';

$nama_kategori = $_POST['nama_kategori'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

if (empty($nama_kategori)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Nama kategori wajib diisi.']);
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param("ss", $nama_kategori, $keterangan);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil ditambahkan.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menyimpan data. Kategori mungkin sudah ada.']);
}
$stmt->close();
?>