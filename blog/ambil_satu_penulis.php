<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'ID tidak valid.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama_depan, nama_belakang, user_name, foto FROM penulis WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hasil = $stmt->get_result();
$baris = $hasil->fetch_assoc();

if ($baris) {
    echo json_encode([
        'status' => 'sukses',
        'data' => [
            'id' => $baris['id'],
            'nama_depan' => htmlspecialchars($baris['nama_depan']),
            'nama_belakang' => htmlspecialchars($baris['nama_belakang']),
            'user_name' => htmlspecialchars($baris['user_name']),
            'foto' => htmlspecialchars($baris['foto'])
        ]
    ]);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Data tidak ditemukan.']);
}
$stmt->close();
?>