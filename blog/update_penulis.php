<?php
require_once 'koneksi.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama_depan = $_POST['nama_depan'] ?? '';
$nama_belakang = $_POST['nama_belakang'] ?? '';
$user_name = $_POST['user_name'] ?? '';
$password_baru = $_POST['password'] ?? '';

if ($id <= 0 || empty($nama_depan) || empty($nama_belakang) || empty($user_name)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Kolom teks wajib diisi.']);
    exit;
}

// Ekstraksi data sebelumnya
$stmt_lama = $koneksi->prepare("SELECT password, foto FROM penulis WHERE id = ?");
$stmt_lama->bind_param("i", $id);
$stmt_lama->execute();
$hasil_lama = $stmt_lama->get_result();
$data_lama = $hasil_lama->fetch_assoc();
$stmt_lama->close();

$password_final = $data_lama['password'];
if (!empty($password_baru)) {
    $password_final = password_hash($password_baru, PASSWORD_BCRYPT);
}

$foto_final = $data_lama['foto'];
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['foto']['tmp_name']);
    finfo_close($finfo);

    $tipe_diizinkan = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime_type, $tipe_diizinkan) || $_FILES['foto']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Tipe file tidak valid atau ukuran > 2MB.']);
        exit;
    }

    $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_file_baru = uniqid() . '.' . $ekstensi;
    $tujuan = 'uploads_penulis/' . $nama_file_baru;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
        if ($foto_final !== 'default.png' && file_exists('uploads_penulis/' . $foto_final)) {
            unlink('uploads_penulis/' . $foto_final);
        }
        $foto_final = $nama_file_baru;
    } else {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal mengunggah foto.']);
        exit;
    }
}

$stmt = $koneksi->prepare("UPDATE penulis SET nama_depan = ?, nama_belakang = ?, user_name = ?, password = ?, foto = ? WHERE id = ?");
$stmt->bind_param("sssssi", $nama_depan, $nama_belakang, $user_name, $password_final, $foto_final, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Data penulis berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal memperbarui data.']);
}
$stmt->close();
?>