<?php
session_start();
include 'koneksi.php';

// Path untuk menyimpan foto
$target_dir = "foto_galeri/";

// Pastikan folder ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Pastikan hanya admin yang bisa mengakses file ini
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'tambah_foto') {
        $judul = $_POST['judul'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';
        $file_name = $_FILES['foto_file']['name'] ?? '';
        $file_tmp = $_FILES['foto_file']['tmp_name'] ?? '';
        $file_size = $_FILES['foto_file']['size'] ?? 0;
        $file_error = $_FILES['foto_file']['error'] ?? 0;
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Pengecekan dasar file
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = "Format file tidak diizinkan. Hanya JPG, JPEG, atau PNG.";
        } elseif ($file_size > 2000000) { // Maks 2MB
            $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
        } elseif ($file_error !== 0) {
            $_SESSION['error'] = "Terjadi kesalahan saat mengupload file.";
        } else {
            // Buat nama file unik
            $new_file_name = uniqid('foto_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Simpan data ke database
                $stmt = $koneksi->prepare("INSERT INTO galeri (judul, keterangan, path_foto, tanggal_upload) VALUES (?, ?, ?, CURDATE())");
                $path_db = $target_file;
                $stmt->bind_param("sss", $judul, $keterangan, $path_db);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Foto berhasil diupload dan ditambahkan ke galeri.";
                } else {
                    // Jika gagal simpan ke DB, hapus file yang sudah diupload
                    unlink($target_file);
                    $_SESSION['error'] = "Gagal menyimpan data ke database: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Gagal memindahkan file yang diupload.";
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;
    $file_path = $_GET['file'] ?? null;

    if ($action === 'hapus' && $id && $file_path) {
        
        // 1. Hapus entri dari database
        $stmt = $koneksi->prepare("DELETE FROM galeri WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // 2. Hapus file fisik dari server
            if (file_exists($file_path) && is_file($file_path)) {
                if (unlink($file_path)) {
                    $_SESSION['message'] = "Foto dan data berhasil dihapus.";
                } else {
                    $_SESSION['error'] = "Data berhasil dihapus dari DB, namun gagal menghapus file fisik.";
                }
            } else {
                 $_SESSION['message'] = "Foto berhasil dihapus dari DB, namun file fisik tidak ditemukan di server.";
            }
        } else {
            $_SESSION['error'] = "Gagal menghapus foto dari database: " . $stmt->error;
        }
        $stmt->close();
    }
}

$koneksi->close();
header("Location: gallery.php");
exit;
?>