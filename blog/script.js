// modul kategori
function muatKategori() {
    tutupSemuaModal();
    document.getElementById('judul-halaman').innerText = 'Data Kategori Artikel';
    document.getElementById('btn-tambah').style.display = 'inline-block';
    document.getElementById('btn-tambah').innerText = 'Tambah Kategori';
    document.getElementById('btn-tambah').setAttribute('onclick', 'bukaModalKategori()');
    document.getElementById('tabel-head').innerHTML = '<tr><th>Nama Kategori</th><th>Keterangan</th><th>Aksi</th></tr>';
    
    fetch('ambil_kategori.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('tabel-body');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" align="center">Belum ada data kategori.</td></tr>';
            return;
        }
        data.forEach(baris => {
            tbody.innerHTML += `
                <tr>
                    <td>${baris.nama_kategori}</td>
                    <td>${baris.keterangan}</td>
                    <td>
                        <button onclick="editKategori(${baris.id})">Edit</button>
                        <button onclick="hapusKategori(${baris.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    });
}

function bukaModalKategori() {
    document.getElementById('form-kategori').reset();
    document.getElementById('kategori_id').value = '';
    document.getElementById('modal-judul').innerText = 'Tambah Kategori';
    document.getElementById('modal-kategori').style.display = 'block';
}

function tutupModal(idModal) {
    document.getElementById(idModal).style.display = 'none';
}

function simpanKategori() {
    const form = document.getElementById('form-kategori');
    const formData = new FormData(form);
    const id = document.getElementById('kategori_id').value;
    
    const url = id ? 'update_kategori.php' : 'simpan_kategori.php';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            tampilkanNotifikasi(data.pesan, data.status);
            if (data.status === 'sukses') {
                tutupModal('modal-kategori');
                muatKategori();
            }
        } catch (error) {
            console.error("Format Respon Server Tidak Valid (Bukan JSON):", text);
            tampilkanNotifikasi("Terjadi kesalahan server. Periksa Console (F12).", "gagal");
        }
    })
    .catch(error => {
        console.error("Terjadi kegagalan jaringan/request:", error);
        tampilkanNotifikasi("Gagal terhubung ke server.", "gagal");
    });
}

function editKategori(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('ambil_satu_kategori.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sukses') {
            document.getElementById('kategori_id').value = data.data.id;
            document.getElementById('nama_kategori').value = data.data.nama_kategori;
            document.getElementById('keterangan').value = data.data.keterangan;
            document.getElementById('modal-judul').innerText = 'Edit Kategori';
            document.getElementById('modal-kategori').style.display = 'block';
        } else {
            tampilkanNotifikasi(data.pesan, 'gagal');
        }
    });
}

function hapusKategori(id) {
    if (!confirm('Hapus data ini?')) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('hapus_kategori.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        tampilkanNotifikasi(data.pesan, data.status);
        if (data.status === 'sukses') muatKategori();
    });
}

function tampilkanNotifikasi(pesan, status) {
    const notif = document.getElementById('notifikasi');
    notif.innerText = pesan;
    notif.style.color = status === 'sukses' ? 'green' : 'red';
    setTimeout(() => notif.innerText = '', 3000);
}

// modul penulis
function muatPenulis() {
    tutupSemuaModal();
    document.getElementById('judul-halaman').innerText = 'Data Penulis';
    document.getElementById('btn-tambah').style.display = 'inline-block';
    document.getElementById('btn-tambah').innerText = 'Tambah Penulis';
    document.getElementById('btn-tambah').setAttribute('onclick', 'bukaModalPenulis()');
    document.getElementById('tabel-head').innerHTML = '<tr><th>Foto</th><th>Nama</th><th>Username</th><th>Aksi</th></tr>';
    
    fetch('ambil_penulis.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('tabel-body');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" align="center">Belum ada data penulis.</td></tr>';
            return;
        }
        data.forEach(baris => {
            const fotoPath = baris.foto ? 'uploads_penulis/' + baris.foto : 'uploads_penulis/default.png';
            tbody.innerHTML += `
                <tr>
                    <td align="center"><img src="${fotoPath}" width="50" height="50" style="object-fit: cover; border-radius: 5px;"></td>
                    <td>${baris.nama}</td>
                    <td>${baris.user_name}</td>
                    <td>
                        <button onclick="editPenulis(${baris.id})">Edit</button>
                        <button onclick="hapusPenulis(${baris.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(error => {
        console.error("Gagal memuat penulis:", error);
        tampilkanNotifikasi("Gagal memuat data penulis.", "gagal");
    });
}

function bukaModalPenulis() {
    document.getElementById('form-penulis').reset();
    document.getElementById('penulis_id').value = '';
    document.getElementById('password').required = true; 
    document.getElementById('modal-judul-penulis').innerText = 'Tambah Penulis';
    document.getElementById('modal-penulis').style.display = 'block';
}

function simpanPenulis() {
    const form = document.getElementById('form-penulis');
    const formData = new FormData(form);
    const id = document.getElementById('penulis_id').value;
    
    const url = id ? 'update_penulis.php' : 'simpan_penulis.php';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            tampilkanNotifikasi(data.pesan, data.status);
            if (data.status === 'sukses') {
                tutupModal('modal-penulis');
                muatPenulis();
            }
        } catch (error) {
            console.error("Format Respon Error:", text);
            tampilkanNotifikasi("Terjadi kesalahan sistem saat menyimpan penulis.", "gagal");
        }
    });
}

function editPenulis(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('ambil_satu_penulis.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sukses') {
            document.getElementById('penulis_id').value = data.data.id;
            document.getElementById('nama_depan').value = data.data.nama_depan;
            document.getElementById('nama_belakang').value = data.data.nama_belakang;
            document.getElementById('user_name').value = data.data.user_name;
            document.getElementById('password').required = false; 
            document.getElementById('modal-judul-penulis').innerText = 'Edit Penulis';
            document.getElementById('modal-penulis').style.display = 'block';
        } else {
            tampilkanNotifikasi(data.pesan, 'gagal');
        }
    });
}

function hapusPenulis(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus penulis ini?')) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('hapus_penulis.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        tampilkanNotifikasi(data.pesan, data.status);
        if (data.status === 'sukses') muatPenulis();
    });
}

// modul artikel
function muatArtikel() {
    tutupSemuaModal();
    document.getElementById('judul-halaman').innerText = 'Data Artikel';
    document.getElementById('btn-tambah').style.display = 'inline-block';
    document.getElementById('btn-tambah').innerText = 'Tambah Artikel';
    document.getElementById('btn-tambah').setAttribute('onclick', 'bukaModalArtikel()');
    document.getElementById('tabel-head').innerHTML = '<tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tanggal</th><th>Aksi</th></tr>';
    
    fetch('ambil_artikel.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('tabel-body');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" align="center">Belum ada data artikel.</td></tr>';
            return;
        }
        data.forEach(baris => {
            const gambarPath = 'uploads_artikel/' + baris.gambar;
            tbody.innerHTML += `
                <tr>
                    <td align="center"><img src="${gambarPath}" width="60" style="object-fit: cover; border-radius: 5px;"></td>
                    <td>${baris.judul}</td>
                    <td>${baris.kategori}</td>
                    <td>${baris.penulis}</td>
                    <td>${baris.hari_tanggal}</td>
                    <td>
                        <button onclick="editArtikel(${baris.id})">Edit</button>
                        <button onclick="hapusArtikel(${baris.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(error => {
        console.error("Gagal memuat artikel:", error);
        tampilkanNotifikasi("Gagal memuat data artikel.", "gagal");
    });
}

function muatDropdownArtikel(idPenulisTerpilih = null, idKategoriTerpilih = null) {
    fetch('ambil_penulis.php')
    .then(response => response.json())
    .then(data => {
        let opsi = '<option value="">Pilih Penulis</option>';
        data.forEach(p => {
            const selected = (p.id == idPenulisTerpilih) ? 'selected' : '';
            opsi += `<option value="${p.id}" ${selected}>${p.nama}</option>`;
        });
        document.getElementById('id_penulis').innerHTML = opsi;
    });

    fetch('ambil_kategori.php')
    .then(response => response.json())
    .then(data => {
        let opsi = '<option value="">Pilih Kategori</option>';
        data.forEach(k => {
            const selected = (k.id == idKategoriTerpilih) ? 'selected' : '';
            opsi += `<option value="${k.id}" ${selected}>${k.nama_kategori}</option>`;
        });
        document.getElementById('id_kategori').innerHTML = opsi;
    });
}

function bukaModalArtikel() {
    document.getElementById('form-artikel').reset();
    document.getElementById('artikel_id').value = '';
    document.getElementById('gambar').required = false; 
    document.getElementById('modal-judul-artikel').innerText = 'Tambah Artikel';
    muatDropdownArtikel(); 
    document.getElementById('modal-artikel').style.display = 'block';
}

function simpanArtikel() {
    const form = document.getElementById('form-artikel');
    const formData = new FormData(form);
    const id = document.getElementById('artikel_id').value;
    
    const url = id ? 'update_artikel.php' : 'simpan_artikel.php';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            tampilkanNotifikasi(data.pesan, data.status);
            if (data.status === 'sukses') {
                tutupModal('modal-artikel');
                muatArtikel();
            }
        } catch (error) {
            console.error("Format Respon Error:", text);
            tampilkanNotifikasi("Terjadi kesalahan sistem saat menyimpan artikel.", "gagal");
        }
    });
}

function editArtikel(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('ambil_satu_artikel.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sukses') {
            document.getElementById('artikel_id').value = data.data.id;
            document.getElementById('judul').value = data.data.judul;
            document.getElementById('isi').value = data.data.isi;
            document.getElementById('gambar').required = false; 
            document.getElementById('modal-judul-artikel').innerText = 'Edit Artikel';
            muatDropdownArtikel(data.data.id_penulis, data.data.id_kategori);
            document.getElementById('modal-artikel').style.display = 'block';
        } else {
            tampilkanNotifikasi(data.pesan, 'gagal');
        }
    });
}

function hapusArtikel(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus artikel ini? Data yang dihapus tidak dapat dikembalikan.')) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('hapus_artikel.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        tampilkanNotifikasi(data.pesan, data.status);
        if (data.status === 'sukses') muatArtikel();
    });
}

function tutupSemuaModal() {
    document.getElementById('modal-kategori').style.display = 'none';
    document.getElementById('modal-penulis').style.display = 'none';
    document.getElementById('modal-artikel').style.display = 'none';
}