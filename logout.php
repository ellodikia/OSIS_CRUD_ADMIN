<?php
// Pastikan sesi dimulai
session_start();

// Hancurkan semua variabel sesi
$_SESSION = array();

// Jika menggunakan cookie sesi, hapus juga cookie-nya
// Catatan: Ini akan menghancurkan cookie sesi, bukan cookie biasa
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Terakhir, hancurkan sesi
session_destroy();

// Arahkan kembali ke halaman login
header("Location: login.php");
exit;
?>