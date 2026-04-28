<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama_kategori = $_POST['nama_kategori'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

if ($id <= 0 || empty($nama_kategori)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid atau nama kategori kosong.']);
    exit;
}

$stmt = $koneksi->prepare("UPDATE kategori_artikel SET nama_kategori = ?, keterangan = ? WHERE id = ?");
$stmt->bind_param("ssi", $nama_kategori, $keterangan, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal memperbarui kategori.']);
}
$stmt->close();
?>