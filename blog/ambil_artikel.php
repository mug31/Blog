<?php
require_once 'koneksi.php';

$query = "SELECT a.id, a.judul, a.gambar, a.hari_tanggal, k.nama_kategori, p.nama_depan, p.nama_belakang 
          FROM artikel a 
          JOIN kategori_artikel k ON a.id_kategori = k.id 
          JOIN penulis p ON a.id_penulis = p.id 
          ORDER BY a.id DESC";
          
$stmt = $koneksi->prepare($query);
$stmt->execute();
$hasil = $stmt->get_result();

$data = [];
while ($baris = $hasil->fetch_assoc()) {
    $data[] = [
        'id' => $baris['id'],
        'gambar' => htmlspecialchars($baris['gambar']),
        'judul' => htmlspecialchars($baris['judul']),
        'kategori' => htmlspecialchars($baris['nama_kategori']),
        'penulis' => htmlspecialchars($baris['nama_depan'] . ' ' . $baris['nama_belakang']),
        'hari_tanggal' => htmlspecialchars($baris['hari_tanggal'])
    ];
}
echo json_encode($data);
$stmt->close();
?>