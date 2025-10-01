<?php
// Tampilkan semua error untuk debugging (WAJIB DILAKUKAN SAAT LOKAL)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php'; 

// Debug: Log semua request
error_log("CRUD_GALERI: Request Method = " . $_SERVER['REQUEST_METHOD']);
error_log("CRUD_GALERI: GET Data = " . print_r($_GET, true));
error_log("CRUD_GALERI: POST Data = " . print_r($_POST, true));

// Cek koneksi di sini untuk memastikan tidak ada masalah
if (!isset($koneksi) || $koneksi->connect_error) {
    die("Koneksi database GAGAL TOTAL: " . $koneksi->connect_error);
}

// Path (lokasi folder) tempat kita menyimpan foto-foto galeri
$target_dir = "foto_galeri/";

// Cek dan buat folder jika belum ada
if (!file_exists($target_dir)) {
    // Izin 0777 diperlukan di lokal agar PHP bisa menulis/menghapus
    if (!mkdir($target_dir, 0777, true)) {
        die("GAGAL membuat folder: " . $target_dir);
    }
}

// ----------------------------------------------------------------------
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk ---
// ----------------------------------------------------------------------
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    $_SESSION['error'] = "Akses ditolak. Hanya admin yang boleh mengakses.";
    header("Location: login.php");
    exit;
}

// ----------------------------------------------------------------------
// --- BAGIAN CREATE (Tambah Foto Baru) ---
// ----------------------------------------------------------------------
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

        $allowed_ext = ['jpg', 'jpeg', 'png']; 
        
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = "Format file tidak diizinkan. Hanya JPG, JPEG, atau PNG yang boleh ya.";
        } elseif ($file_size > 2000000) { 
            $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB saja.";
        } elseif ($file_error !== 0) {
            $_SESSION['error'] = "Terjadi kesalahan saat mengupload file.";
        } else {
            $new_file_name = uniqid('foto_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_file_name; 

            if (move_uploaded_file($file_tmp, $target_file)) {
                $stmt = $koneksi->prepare("INSERT INTO galeri (judul, keterangan, path_foto, tanggal_upload) VALUES (?, ?, ?, CURDATE())");
                $path_db = $target_file; 
                $stmt->bind_param("sss", $judul, $keterangan, $path_db);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Foto **berhasil** diupload dan ditambahkan ke galeri. 🎉";
                } else {
                    unlink($target_file); 
                    $_SESSION['error'] = "Gagal menyimpan data ke database: " . $stmt->error;
                }
                $stmt->close(); 
            } else {
                $_SESSION['error'] = "Gagal memindahkan file yang diupload. Cek izin folder!";
            }
        }
    }
} 
// ----------------------------------------------------------------------
// --- BAGIAN DELETE (Hapus Foto) --- 
// ----------------------------------------------------------------------
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;

    error_log("CRUD_GALERI: Action = $action, ID = $id");

    if ($action === 'hapus' && $id) {
        $id = (int)$id; 
        error_log("CRUD_GALERI: Memproses hapus untuk ID = $id");

        // Ambil file path yang benar dan aman dari database
        $stmt_path = $koneksi->prepare("SELECT path_foto FROM galeri WHERE id = ?");
        if (!$stmt_path) {
            die("ERROR Prepare Statement: " . $koneksi->error);
        }
        
        $stmt_path->bind_param("i", $id);
        
        if (!$stmt_path->execute()) {
            die("ERROR SQL [Ambil Path]: " . $stmt_path->error);
        }
        
        $result_path = $stmt_path->get_result();
        $path_data = $result_path->fetch_assoc();
        $stmt_path->close();
        
        if ($path_data) {
            $path_to_delete = $path_data['path_foto'];
            error_log("CRUD_GALERI: Path file = $path_to_delete");

            // Hapus entri dari database
            $stmt_delete = $koneksi->prepare("DELETE FROM galeri WHERE id = ?");
            if (!$stmt_delete) {
                die("ERROR Prepare Statement Delete: " . $koneksi->error);
            }
            
            $stmt_delete->bind_param("i", $id); 

            if ($stmt_delete->execute()) {
                error_log("CRUD_GALERI: Berhasil hapus dari database");
                
                // Hapus file fisik dari server
                if (!empty($path_to_delete) && file_exists($path_to_delete) && is_file($path_to_delete)) {
                    if (unlink($path_to_delete)) {
                        $_SESSION['message'] = "Foto dan data berhasil dihapus. ✅";
                        error_log("CRUD_GALERI: Berhasil hapus file fisik");
                    } else {
                        $_SESSION['error'] = "Data berhasil dihapus dari DB, **NAMUN GAGAL MENGHAPUS FILE FISIK**. Cek perizinan folder!";
                        error_log("CRUD_GALERI: Gagal hapus file fisik");
                    }
                } else {
                    $_SESSION['message'] = "Foto berhasil dihapus dari DB. File fisik tidak ditemukan/sudah terhapus.";
                    error_log("CRUD_GALERI: File fisik tidak ditemukan");
                }
            } else {
                $_SESSION['error'] = "Gagal menghapus data dari database: " . $stmt_delete->error;
                error_log("CRUD_GALERI: Gagal hapus dari database: " . $stmt_delete->error);
            }
            $stmt_delete->close(); 

        } else {
            $_SESSION['error'] = "Data foto dengan ID $id tidak ditemukan.";
            error_log("CRUD_GALERI: Data tidak ditemukan untuk ID = $id");
        }
    } else {
        $_SESSION['error'] = "Parameter tidak valid untuk aksi hapus.";
        error_log("CRUD_GALERI: Parameter tidak valid");
    }
}

// ----------------------------------------------------------------------
// --- PENUTUP ---
// ----------------------------------------------------------------------
$koneksi->close();

header("Location: gallery.php");
exit;
?>