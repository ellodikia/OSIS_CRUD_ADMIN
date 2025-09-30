<?php
// Selalu mulai dengan session_start() untuk bisa pakai $_SESSION
session_start();
// Jangan lupa include file koneksi ke database kita!
include 'koneksi.php';

// Path (lokasi folder) tempat kita mau simpan foto-foto galeri
$target_dir = "foto_galeri/";

// Cek, kalau foldernya belum ada, kita buatkan!
// Kita kasih izin akses penuh (0777) dan 'true' agar bisa buat folder induk juga (jika perlu)
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// ----------------------------------------------------------------------
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk ke file CRUD ini! ---
// ----------------------------------------------------------------------
// Cek apakah user sudah login dan levelnya BUKAN 'admin'.
// Kalau iya, tendang balik ke halaman login.
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ----------------------------------------------------------------------
// --- BAGIAN CREATE (Tambah Foto Baru) ---
// ----------------------------------------------------------------------
// Cek apakah ada data yang dikirimkan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil nilai 'action' dari POST, defaultnya kosong kalau nggak ada
    $action = $_POST['action'] ?? '';

    if ($action === 'tambah_foto') {
        // Ambil data-data dari form
        $judul = $_POST['judul'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';
        
        // Ambil info detail tentang file yang diupload
        $file_name = $_FILES['foto_file']['name'] ?? '';
        $file_tmp = $_FILES['foto_file']['tmp_name'] ?? '';
        $file_size = $_FILES['foto_file']['size'] ?? 0;
        $file_error = $_FILES['foto_file']['error'] ?? 0;
        // Ambil ekstensi file-nya dan ubah jadi huruf kecil (misal: JPG jadi jpg)
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Pengecekan Keamanan dan Validasi File
        $allowed_ext = ['jpg', 'jpeg', 'png']; // Ekstensi yang diizinkan
        
        // Cek ekstensi file
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = "Format file tidak diizinkan. Hanya JPG, JPEG, atau PNG yang boleh ya.";
        } 
        // Cek ukuran file (2000000 bytes = 2MB)
        elseif ($file_size > 2000000) { 
            $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB saja.";
        } 
        // Cek apakah ada error saat proses upload (seperti file korup, dll)
        elseif ($file_error !== 0) {
            $_SESSION['error'] = "Terjadi kesalahan saat mengupload file.";
        } else {
            // Kalau semua cek lolos, kita lanjutkan proses upload dan simpan ke DB

            // 1. Buat nama file yang unik banget (pakai uniqid() agar tidak ada duplikasi)
            $new_file_name = uniqid('foto_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_file_name; // Path lengkap tujuan file

            // 2. Pindahkan file dari folder sementara ke folder tujuan kita
            if (move_uploaded_file($file_tmp, $target_file)) {
                
                // 3. Simpan data ke database menggunakan Prepared Statement (biar aman!)
                $stmt = $koneksi->prepare("INSERT INTO galeri (judul, keterangan, path_foto, tanggal_upload) VALUES (?, ?, ?, CURDATE())");
                // Path yang akan disimpan di DB adalah $target_file (misal: "foto_galeri/foto_5f7c3...")
                $path_db = $target_file; 
                // "sss" artinya 3 parameter string
                $stmt->bind_param("sss", $judul, $keterangan, $path_db);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Foto **berhasil** diupload dan ditambahkan ke galeri. 🎉";
                } else {
                    // Kalau gagal simpan ke DB, file yang sudah terlanjur diupload harus DIBUANG!
                    unlink($target_file);
                    $_SESSION['error'] = "Gagal menyimpan data ke database: " . $stmt->error;
                }
                $stmt->close(); // Tutup statement
            } else {
                $_SESSION['error'] = "Gagal memindahkan file yang diupload. Cek izin folder!";
            }
        }
    }
} 
// ----------------------------------------------------------------------
// --- BAGIAN DELETE (Hapus Foto) ---
// ----------------------------------------------------------------------
// Cek apakah ada permintaan yang dikirimkan menggunakan metode GET
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;
    $file_path = $_GET['file'] ?? null; // Kita ambil path_foto-nya juga dari GET

    // Pastikan action-nya 'hapus' dan ID serta path file ada
    if ($action === 'hapus' && $id && $file_path) {
        
        // 1. Hapus entri dari database dulu
        $stmt = $koneksi->prepare("DELETE FROM galeri WHERE id = ?");
        $stmt->bind_param("i", $id); // "i" untuk integer (ID)

        if ($stmt->execute()) {
            // Kalau hapus dari DB berhasil, lanjut ke langkah 2
            
            // 2. Hapus file fisik dari server
            // Cek apakah filenya benar-benar ada di path yang diberikan
            if (file_exists($file_path) && is_file($file_path)) {
                if (unlink($file_path)) {
                    $_SESSION['message'] = "Foto dan data berhasil dihapus. ✅";
                } else {
                    // Beri pesan kalau file fisik gagal dihapus (misalnya izin folder)
                    $_SESSION['error'] = "Data berhasil dihapus dari DB, namun gagal menghapus file fisik. Tolong hapus manual.";
                }
            } else {
                 // Beri pesan kalau data DB terhapus, tapi filenya memang nggak ada
                 $_SESSION['message'] = "Foto berhasil dihapus dari DB, namun file fisik tidak ditemukan di server. (Sudah beres!)";
            }
        } else {
            $_SESSION['error'] = "Gagal menghapus foto dari database: " . $stmt->error;
        }
        $stmt->close(); // Tutup statement
    }
}

// ----------------------------------------------------------------------
// --- PENUTUP ---
// ----------------------------------------------------------------------
// Setelah selesai memproses semua aksi (tambah/hapus), tutup koneksi database
$koneksi->close();

// Lalu, kita arahkan user kembali ke halaman galeri (gallery.php)
header("Location: gallery.php");
exit;
?>