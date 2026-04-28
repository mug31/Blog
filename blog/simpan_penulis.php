<?php
require_once 'koneksi.php';

$nama_depan = $_POST['nama_depan'] ?? '';
$nama_belakang = $_POST['nama_belakang'] ?? '';
$user_name = $_POST['user_name'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nama_depan) || empty($nama_belakang) || empty($user_name) || empty($password)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Semua kolom teks wajib diisi.']);
    exit;
}

// Enkripsi password sesuai ketentuan UTS
$password_hashed = password_hash($password, PASSWORD_BCRYPT);
$nama_file = "default.png"; // Fallback default sesuai soal

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    // Validasi file menggunakan fungsi finfo, bukan $_FILES['type']
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['foto']['tmp_name']);
    finfo_close($finfo);

    $tipe_diizinkan = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime_type, $tipe_diizinkan)) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Tipe file tidak diizinkan.']);
        exit;
    }

    // Validasi ukuran maksimal 2MB
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_file = uniqid() . '.' . $ekstensi;
    $tujuan = 'uploads_penulis/' . $nama_file;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Foto gagal diunggah.']);
        exit;
    }
}

// Prepared statement untuk insert data
$stmt = $koneksi->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $password_hashed, $nama_file);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Penulis berhasil ditambahkan.']);
} else {
    if ($nama_file !== 'default.png' && file_exists('uploads_penulis/' . $nama_file)) {
        unlink('uploads_penulis/' . $nama_file);
    }
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menyimpan data. Username mungkin duplikat.']);
}
$stmt->close();
?>