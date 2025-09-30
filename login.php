<?php
// login.php
session_start();

// --- Konfigurasi Login (HARAP GANTI DENGAN QUERY DATABASE UNTUK KEAMANAN NYATA) ---
$admin_username = "admin123";
// Di aplikasi nyata, 'osis123' harus di-hash (misal: password_hash('osis123', PASSWORD_DEFAULT))
$admin_password = "osis123"; 

$error_message = '';

if (isset($_POST['admin']) && isset($_POST['password'])) {
    $input_admin = $_POST['admin'];
    $input_password = $_POST['password'];

    // Cek Username dan Password
    // PENTING: Dalam aplikasi nyata, Anda harus menggunakan password_verify()
    if ($input_admin === $admin_username && $input_password === $admin_password) {
        $_SESSION['level'] = 'admin';
        
        // Redirect ke halaman admin
        header("Location: index_admin.php");
        exit;
    } else {
        // Simpan pesan error ke sesi untuk ditampilkan sekali saja
        $_SESSION['error'] = "Username atau password salah! Silakan coba lagi.";
        // Refresh halaman untuk membersihkan data POST dan menampilkan pesan error
        header("Location: login.php");
        exit;
    }
}

// Ambil pesan error dari sesi jika ada
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Hapus pesan setelah ditampilkan
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - OSIS Raksana</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #800000; /* Merah Marun */
            --secondary-color: #f0f0f0;
            --text-color: #333;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--secondary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        /* --- Login Card --- */
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 380px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }

        /* --- Header & Logo --- */
        .header-login {
            margin-bottom: 25px;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .logo-container img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 50%;
        }
        .header-login h1 {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        /* --- Form Elements --- */
        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.95rem;
        }
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .input-group input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        /* --- Button --- */
        .btn-login {
            width: 100%;
            background-color: var(--primary-color);
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.05rem;
            transition: background-color 0.3s, transform 0.1s;
        }
        .btn-login:hover {
            background-color: #a00000;
            transform: translateY(-2px);
        }
        
        /* --- Links & Alerts --- */
        .link-back {
            display: block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .link-back:hover {
            color: var(--primary-color);
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="header-login">
            <div class="logo-container">
                <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" onerror="this.style.display='none'">
                <img src="foto/logo-osis.png" alt="Logo OSIS" onerror="this.style.display='none'">
            </div>
            <h1>Akses Admin OSIS</h1>
        </div>

        <?php if ($error_message): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
                <label for="admin">Username Admin</label>
                <input type="text" name="admin" id="admin" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
            
            <a href="index.php" class="link-back">
                <i class="fas fa-chevron-left"></i> Kembali ke Halaman Utama
            </a>
        </form>
    </div>
</body>
</html>