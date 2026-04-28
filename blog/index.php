<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Blog (CMS)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h2>Sistem Manajemen Blog (CMS)</h2>
    </header>
    
    <div class="layout-container">
        <nav class="sidebar">
            <ul>
                <li><button onclick="muatPenulis()">Kelola Penulis</button></li>
                <li><button onclick="muatArtikel()">Kelola Artikel</button></li>
                <li><button onclick="muatKategori()">Kelola Kategori</button></li>
            </ul>
        </nav>

        <main class="content-area">
            <div class="card">
                <div class="header-content">
                    <h2 id="judul-halaman">Pilih menu navigasi di samping</h2>
                    <button id="btn-tambah" class="btn-tambah" style="display: none;">Tambah Data</button>
                </div>
                
                <div id="notifikasi"></div>
                
                <table>
                    <thead id="tabel-head"></thead>
                    <tbody id="tabel-body">
                        <tr><td style="text-align:center; padding: 30px; color: #999;">Silakan pilih menu untuk memuat data.</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="modal-kategori" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-judul">Tambah Kategori</h3>
            <form id="form-kategori">
                <input type="hidden" id="kategori_id" name="id">
                
                <label>Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" required>
                
                <label>Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4"></textarea>
                
                <button type="button" class="btn-tambah" onclick="simpanKategori()">Simpan Data</button>
                <button type="button" class="btn-batal" onclick="tutupModal('modal-kategori')">Batal</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>

    <div id="modal-penulis" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-judul-penulis">Tambah Penulis</h3>
            <form id="form-penulis">
                <input type="hidden" id="penulis_id" name="id">
                
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Nama Depan</label>
                        <input type="text" id="nama_depan" name="nama_depan" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Nama Belakang</label>
                        <input type="text" id="nama_belakang" name="nama_belakang" required>
                    </div>
                </div>
                
                <label>Username</label>
                <input type="text" id="user_name" name="user_name" required>
                
                <label>Password <span style="font-weight: normal; font-size: 12px; color: #777;">(Kosongkan jika tidak diganti)</span></label>
                <input type="password" id="password" name="password">

                <label>Foto Profil <span style="font-weight: normal; font-size: 12px; color: #777;">(Kosongkan jika tidak diganti)</span></label><br>
                <input type="file" id="foto" name="foto" accept="image/*" style="margin: 8px 0 20px;"><br>
                
                <button type="button" class="btn-tambah" onclick="simpanPenulis()">Simpan Data</button>
                <button type="button" class="btn-batal" onclick="tutupModal('modal-penulis')">Batal</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>

    <div id="modal-artikel" class="modal-overlay">
        <div class="modal-content" style="width: 650px;">
            <h3 id="modal-judul-artikel">Tambah Artikel</h3>
            <form id="form-artikel">
                <input type="hidden" id="artikel_id" name="id">
                
                <label>Judul Artikel</label>
                <input type="text" id="judul" name="judul" required>
                
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Penulis</label>
                        <select id="id_penulis" name="id_penulis" required></select>
                    </div>
                    <div style="flex: 1;">
                        <label>Kategori</label>
                        <select id="id_kategori" name="id_kategori" required></select>
                    </div>
                </div>
                
                <label>Isi Artikel</label>
                <textarea id="isi" name="isi" rows="6" required></textarea>
                
                <label>Gambar <span style="font-weight: normal; font-size: 12px; color: #777;">(Kosongkan jika tidak diganti)</span></label><br>
                <input type="file" id="gambar" name="gambar" accept="image/*" style="margin: 8px 0 20px;"><br>
                
                <button type="button" class="btn-tambah" onclick="simpanArtikel()">Simpan Data</button>
                <button type="button" class="btn-batal" onclick="tutupModal('modal-artikel')">Batal</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>