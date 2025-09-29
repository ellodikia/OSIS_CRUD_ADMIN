<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    <title>Gallery OSIS Raksana</title>
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>// Handle URL parameters for filtering
function handleUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');
    
    if (filter) {
        // Find and click the corresponding filter button
        const filterButton = document.querySelector(`.filter-btn[data-filter="${filter}"]`);
        if (filterButton) {
            filterButton.click();
        }
    }
}

// Panggil fungsi ini di akhir DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // ... kode lainnya ...
    
    // Handle URL parameters
    handleUrlParams();
});</script>
  <link rel="stylesheet" href="gallery.css">
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
   

    <!-- Main Content -->
    <main id="main-content">
        <!-- Gallery Section -->
        <section class="section">
            <div class="container">
                <h2 class="section__title">Dokumentasi Kegiatan</h2>
                
                <!-- Gallery Filters -->
                <div class="gallery-filters">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="olahraga">Hari Olahraga</button>
                    <button class="filter-btn" data-filter="seni">Pentas Seni</button>
                    <button class="filter-btn" data-filter="seminar">Seminar</button>
                    <button class="filter-btn" data-filter="lainnya">Lainnya</button>
                </div>
                
                <!-- Gallery Grid -->
                <div class="gallery-grid">
                    <!-- Item 1 - Hari Olahraga -->
                    <div class="gallery-item" data-category="olahraga">
                        <img src="foto/1.jpg" alt="Perayaan Hari Olahraga Nasional" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title">Perayaan Hari Olahraga</h3>
                            <p class="gallery-item__date">08 September 2025</p>
                        </div>
                    </div>
                    
                    <!-- Item 2 - Hari Olahraga -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/" alt="" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 3 - Hari Olahraga -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/olahraga3.jpg" alt="" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 4 - Pentas Seni -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/seni1.jpg" alt="" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 5 - Pentas Seni -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/seni2.jpg" alt="Band Siswa" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 6 - Seminar -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/seminar1.jpg" alt="" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 7 - Lainnya -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/lain1.jpg" alt="Kegiatan Bakti Sosial" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                    
                    <!-- Item 8 - Lainnya -->
                    <div class="gallery-item" data-category="">
                        <img src="foto/lain2.jpg" alt="" class="gallery-item__image">
                        <div class="gallery-item__overlay">
                            <h3 class="gallery-item__title"></h3>
                            <p class="gallery-item__date"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Lightbox Modal -->
    <div class="lightbox">
        <button class="lightbox__close">&times;</button>
        <div class="lightbox__nav">
            <button class="lightbox__btn lightbox__prev"><i class="fas fa-chevron-left"></i></button>
            <button class="lightbox__btn lightbox__next"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="lightbox__content">
            <img src="" alt="" class="lightbox__image">
            <p class="lightbox__caption"></p>
        </div>
    </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Mobile Menu Toggle dengan Overlay ---
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const nav = document.querySelector('.nav');
            const overlay = document.querySelector('.nav-overlay');
            const body = document.body;
            
            if (mobileMenuBtn && nav) {
                mobileMenuBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleMobileMenu();
                });
                
                if (overlay) {
                    overlay.addEventListener('click', () => {
                        closeMobileMenu();
                    });
                }
                
                // Tutup menu ketika klik link di navigasi
                nav.addEventListener('click', (e) => {
                    if (e.target.tagName === 'A') {
                        closeMobileMenu();
                    }
                });
                
                // Fungsi untuk toggle menu mobile
                function toggleMobileMenu() {
                    nav.classList.toggle('active');
                    if (overlay) {
                        overlay.classList.toggle('active');
                    }
                    mobileMenuBtn.innerHTML = nav.classList.contains('active') ? '✕' : '☰';
                    
                    // Prevent body scroll ketika menu terbuka
                    if (nav.classList.contains('active')) {
                        body.style.overflow = 'hidden';
                    } else {
                        body.style.overflow = '';
                    }
                }
                
                // Fungsi untuk menutup menu mobile
                function closeMobileMenu() {
                    if (nav.classList.contains('active')) {
                        nav.classList.remove('active');
                        if (overlay) {
                            overlay.classList.remove('active');
                        }
                        mobileMenuBtn.innerHTML = '☰';
                        body.style.overflow = '';
                    }
                }
            }
            
            // --- Animasi Scroll untuk Sections ---
            const sections = document.querySelectorAll('.section');
            
            // Fungsi untuk memeriksa apakah elemen terlihat di viewport
            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.9 &&
                    rect.bottom >= 0
                );
            }
            
            // Fungsi untuk menangani animasi scroll
            function handleScrollAnimation() {
                sections.forEach(section => {
                    if (isElementInViewport(section)) {
                        section.classList.add('visible');
                    }
                });
            }
            
            // Jalankan saat scroll dan saat load pertama
            window.addEventListener('scroll', handleScrollAnimation);
            window.addEventListener('load', handleScrollAnimation);
            handleScrollAnimation(); // Jalankan sekali saat pertama dimuat
            
            // --- Gallery Filter Functionality ---
            const filterButtons = document.querySelectorAll('.filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    const filterValue = button.getAttribute('data-filter');
                    
                    // Filter gallery items
                    galleryItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        
                        if (filterValue === 'all' || filterValue === itemCategory) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
            
            // --- Lightbox Functionality ---
            const lightbox = document.querySelector('.lightbox');
            const lightboxImage = document.querySelector('.lightbox__image');
            const lightboxCaption = document.querySelector('.lightbox__caption');
            const lightboxClose = document.querySelector('.lightbox__close');
            const lightboxPrev = document.querySelector('.lightbox__prev');
            const lightboxNext = document.querySelector('.lightbox__next');
            
            let currentImageIndex = 0;
            let images = [];
            
            // Initialize lightbox with all visible images
            function initLightbox() {
                images = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])'));
                
                galleryItems.forEach((item, index) => {
                    item.addEventListener('click', () => {
                        // Update current image index based on filtered items
                        const visibleItems = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])'));
                        currentImageIndex = visibleItems.indexOf(item);
                        
                        openLightbox(currentImageIndex);
                    });
                });
            }
            
            // Open lightbox with specific image
            function openLightbox(index) {
                const visibleItems = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])'));
                
                if (visibleItems.length === 0) return;
                
                const imageSrc = visibleItems[index].querySelector('img').src;
                const imageTitle = visibleItems[index].querySelector('.gallery-item__title').textContent;
                const imageDate = visibleItems[index].querySelector('.gallery-item__date').textContent;
                
                lightboxImage.src = imageSrc;
                lightboxCaption.textContent = `${imageTitle} - ${imageDate}`;
                
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            // Close lightbox
            function closeLightbox() {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Navigate to next image
            function nextImage() {
                const visibleItems = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])'));
                currentImageIndex = (currentImageIndex + 1) % visibleItems.length;
                openLightbox(currentImageIndex);
            }
            
            // Navigate to previous image
            function prevImage() {
                const visibleItems = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])'));
                currentImageIndex = (currentImageIndex - 1 + visibleItems.length) % visibleItems.length;
                openLightbox(currentImageIndex);
            }
            
            // Event listeners for lightbox controls
            lightboxClose.addEventListener('click', closeLightbox);
            lightboxNext.addEventListener('click', nextImage);
            lightboxPrev.addEventListener('click', prevImage);
            
            // Close lightbox when clicking on overlay
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (lightbox.classList.contains('active')) {
                    if (e.key === 'Escape') closeLightbox();
                    if (e.key === 'ArrowRight') nextImage();
                    if (e.key === 'ArrowLeft') prevImage();
                }
            });
            
            // Initialize lightbox
            initLightbox();
            
            // Reinitialize lightbox when filters change
            filterButtons.forEach(button => {
                button.addEventListener('click', initLightbox);
            });
        });
    </script>
</body>
</html>