<?php
// index.php
session_start();

// ----------------------------------------------------------------------
// --- PROTEKSI ADMIN: Langsung alihkan admin ke dashboard mereka ---
// ----------------------------------------------------------------------
if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin') {
    // Jika user adalah admin, kita bawa dia ke halaman khusus admin.
    header("Location: index_admin.php");
    exit;
}

// Include koneksi database
include 'koneksi.php'; 

// ----------------------------------------------------------------------
// --- DATA FETCHING (Mengambil Data) ---
// ----------------------------------------------------------------------

// Ambil 3 Berita terbaru (level='berita')
$sql_berita = "SELECT id, judul, isi, foto, tanggal_publikasi FROM berita WHERE level='berita' ORDER BY tanggal_publikasi DESC LIMIT 3";
$berita_result = $koneksi->query($sql_berita);

// Ambil 3 Pengumuman terbaru (level='pengumuman')
$sql_pengumuman = "SELECT id, judul, isi, tanggal_publikasi FROM berita WHERE level='pengumuman' ORDER BY tanggal_publikasi DESC LIMIT 3";
$pengumuman_result = $koneksi->query($sql_pengumuman);

// Tutup koneksi (kebiasaan bagus)
$koneksi->close(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Raksana</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    
    <header class="header">
        <div class="container header__container">
            <div class="header__logo-container">
                <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" class="header__logo">
                <a href="login.php"><img src="foto/logo-osis.png" alt="Logo OSIS" class="header__logo"></a>
            </div>
            
            <nav class="nav">
                <a href="index.php" class="nav__link active">Beranda</a>
                <a href="struktur.php" class="nav__link">Struktur OSIS</a>
                <a href="kalender.php" class="nav__link">Kalender Kegiatan</a>
                <a href="gallery.php" class="nav__link">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <section class="hero">
        <div class="container hero__content">
            <h1 class="hero__title">Selamat Datang di Website OSIS Raksana 👋</h1>
            <p class="hero__description">Organisasi Siswa Intra Sekolah Yayasan Pendidikan Raksana. Bertindak dan Bersatu untuk Satu Tujuan.</p>
            <a href="#main-content" class="btn btn--primary">Jelajahi Sekarang</a>
        </div>
    </section>

<main id="main-content">
    
    <section class="section">
        <div class="container">
            <h2 class="section__title">Berita Terkini 📰</h2>
            
            <div class="news-grid">
                <?php 
                // Cek apakah ada data berita
                if($berita_result && $berita_result->num_rows > 0): 
                ?>
                    <?php while($row = $berita_result->fetch_assoc()): ?>
                    <article class="news-card">
                        <?php 
                        // Tentukan path foto
                        $foto_path = !empty($row['foto']) ? "uploads/berita/" . htmlspecialchars($row['foto']) : "foto/1.jpg"; 
                        ?>
                        <img src="<?= $foto_path ?>" 
                             alt="<?= htmlspecialchars($row['judul']) ?>" 
                             class="news-card__image">
                        
                        <div class="news-card__content">
                            <span class="news-card__date">
                                <i class="fas fa-calendar-alt"></i> 
                                <?= date('d F Y', strtotime($row['tanggal_publikasi'])) ?>
                            </span>
                            <h3 class="news-card__title"><?= htmlspecialchars($row['judul']) ?></h3>
                            <p class="news-card__excerpt">
                                <?= nl2br(htmlspecialchars(substr($row['isi'], 0, 150))) ?>...
                            </p>
                            <a href="news_detail.php?id=<?= $row['id'] ?>" class="news-card__link">
                                <i class="fas fa-arrow-right"></i> Selengkapnya
                            </a>
                        </div>
                    </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 30px; border: 1px dashed #ddd;">
                        <i class="fas fa-box-open" style="font-size: 2em; color: #aaa;"></i>
                        <p>Saat ini belum ada Berita terkini yang dipublikasikan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    ---

    <section class="section bg-gray">
        <div class="container">
            <h2 class="section__title">Pengumuman Penting 📢</h2>
            
            <div class="announcement-list">
                <?php if ($pengumuman_result && $pengumuman_result->num_rows > 0): ?>
                    <?php while($row = $pengumuman_result->fetch_assoc()): ?>
                    <div class="announcement-item">
                        <span class="announcement-badge">
                            <i class="fas fa-bullhorn"></i> PENTING
                        </span>
                        <div class="announcement-item__content">
                            <h3 class="announcement-item__title"><?= htmlspecialchars($row['judul']) ?></h3>
                            <p class="announcement-item__description">
                                <?= nl2br(htmlspecialchars($row['isi'])) ?>
                            </p>
                            <span class="announcement-date">
                                <i class="fas fa-clock"></i> 
                                <?= date('d F Y', strtotime($row['tanggal_publikasi'])) ?>
                            </span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="announcement-item">
                        <span class="announcement-badge">BARU</span>
                        <div class="announcement-item__content">
                            <h3 class="announcement-item__title">Selamat Datang di Website OSIS Raksana</h3>
                            <p class="announcement-item__description">
                                Website OSIS Raksana telah resmi diluncurkan! Pantau terus halaman ini dan bagian **Berita & Pengumuman** untuk informasi terbaru kegiatan OSIS.
                            </p>
                            <span class="announcement-date"><i class="fas fa-clock"></i> Hari Ini</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="news.php" class="btn btn--primary" style="background: #17a2b8;">Lihat Semua Pengumuman</a>
            </div>
        </div>
    </section>

    ---

    <section class="section">
        <div class="container">
            <h2 class="section__title">Akses Cepat</h2>
            <div class="quick-links">
                <a href="struktur.php" class="quick-link-card">
                    <div class="quick-link-card__icon"><i class="fas fa-users"></i></div>
                    <h3 class="quick-link-card__title">Profil OSIS</h3>
                    <p class="quick-link-card__description">Kenali struktur dan program kerja OSIS kami</p>
                </a>
                
                <a href="kalender.php" class="quick-link-card">
                    <div class="quick-link-card__icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3 class="quick-link-card__title">Kalender Kegiatan</h3>
                    <p class="quick-link-card__description">Jadwal kegiatan OSIS dan sekolah</p>
                </a>
                
                <a href="gallery.php" class="quick-link-card">
                    <div class="quick-link-card__icon"><i class="fas fa-images"></i></div>
                    <h3 class="quick-link-card__title">Galeri Kegiatan</h3>
                    <p class="quick-link-card__description">Lihat dokumentasi foto-foto kegiatan kami</p>
                </a>
                
                <a href="news.php" class="quick-link-card">
                    <div class="quick-link-card__icon"><i class="fas fa-newspaper"></i></div>
                    <h3 class="quick-link-card__title">Berita & Info</h3>
                    <p class="quick-link-card__description">Daftar lengkap semua berita dan pengumuman</p>
                </a>
            </div>
        </div>
    </section>
</main>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__column">
                    <h3>Tentang OSIS</h3>
                    <p>Organisasi Siswa Intra Sekolah (OSIS) merupakan organisasi resmi sekolah yang bertujuan untuk mengembangkan potensi siswa dan menyalurkan aspirasi siwa.</p>
                </div>
                
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
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(No Telepon Sekolah)</span>
                        </div>
                    </div>
                </div>
                
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
                <p>&copy; 2025 OSIS Yayasan Pendidikan Raksana. Semua Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="js/index.js"></script>
</body>
</html>