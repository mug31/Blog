<?php
require_once 'koneksi.php';

$stmt = $koneksi->prepare("SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id DESC");
$stmt->execute();
$hasil = $stmt->get_result();

$data = [];
while ($baris = $hasil->fetch_assoc()) {
    $data[] = [
        'id' => $baris['id'],
        'nama_kategori' => htmlspecialchars($baris['nama_kategori']),
        'keterangan' => htmlspecialchars($baris['keterangan'])
    ];
}
echo json_encode($data);
$stmt->close();
?>