<?php
require_once 'koneksi.php';

$id_penulis = isset($_POST['id_penulis']) ? (int)$_POST['id_penulis'] : 0;
$id_kategori = isset($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : 0;
$judul = $_POST['judul'] ?? '';
$isi = $_POST['isi'] ?? '';

if ($id_penulis <= 0 || $id_kategori <= 0 || empty($judul) || empty($isi)) {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Kolom teks wajib diisi.']);
    exit;
}

// Set default gambar jika tidak ada yang diunggah
$nama_file = "default.png";

// Cek apakah pengguna mengunggah gambar
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    // Validasi tipe file menggunakan finfo [cite: 290-293]
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['gambar']['tmp_name']);
    finfo_close($finfo);

    $tipe_diizinkan = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime_type, $tipe_diizinkan)) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Tipe file tidak diizinkan.']);
        exit;
    }

    // Validasi ukuran maksimal 2 MB [cite: 296]
    if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ekstensi = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_file = uniqid() . '.' . $ekstensi; // Membuat nama unik [cite: 905, 914]
    $tujuan = 'uploads_artikel/' . $nama_file;

    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
        echo json_encode(['status' => 'gagal', 'pesan' => 'Gambar gagal diunggah ke server.']);
        exit;
    }
}

// Generate hari_tanggal otomatis dari server [cite: 249]
date_default_timezone_set('Asia/Jakarta');
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$sekarang = new DateTime();
$hari_tanggal = $hari[$sekarang->format('w')] . ", " . $sekarang->format('j') . " " . $bulan[(int)$sekarang->format('n')] . " " . $sekarang->format('Y') . " | " . $sekarang->format('H:i');

$stmt = $koneksi->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $nama_file, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil disimpan.']);
} else {
    // Hapus file jika database gagal menyimpan tapi file sudah terlanjur diupload [cite: 1089]
    if ($nama_file !== 'default.png' && file_exists('uploads_artikel/' . $nama_file)) {
        unlink('uploads_artikel/' . $nama_file);
    }
    echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menyimpan artikel ke database.']);
}
$stmt->close();
?>