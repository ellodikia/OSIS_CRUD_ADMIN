<?php
// crud_pengurus.php

session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    // header("Location: login.php");
    // exit();
}

// Lokasi folder untuk menyimpan foto
$folder_upload = "uploads/pengurus/";

// Buat folder jika belum ada
if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0777, true);
}

// --- FUNGSI CREATE (Tambah Data) ---
if (isset($_POST['action']) && $_POST['action'] == 'tambah_pengurus') {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $visi_misi = $_POST['visi_misi'];
    $nama_file_foto = '';

    // Proses upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = basename($_FILES['foto']['name']);
        
        // Amankan nama file dan tambahkan timestamp
        $nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file_name);
        
        if (move_uploaded_file($file_tmp, $folder_upload . $nama_baru)) {
            $nama_file_foto = $nama_baru;
        } else {
            echo "<script>alert('Gagal mengupload foto.'); window.location='struktur.php';</script>";
            exit();
        }
    }

    $stmt = $koneksi->prepare("INSERT INTO pengurus (nama, jabatan, foto, visi_misi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $jabatan, $nama_file_foto, $visi_misi);
    
    if ($stmt->execute()) {
        echo "<script>alert('Pengurus berhasil ditambahkan!'); window.location='struktur.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pengurus: " . $stmt->error . "'); window.location='struktur.php';</script>";
    }
    $stmt->close();
    exit();
}


// --- FUNGSI DELETE (Hapus Data) ---
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Ambil nama file foto
    $result = $koneksi->query("SELECT foto FROM pengurus WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = $folder_upload . $row['foto'];
        
        // 2. Hapus file foto dari folder
        if (file_exists($file_to_delete) && !empty($row['foto'])) {
            unlink($file_to_delete);
        }
    }

    // 3. Hapus data dari database
    $stmt = $koneksi->prepare("DELETE FROM pengurus WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Pengurus berhasil dihapus!'); window.location='struktur.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pengurus: " . $stmt->error . "'); window.location='struktur.php';</script>";
    }
    $stmt->close();
    exit();
}

$koneksi->close();
?>