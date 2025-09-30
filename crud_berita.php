<?php
// Pastikan file 'koneksi.php' sudah di-include untuk koneksi ke database
include 'koneksi.php';

// Lokasi folder tempat kita akan menyimpan semua foto berita
$folder_upload = "foto_berita/"; 

// Cek, kalau foldernya belum ada, kita buatkan!
// Kita kasih izin akses 0777 (izin penuh) dan 'true' agar bisa buat folder induk juga (jika perlu)
if (!is_dir($folder_upload)) {
    // Parameter ketiga 'true' untuk rekursif, bikin folder induk jika belum ada
    mkdir($folder_upload, 0777, true);
}


// ----------------------------------------------------------------------
// --- BAGIAN CREATE (Menambah Data Berita Baru) ---
// ----------------------------------------------------------------------
// Cek apakah form tambah berita sudah disubmit (dengan POST action 'tambah_berita')
if (isset($_POST['action']) && $_POST['action'] == 'tambah_berita') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $level = $_POST['level'];
    // Tanggal publikasi otomatis diambil saat ini
    $tanggal = date("Y-m-d"); 
    // Inisialisasi nama file foto, defaultnya kosong
    $nama_file_foto = ''; 

    // 1. PROSES UPLOAD GAMBAR/FOTO
    // Cek apakah ada file foto yang diupload dan tidak ada error
    if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
        $file_tmp = $_FILES['foto_berita']['tmp_name']; // Lokasi sementara file
        $file_name = basename($_FILES['foto_berita']['name']); // Nama asli file
        
        // Agar nama file aman dan tidak ada yang kembar/duplikat, 
        // kita tambahkan timestamp di depannya. Kita juga bersihkan nama file.
        $nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file_name);
        $target_file = $folder_upload . $nama_baru; // Lokasi tujuan akhir

        // Coba pindahkan file yang diupload dari folder sementara ke folder tujuan kita
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Kalau berhasil, simpan nama file yang sudah aman
            $nama_file_foto = $nama_baru;
        } else {
            // Kalau gagal pindah file (mungkin masalah izin folder), balik ke halaman admin dengan pesan error
            header("Location: index_admin.php?status=error_upload_move");
            exit();
        }
    }
    // END PROSES UPLOAD GAMBAR

    // 2. QUERY KE DATABASE (Simpan semua data, termasuk nama foto)
    // Kita pakai Prepared Statement untuk keamanan dari SQL Injection
    // "sssss" berarti 5 parameter string (?, ?, ?, ?, ?)
    $stmt = $koneksi->prepare("INSERT INTO berita (judul, isi, tanggal_publikasi, level, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $judul, $isi, $tanggal, $level, $nama_file_foto);
    
    // Jalankan query
    if ($stmt->execute()) {
        // Kalau berhasil, kita arahkan balik ke halaman admin dengan status sukses
        header("Location: index_admin.php?status=success_add");
    } else {
        // Kalau gagal simpan ke DB, kita harus hapus lagi foto yang sudah terlanjur terupload tadi (biar enggak nyampah)
        if (!empty($nama_file_foto) && file_exists($folder_upload . $nama_file_foto)) {
            unlink($folder_upload . $nama_file_foto);
        }
        // Arahkan balik ke halaman admin dengan status error
        header("Location: index_admin.php?status=error_add");
    }
    exit(); // Penting: Hentikan eksekusi script setelah redirect
}

// ----------------------------------------------------------------------
// --- BAGIAN DELETE (Menghapus Data Berita) ---
// ----------------------------------------------------------------------
// Cek apakah ada permintaan hapus (action=hapus dan ada ID)
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Ambil nama file foto lama dulu sebelum datanya dihapus
    $result = $koneksi->query("SELECT foto FROM berita WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = $folder_upload . $row['foto'];
        
        // 2. Hapus file foto dari folder 'foto_berita' jika memang ada file fotonya
        if (file_exists($file_to_delete) && !empty($row['foto'])) {
            unlink($file_to_delete);
        }
    }

    // 3. Hapus data beritanya dari database
    // Pakai Prepared Statement lagi untuk keamanan
    $stmt = $koneksi->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" untuk integer (ID)
    
    // Jalankan query hapus
    if ($stmt->execute()) {
        header("Location: index_admin.php?status=success_delete");
    } else {
        header("Location: index_admin.php?status=error_delete");
    }
    exit(); // Penting: Hentikan eksekusi script setelah redirect
}

// ----------------------------------------------------------------------
// --- BAGIAN UPDATE (Edit Data) ---
// ----------------------------------------------------------------------
?>