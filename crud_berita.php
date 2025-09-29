<?php
include 'koneksi.php';

// Lokasi folder untuk menyimpan foto berita
$folder_upload = "foto_berita/"; 

// Buat folder jika belum ada (pastikan folder_berita/ berada di direktori yang sama dengan crud_berita.php)
if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0777, true);
}


// --- CREATE (Tambah Data) ---
if (isset($_POST['action']) && $_POST['action'] == 'tambah_berita') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $level = $_POST['level'];
    $tanggal = date("Y-m-d");
    $nama_file_foto = ''; // Default jika tidak ada upload

    // 1. PROSES UPLOAD GAMBAR
    if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
        $file_tmp = $_FILES['foto_berita']['tmp_name'];
        $file_name = basename($_FILES['foto_berita']['name']);
        
        // Amankan nama file dengan timestamp untuk menghindari duplikasi
        $nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file_name);
        $target_file = $folder_upload . $nama_baru;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $nama_file_foto = $nama_baru;
        } else {
            // Gagal memindahkan file (misalnya izin folder)
            header("Location: index_admin.php?status=error_upload_move");
            exit();
        }
    }
    // END PROSES UPLOAD GAMBAR

    // 2. QUERY KE DATABASE (TAMBAHKAN KOLOM 'foto')
    // Perhatikan: sssss untuk 5 string (judul, isi, tanggal, level, foto)
    $stmt = $koneksi->prepare("INSERT INTO berita (judul, isi, tanggal_publikasi, level, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $judul, $isi, $tanggal, $level, $nama_file_foto);
    
    if ($stmt->execute()) {
        header("Location: index_admin.php?status=success_add");
    } else {
        // Jika gagal simpan ke DB, hapus file yang sudah terlanjur terupload
        if (!empty($nama_file_foto) && file_exists($folder_upload . $nama_file_foto)) {
            unlink($folder_upload . $nama_file_foto);
        }
        header("Location: index_admin.php?status=error_add");
    }
    exit();
}

// --- DELETE (Hapus Data) ---
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Ambil nama file foto lama sebelum menghapus data
    $result = $koneksi->query("SELECT foto FROM berita WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = $folder_upload . $row['foto'];
        
        // 2. Hapus file foto dari folder jika ada
        if (file_exists($file_to_delete) && !empty($row['foto'])) {
            unlink($file_to_delete);
        }
    }

    // 3. Hapus data dari database
    $stmt = $koneksi->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index_admin.php?status=success_delete");
    } else {
        header("Location: index_admin.php?status=error_delete");
    }
    exit();
}

// Tambahkan logika UPDATE di sini jika Anda membuat form edit terpisah
// ...
?>