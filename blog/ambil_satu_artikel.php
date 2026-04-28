<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, id_penulis, id_kategori, judul, isi, gambar FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hasil = $stmt->get_result();
$baris = $hasil->fetch_assoc();

if ($baris) {
    echo json_encode([
        'status' => 'sukses',
        'data' => [
            'id' => $baris['id'],
            'id_penulis' => $baris['id_penulis'],
            'id_kategori' => $baris['id_kategori'],
            'judul' => htmlspecialchars($baris['judul']),
            'isi' => htmlspecialchars($baris['isi']),
            'gambar' => htmlspecialchars($baris['gambar'])
        ]
    ]);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Data tidak ditemukan.']);
}
$stmt->close();
?>