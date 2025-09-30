<?php
// crud_pengurus.php

// Selalu mulai dengan session_start()
session_start();
// Include koneksi ke database kita
include 'koneksi.php';

// ----------------------------------------------------------------------
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk! ---
// ----------------------------------------------------------------------
// Kita cek, kalau user belum login atau levelnya bukan 'admin',
// langsung kita arahkan balik ke halaman login/lainnya.
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    // Saat ini baris di-nonaktifkan, tapi kalau mau dipakai tinggal hilangkan //
    // header("Location: login.php");
    // exit();
}

// Lokasi folder tempat kita akan menyimpan foto-foto pengurus
$folder_upload = "uploads/pengurus/";

// Cek, kalau foldernya belum ada, kita buatkan!
if (!is_dir($folder_upload)) {
    // Kita kasih izin akses 0777 (izin penuh)
    mkdir($folder_upload, 0777, true);
}

// ----------------------------------------------------------------------
// --- FUNGSI CREATE (Tambah Data Pengurus Baru) ---
// ----------------------------------------------------------------------
// Cek apakah form tambah pengurus sudah disubmit (action 'tambah_pengurus')
if (isset($_POST['action']) && $_POST['action'] == 'tambah_pengurus') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $visi_misi = $_POST['visi_misi'];
    $nama_file_foto = ''; // Default jika tidak ada upload

    // 1. PROSES UPLOAD FOTO
    // Cek apakah ada file foto yang diupload dan tidak ada error
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = basename($_FILES['foto']['name']);
        
        // Amankan nama file dan tambahkan timestamp agar unik dan menghindari duplikasi
        $nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file_name);
        
        // Coba pindahkan file
        if (move_uploaded_file($file_tmp, $folder_upload . $nama_baru)) {
            $nama_file_foto = $nama_baru;
        } else {
            // Kalau gagal pindah file, kasih pesan error dan hentikan proses
            echo "<script>alert('Gagal mengupload foto. Cek izin folder!'); window.location='struktur.php';</script>";
            exit();
        }
    }

    // 2. QUERY KE DATABASE
    // Kita pakai Prepared Statement untuk keamanan dari SQL Injection
    $stmt = $koneksi->prepare("INSERT INTO pengurus (nama, jabatan, foto, visi_misi) VALUES (?, ?, ?, ?)");
    // "ssss" berarti 4 parameter string
    $stmt->bind_param("ssss", $nama, $jabatan, $nama_file_foto, $visi_misi);
    
    if ($stmt->execute()) {
        // Kalau berhasil, kasih alert sukses
        echo "<script>alert('Pengurus berhasil ditambahkan!'); window.location='struktur.php';</script>";
    } else {
        // Kalau gagal simpan ke DB, kasih alert error
        echo "<script>alert('Gagal menambahkan pengurus: " . $stmt->error . "'); window.location='struktur.php';</script>";
    }
    $stmt->close();
    exit(); // Hentikan script setelah selesai proses POST

}


// ----------------------------------------------------------------------
// --- FUNGSI DELETE (Hapus Data Pengurus) ---
// ----------------------------------------------------------------------
// Cek apakah ada permintaan hapus (action=hapus dan ada ID)
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Ambil nama file foto lama dulu sebelum datanya dihapus
    $result = $koneksi->query("SELECT foto FROM pengurus WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = $folder_upload . $row['foto'];
        
        // 2. Hapus file foto dari folder jika ada
        if (file_exists($file_to_delete) && !empty($row['foto'])) {
            unlink($file_to_delete);
        }
    }

    // 3. Hapus data dari database
    $stmt = $koneksi->prepare("DELETE FROM pengurus WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" untuk integer (ID)
    
    if ($stmt->execute()) {
        // Kalau berhasil, kasih alert sukses
        echo "<script>alert('Pengurus berhasil dihapus!'); window.location='struktur.php';</script>";
    } else {
        // Kalau gagal, kasih alert error
        echo "<script>alert('Gagal menghapus pengurus: " . $stmt->error . "'); window.location='struktur.php';</script>";
    }
    $stmt->close();
    exit(); // Hentikan script setelah selesai proses GET
}

// ----------------------------------------------------------------------
// --- PENUTUP ---
// ----------------------------------------------------------------------
// Tutup koneksi database
$koneksi->close();

// Catatan: Logika untuk UPDATE (edit) pengurus tidak ada di kode awal, 
// jadi tidak ditambahkan di sini.
?>