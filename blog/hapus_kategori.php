<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid.']);
    exit;
}

// 1. Cek apakah kategori masih memiliki relasi di tabel artikel
$cek_relasi = $koneksi->prepare("SELECT id FROM artikel WHERE id_kategori = ?");
$cek_relasi->bind_param("i", $id);
$cek_relasi->execute();
$hasil_relasi = $cek_relasi->get_result();

if ($hasil_relasi->num_rows > 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Data tidak dapat dihapus karena kategori ini masih memiliki artikel.']);
    $cek_relasi->close();
    exit;
}
$cek_relasi->close();

// 2. Eksekusi penghapusan jika aman
$stmt = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Terjadi kesalahan sistem saat menghapus kategori.']);
}
$stmt->close();
?>