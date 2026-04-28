<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid.']);
    exit;
}

$cek_foto = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$cek_foto->bind_param("i", $id);
$cek_foto->execute();
$hasil_foto = $cek_foto->get_result();
$baris_foto = $hasil_foto->fetch_assoc();
$gambar_lama = $baris_foto['gambar'] ?? '';
$cek_foto->close();

$stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($gambar_lama && file_exists('uploads_artikel/' . $gambar_lama)) {
        unlink('uploads_artikel/' . $gambar_lama);
    }
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menghapus artikel.']);
}
$stmt->close();
?>