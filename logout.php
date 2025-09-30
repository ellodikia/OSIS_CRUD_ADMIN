<?php
// logout.php

// 1. Pastikan sesi dimulai (selalu harus ada di awal)
session_start();

// 2. Hancurkan semua variabel sesi
// Ini akan menghapus data yang tersimpan di dalam $_SESSION (misal: 'username', 'level')
$_SESSION = array();

// 3. Hapus Cookie Sesi (opsional, tapi disarankan)
// Ini adalah cara untuk menghapus cookie yang menyimpan ID sesi di browser pengguna.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // Nama cookie sesi (biasanya PHPSESSID)
        '',             // Nilai diubah menjadi kosong
        time() - 42000, // Waktu kedaluwarsa diatur ke masa lalu (42000 detik yang lalu)
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Hancurkan sesi di server
// Ini akan menghapus file sesi di direktori penyimpanan sesi server
session_destroy();

// 5. Arahkan kembali pengguna ke halaman login
header("Location: login.php");
exit; // Selalu panggil exit setelah header Location
?>