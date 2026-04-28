<?php
require_once 'koneksi.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$id_penulis = isset($_POST['id_penulis']) ? (int)$_POST['id_penulis'] : 0;
$id_kategori = isset($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : 0;
$judul = $_POST['judul'] ?? '';
$isi = $_POST['isi'] ?? '';

if ($id <= 0 || $id_penulis <= 0 || $id_kategori <= 0 || empty($judul) || empty($isi)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Semua kolom teks wajib diisi.']);
    exit;
}

$stmt_lama = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmt_lama->bind_param("i", $id);
$stmt_lama->execute();
$hasil_lama = $stmt_lama->get_result();
$data_lama = $hasil_lama->fetch_assoc();
$stmt_lama->close();

$gambar_final = $data_lama['gambar'];

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['gambar']['tmp_name']);
    finfo_close($finfo);

    $tipe_diizinkan = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime_type, $tipe_diizinkan) || $_FILES['gambar']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Tipe file tidak valid atau ukuran > 2MB.']);
        exit;
    }

    $ekstensi = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_gambar_baru = uniqid() . '.' . $ekstensi;
    $tujuan = 'uploads_artikel/' . $nama_gambar_baru;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
        if ($gambar_final && file_exists('uploads_artikel/' . $gambar_final)) {
            unlink('uploads_artikel/' . $gambar_final);
        }
        $gambar_final = $nama_gambar_baru;
    } else {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal mengunggah gambar artikel.']);
        exit;
    }
}

$stmt = $koneksi->prepare("UPDATE artikel SET id_penulis = ?, id_kategori = ?, judul = ?, isi = ?, gambar = ? WHERE id = ?");
$stmt->bind_param("iisssi", $id_penulis, $id_kategori, $judul, $isi, $gambar_final, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal memperbarui artikel.']);
}
$stmt->close();
?>