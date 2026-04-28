<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid.']);
    exit;
}

// Cek relasi artikel
$cek_relasi = $koneksi->prepare("SELECT id FROM artikel WHERE id_penulis = ?");
$cek_relasi->bind_param("i", $id);
$cek_relasi->execute();
$hasil_relasi = $cek_relasi->get_result();

if ($hasil_relasi->num_rows > 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Penulis tidak dapat dihapus karena masih memiliki artikel.']);
    $cek_relasi->close();
    exit;
}
$cek_relasi->close();

// Ambil nama file foto lama
$cek_foto = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$cek_foto->bind_param("i", $id);
$cek_foto->execute();
$hasil_foto = $cek_foto->get_result();
$baris_foto = $hasil_foto->fetch_assoc();
$foto_lama = $baris_foto['foto'] ?? '';
$cek_foto->close();

// Eksekusi penghapusan
$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($foto_lama && $foto_lama !== 'default.png' && file_exists('uploads_penulis/' . $foto_lama)) {
        unlink('uploads_penulis/' . $foto_lama);
    }
    echo json_encode(['status' => 'sukses', 'pesan' => 'Penulis berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menghapus penulis.']);
}
$stmt->close();
?>