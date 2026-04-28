<?php
require_once 'koneksi.php';

$stmt = $koneksi->prepare("SELECT id, nama_depan, nama_belakang, user_name, password, foto FROM penulis ORDER BY id DESC");
$stmt->execute();
$hasil = $stmt->get_result();

$data = [];
while ($baris = $hasil->fetch_assoc()) {
    $data[] = [
        'id' => $baris['id'],
        'nama' => htmlspecialchars($baris['nama_depan'] . ' ' . $baris['nama_belakang']),
        'user_name' => htmlspecialchars($baris['user_name']),
        'password' => htmlspecialchars($baris['password']),
        'foto' => htmlspecialchars($baris['foto'])
    ];
}
echo json_encode($data);
$stmt->close();
?>