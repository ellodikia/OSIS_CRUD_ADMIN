<?php
// Selalu mulai dengan session_start() agar bisa pakai $_SESSION
session_start();
// Jangan lupa include koneksi database kita
include 'koneksi.php';

// ----------------------------------------------------------------------
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk ke sini! ---
// ----------------------------------------------------------------------
// Cek, kalau user belum login atau levelnya BUKAN 'admin',
// langsung kita arahkan balik ke halaman kalender atau login.
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: kalender.php"); // Atau ke login.php, tergantung alur aplikasi Anda
    exit;
}

// ----------------------------------------------------------------------
// --- BAGIAN CREATE (Menambah Kegiatan Baru) ---
// ----------------------------------------------------------------------
// Cek apakah ada data yang dikirimkan menggunakan metode POST (biasanya dari form tambah)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // Ambil nilai 'action'

    if ($action === 'tambah_kegiatan') {
        // Ambil semua data input dari form
        $judul = $_POST['judul'] ?? '';
        $tanggal = $_POST['tanggal'] ?? '';
        $waktu = $_POST['waktu'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $penanggung_jawab = $_POST['penanggung_jawab'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // Validasi sederhana: Pastikan judul, tanggal, dan lokasi tidak kosong
        if ($judul && $tanggal && $lokasi) {
            
            // Siapkan query INSERT menggunakan Prepared Statement (wajib biar aman!)
            $stmt = $koneksi->prepare("INSERT INTO kegiatan (judul, tanggal, waktu, lokasi, penanggung_jawab, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
            
            // "ssssss" artinya 6 parameter yang akan diisi dengan data string
            $stmt->bind_param("ssssss", $judul, $tanggal, $waktu, $lokasi, $penanggung_jawab, $deskripsi);

            // Eksekusi query
            if ($stmt->execute()) {
                $_SESSION['message'] = "Kegiatan berhasil ditambahkan ke kalender! 🎉";
            } else {
                $_SESSION['error'] = "Gagal menambahkan kegiatan: " . $stmt->error;
            }
            $stmt->close(); // Tutup statement
        } else {
            $_SESSION['error'] = "Ups! Data kegiatan (judul, tanggal, lokasi) harus diisi lengkap.";
        }
    }
    // Catatan: Logika untuk UPDATE (edit) akan diletakkan di sini juga, tapi dengan action yang berbeda.

} 
// ----------------------------------------------------------------------
// --- BAGIAN DELETE (Menghapus Kegiatan) ---
// ----------------------------------------------------------------------
// Cek apakah ada permintaan yang dikirimkan menggunakan metode GET (biasanya dari link 'Hapus')
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;

    // Pastikan action-nya 'hapus' dan ID-nya ada
    if ($action === 'hapus' && $id) {
        
        // Siapkan query DELETE menggunakan Prepared Statement
        $stmt = $koneksi->prepare("DELETE FROM kegiatan WHERE id = ?");
        $stmt->bind_param("i", $id); // "i" untuk integer (ID)

        // Eksekusi query
        if ($stmt->execute()) {
            $_SESSION['message'] = "Kegiatan berhasil dihapus dari kalender. ✅";
        } else {
            $_SESSION['error'] = "Gagal menghapus kegiatan: " . $stmt->error;
        }
        $stmt->close(); // Tutup statement
    }
}

// ----------------------------------------------------------------------
// --- PENUTUP ---
// ----------------------------------------------------------------------
// Tutup koneksi database setelah semua proses selesai
$koneksi->close();

// Lalu, kita arahkan user kembali ke halaman kalender untuk melihat hasilnya
header("Location: kalender.php");
exit;
?>