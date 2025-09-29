<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses file ini
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: kalender.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'tambah_kegiatan') {
        $judul = $_POST['judul'] ?? '';
        $tanggal = $_POST['tanggal'] ?? '';
        $waktu = $_POST['waktu'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $penanggung_jawab = $_POST['penanggung_jawab'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        if ($judul && $tanggal && $lokasi) {
            $stmt = $koneksi->prepare("INSERT INTO kegiatan (judul, tanggal, waktu, lokasi, penanggung_jawab, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $judul, $tanggal, $waktu, $lokasi, $penanggung_jawab, $deskripsi);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Kegiatan berhasil ditambahkan.";
            } else {
                $_SESSION['error'] = "Gagal menambahkan kegiatan: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Data kegiatan tidak lengkap.";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;

    if ($action === 'hapus' && $id) {
        $stmt = $koneksi->prepare("DELETE FROM kegiatan WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Kegiatan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus kegiatan: " . $stmt->error;
        }
        $stmt->close();
    }
}

$koneksi->close();

// Redirect kembali ke halaman kalender
header("Location: kalender.php");
exit;
?>