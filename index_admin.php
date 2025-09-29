<?php
session_start();

// PERIKSA APAKAH USER SUDAH LOGIN SEBAGAI ADMIN
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil Berita (limit 3)
$sql_berita = "SELECT * FROM berita WHERE level='berita' ORDER BY tanggal_publikasi DESC LIMIT 3";
$berita_result = $koneksi->query($sql_berita);

// Ambil Pengumuman (limit 3)
$sql_pengumuman = "SELECT * FROM berita WHERE level='pengumuman' ORDER BY tanggal_publikasi DESC LIMIT 3";
$pengumuman_result = $koneksi->query($sql_pengumuman);
?>   
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Raksana - Admin</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        .admin-badge {
            background: #800000;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .crud-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px solid #800000;
        }
        .crud-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .crud-form input, .crud-form textarea, .crud-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-primary {
            background: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #a00000;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container header__container">
            <div class="header__logo-container">
                <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" class="header__logo">
                <img src="foto/logo-osis.png" alt="Logo OSIS" class="header__logo">
            </div>
            
            <nav class="nav">
                <a href="index_admin.php" class="nav__link">Beranda</a>
                <a href="struktur.php" class="nav__link">Struktur OSIS</a>
                <a href="kalender.php" class="nav__link">Kalender Kegiatan</a>
                <a href="gallery.php" class="nav__link active">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>
                <a href="logout.php" class="nav__link">Logout</a>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero__content">
            <h1 class="hero__title">Selamat Datang di Website OSIS - Admin Panel</h1>
            <p class="hero__description">Anda login sebagai Administrator. Mode CRUD aktif.</p>
            <a href="#main-content" class="btn btn--primary">Kelola Konten</a>
        </div>
    </section>
<!-- News Section dengan Style Admin -->
<section class="section">
    <div class="container">
        <h2 class="section__title">
            Berita Terkini 
            <span class="admin-badge"><i class="fas fa-user-shield"></i> Admin Mode</span>
        </h2>
        
        <!-- Admin Controls -->
        <div class="admin-controls">
            <a href="#crud-form" class="admin-control-btn">
            </a>
            <a href="news.php" class="admin-control-btn">
            </a>
        </div>
        
        <div class="news-grid admin-mode">
            <?php while($row = $berita_result->fetch_assoc()): ?>
            <article class="news-card">
                <span class="news-card__type">
                    <?= $row['level'] === 'berita' ? '' : '📢 Pengumuman' ?>
                </span>
                
                <?php if(!empty($row['foto'])): ?>
                    <img src="uploads/berita/<?= htmlspecialchars($row['foto']) ?>" 
                         alt="<?= htmlspecialchars($row['judul']) ?>" 
                         class="news-card__image">
                <?php else: ?>
                    <img src="foto/1.jpg" 
                         alt="Default Image" 
                         class="news-card__image">
                <?php endif; ?>
                
                <div class="news-card__content">
                    <span class="news-card__date">
                        <i class="fas fa-calendar-alt"></i> 
                        <?= date('d F Y', strtotime($row['tanggal_publikasi'])) ?>
                    </span>
                    <h3 class="news-card__title"><?= htmlspecialchars($row['judul']) ?></h3>
                    <p class="news-card__excerpt">
                        <?= nl2br(htmlspecialchars(substr($row['isi'], 0, 150))) ?>...
                    </p>
                    
                    <div class="news-card__actions">
                        <a href="news.php?id=<?= $row['id'] ?>" class="news-card__btn news-card__btn--primary">
                        </a>
                        <a href="crud_berita.php?action=edit&id=<?= $row['id'] ?>" class="news-card__btn" style="background: #ffc107; color: black;">
                        </a>
                        <a href="crud_berita.php?action=hapus&id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus berita ini?')" 
                           class="news-card__btn news-card__btn--danger">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </a>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        
        <!-- CRUD Form dengan Upload Foto -->
        <div class="crud-form" id="crud-form">
            <h3><i class="fas fa-plus-circle"></i> Tambah Konten Baru</h3>
            <form action="crud_berita.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_berita">
                
                <div class="form-group">
                    <label for="judul"><i class="fas fa-heading"></i> Judul:</label>
                    <input type="text" id="judul" name="judul" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="isi"><i class="fas fa-align-left"></i> Isi Konten:</label>
                    <textarea id="isi" name="isi" class="form-control" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="foto"><i class="fas fa-image"></i> Upload Foto:</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <small class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                </div>
                
                <div class="form-group">
                    <label for="level"><i class="fas fa-tag"></i> Jenis Konten:</label>
                    <select id="level" name="level" class="form-control" required>
                        <option value="berita">📰 Berita Terkini</option>
                        <option value="pengumuman">📢 Pengumuman Penting</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Konten
                </button>
            </form>
        </div>
    </div>
</section>
        
        <!-- Quick Links Section -->
        <section class="section">
            <div class="container">
                <h2 class="section__title">Menu Cepat</h2>
                <div class="quick-links">
                    <a href="struktur.php" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="quick-link-card__title">Profil OSIS</h3>
                        <p class="quick-link-card__description">Kenali struktur dan program kerja OSIS kami</p>
                    </a>
                    
                    <a href="kalender.php" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="quick-link-card__title">Kalender Kegiatan</h3>
                        <p class="quick-link-card__description">Kelola jadwal kegiatan OSIS</p>
                    </a>
                    
                    <a href="gallery.php" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="quick-link-card__title">Galeri Foto</h3>
                        <p class="quick-link-card__description">Kelola galeri kegiatan OSIS</p>
                    </a>
                    
                    <a href="news.php" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3 class="quick-link-card__title">Berita & Pengumuman</h3>
                        <p class="quick-link-card__description">Kelola berita dan pengumuman</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <!-- About Column -->
                <div class="footer__column">
                    <h3>Tentang OSIS</h3>
                    <p>Organisasi Siswa Intra Sekolah (OSIS) merupakan organisasi resmi sekolah yang bertujuan untuk mengembangkan potensi siswa dan menyalurkan aspirasi siswa.</p>
                </div>
                
                <!-- Contact Column -->
                <div class="footer__column">
                    <h3>Kontak Kami</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jl. Gajah Mada No. 20 Medan, Sumatera Utara, Indonesia</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>osisraksana@sch.id</span>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Column -->
                <div class="footer__column">
                    <h3>Media Sosial</h3>
                    <p>Ikuti kami di media sosial untuk informasi terbaru</p>
                    <div class="social-links">
                        <a href="https://www.instagram.com/osisraksanamdn/" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2025 OSIS Yayasan Pendidikan Raksana. Semua Hak Cipta Dilindungi. | <strong>Admin Mode</strong></p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/index.js"></script>
</body>
</html>