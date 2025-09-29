<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Raksana</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container header__container">
            <div class="header__logo-container">
                <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" class="header__logo">
                <a href="login.php"><img src="foto/logo-osis.png" alt="Logo OSIS" class="header__logo"></a>
            </div>
            
            <nav class="nav">
                <a href="index.php" class="nav__link">Beranda</a>
                <a href="struktur.php" class="nav__link">Struktur OSIS</a>
                <a href="kalender.php" class="nav__link">Kalender Kegiatan</a>
                <a href="gallery.php" class="nav__link active">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero__content">
            <h1 class="hero__title">Selamat Datang di Website OSIS</h1>
            <p class="hero__description">Organisasi Siswa Intra Sekolah Yayasan Pendidikan Raksana Bertindak dan Bersatu untuk Satu</p>
            <a href="#main-content" class="btn btn--primary">Jelajahi Sekarang</a>
        </div>
    </section>

    <!-- Main Content -->
    <main id="main-content">
        <!-- News Section -->
        <section class="section">
            <div class="container">
                <h2 class="section__title">Berita Terkini</h2>
                
                <div class="news-grid">
                    <!-- News Item 1 -->
                    <article class="news-card">
                        <img src="foto/1.jpg" alt="Kegiatan OSIS" class="news-card__image">
                        <div class="news-card__content">
                            <span class="news-card__date">08 September 2025</span>
                            <h3 class="news-card__title">Perayaan Hari Olahraga Nasional. (Day 1)</h3>
                            <p class="news-card__excerpt">Acara dilaksanakan dengan kondusif</p>
                        <a href="gallery.html?filter=olahraga" class="news-card__link"><i class="fas fa-arrow-right"></i> Selengkapnya</a>
                        </div>
                    </article>
                    
                    <!-- News Item 2 -->
                    <article class="news-card">
                        <img src="news2.jpg" alt="Kegiatan OSIS" class="news-card__image">
                        <div class="news-card__content">
                            <span class="news-card__date">-</span>
                            <h3 class="news-card__title"></h3>
                            <p class="news-card__excerpt"></p>
                            <a href="#" class="news-card__link"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    
                    <!-- News Item 3 -->
                    <article class="news-card">
                        <img src="news3.jpg" alt="Kegiatan OSIS" class="news-card__image">
                        <div class="news-card__content">
                            <span class="news-card__date">-</span>
                            <h3 class="news-card__title"></h3>
                            <p class="news-card__excerpt"></p>
                            <a href="#" class="news-card__link"> <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        
        <!-- Announcements Section -->
        <section class="section bg-gray">
            <div class="container">
                <h2 class="section__title">Pengumuman Penting</h2>
                
                <div class="announcement-list">
                    <!-- Announcement 1 -->
                    <div class="announcement-item">
                        <span class="announcement-badge">BARU</span>
                        <div class="announcement-item__content">
                            <h3 class="announcement-item__title"></h3>
                            <p class="announcement-item__description"></p>
                        </div>
                    </div>
                    
                    <!-- Announcement 2 -->
                    <div class="announcement-item">
                        <span class="announcement-badge">BARU</span>
                        <div class="announcement-item__content">
                            <h3 class="announcement-item__title"></h3>
                            <p class="announcement-item__description"></p>
                        </div>
                    </div>
                    
                    <!-- Announcement 3 -->
                    <div class="announcement-item">
                        <div class="announcement-item__content">
                            <h3 class="announcement-item__title"></h3>
                            <p class="announcement-item__description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Quick Links Section -->
        <section class="section">
            <div class="container">
                <div class="quick-links">
                    <!-- Quick Link 1 -->
                    <a href="struktur.html" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="quick-link-card__title">Profil OSIS</h3>
                        <p class="quick-link-card__description">Kenali struktur dan program kerja OSIS kami</p>
                    </a>
                    
                    <!-- Quick Link 2 -->
                    <a href="kalender.html" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="quick-link-card__title">Kalender Kegiatan</h3>
                        <p class="quick-link-card__description">Jadwal kegiatan OSIS dan sekolah</p>
                    </a>
                    
                    <!-- Quick Link 3 -->
                    <a href="forms.html" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="quick-link-card__title">Formulir</h3>
                        <p class="quick-link-card__description">Pendaftaran dan pengajuan proposal</p>
                    </a>
                    
                    <!-- Quick Link 4 -->
                    <a href="contact.html" class="quick-link-card">
                        <div class="quick-link-card__icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="quick-link-card__title">Hubungi Kami</h3>
                        <p class="quick-link-card__description">Saran dan kritik untuk OSIS</p>
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
                    <p>Organisasi Siswa Intra Sekolah (OSIS) merupakan organisasi resmi sekolah yang bertujuan untuk mengembangkan potensi siswa dan menyalurkan aspirasi siwa.</p>
                </div>
                
                <!-- Contact Column -->
                <div class="footer__column">
                    <h3>Kontak Kami</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jl. Gajah Mada N0. 20 Medan, Sumatera Utara, Indonesia</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>osisraksana@.sch.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span></span>
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
                <p>&copy; 2025 OSIS Yayasan Pendidikan Raksana. Semua Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
   <script src="js/index.js"></script>
</body>
</html>