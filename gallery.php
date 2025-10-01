<?php
// Pastikan sesi dimulai di awal setiap file PHP
session_start();
// Include koneksi ke database
include 'koneksi.php';

// ----------------------------------------------------------------------
// --- LOGIKA PHP & DATA FETCHING ---
// ----------------------------------------------------------------------

// CEK STATUS USER: Tentukan apakah user yang sedang mengakses adalah Admin
// Ini adalah cara yang benar dan ringkas untuk mengecek status admin.
$is_admin = isset($_SESSION['level']) && $_SESSION['level'] == 'admin';

// Debug: Tampilkan status admin
echo "<!-- DEBUG: is_admin = " . ($is_admin ? 'true' : 'false') . " -->";

// Ambil semua data foto dari tabel `galeri`
// Diurutkan berdasarkan tanggal upload terbaru, jika tanggal sama, diurutkan dari ID terbesar.
$sql_galeri = "SELECT * FROM galeri ORDER BY tanggal_upload DESC, id DESC";
$galeri_result = $koneksi->query($sql_galeri);

$photos = [];
// Cek apakah query berhasil dan ada data yang ditemukan
if ($galeri_result && $galeri_result->num_rows > 0) {
    // Ambil semua baris data dan masukkan ke array $photos
    while($row = $galeri_result->fetch_assoc()) {
        $photos[] = $row;
    }
}

// ----------------------------------------------------------------------
// --- LOGIKA NOTIFIKASI SESI ---
// ----------------------------------------------------------------------

// Siapkan variabel untuk menampung pesan notifikasi (sukses/error)
$notification = '';
if (isset($_SESSION['message'])) {
    // Jika ada pesan sukses, tampilkan dengan class 'alert-success'
    $notification = '<div class="alert alert-success container" style="margin-top: var(--space-md);">' . $_SESSION['message'] . '</div>';
    // Hapus pesan dari sesi setelah ditampilkan (agar tidak muncul lagi saat refresh)
    unset($_SESSION['message']);
} elseif (isset($_SESSION['error'])) {
    // Jika ada pesan error, tampilkan dengan class 'alert-error'
    $notification = '<div class="alert alert-error container" style="margin-top: var(--space-md);">' . $_SESSION['error'] . '</div>';
    // Hapus pesan dari sesi
    unset($_SESSION['error']);
}

// ----------------------------------------------------------------------
// --- FUNGSI HELPER ---
// ----------------------------------------------------------------------

// Fungsi placeholder untuk URL Lightbox dihilangkan karena sekarang menggunakan JS Modal
function get_lightbox_url($photo) {
    // Menggunakan path foto asli sebagai fallback URL besar
    return htmlspecialchars($photo['path_foto']); 
}

// Tutup koneksi database (opsional di sini, tapi bagus untuk housekeeping)
$koneksi->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    <title>Galeri Kegiatan OSIS Raksana</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    
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
                <?php if ($is_admin): ?>
                    <a href="logout.php" class="nav__link" style="color: #dc3545;">Logout <i class="fas fa-sign-out-alt"></i></a>
                <?php endif; ?>
            </nav>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <main class="container section visible">
        <div class="section__title">
            <h1>Galeri Kegiatan OSIS </h1>
        </div>
        
        <?= $notification ?>

        <?php if ($is_admin): ?>
<div class="container admin-form-container"> <br>
    <h2 class="section__title" style="margin-bottom: var(--space-lg);">Upload Foto Baru ke Galeri</h2>
    
    <form action="crud_galeri.php" method="POST" enctype="multipart/form-data" class="crud-form" id="upload-form">
        <input type="hidden" name="action" value="tambah_foto">
        
        <div class="form-group">
            <label for="judul">Judul Foto:</label>
            <input type="text" id="judul" name="judul" required placeholder="Contoh: Kegiatan Bersih-Bersih Sekolah">
        </div>
        
        <div class="form-group">
            <label for="keterangan">Keterangan (Opsional):</label>
            <textarea id="keterangan" name="keterangan" placeholder="Deskripsi singkat tentang foto atau kegiatan..."></textarea>
        </div>

        <div class="form-group">
            <label for="foto_file">Pilih File Foto (Maks. 2MB, JPG/PNG):</label>
            <input type="file" id="foto_file" name="foto_file" accept=".jpg, .jpeg, .png" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-submit">
            <i class="fas fa-cloud-upload-alt"></i> Upload & Simpan
        </button> <br>
    </form>
</div>
<?php endif; ?>
        
        <div class="gallery-grid">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="gallery-item" 
                        data-lightbox-url="<?= get_lightbox_url($photo) ?>" 
                        data-title="<?= htmlspecialchars($photo['judul']) ?>" 
                        data-caption="<?= htmlspecialchars($photo['keterangan']) ?>">
                        
                        <img src="<?= htmlspecialchars($photo['path_foto']) ?>" 
                            alt="<?= htmlspecialchars($photo['keterangan']) ?>" 
                            class="gallery-item__image">
                        
                        <div class="gallery-item__overlay">
                            <h4 class="gallery-item__title"><?= htmlspecialchars($photo['judul']) ?></h4>
                            <p class="gallery-item__date">
                                <i class="far fa-calendar-alt"></i> 
                                <?= date('d F Y', strtotime($photo['tanggal_upload'])) ?>
                            </p>
                            
                            <?php if ($is_admin): ?>
                            <div class="admin-actions" style="margin-top: 5px;">
                                <!-- DEBUG: Tampilkan ID foto -->
                                <!-- DEBUG: Photo ID: <?= $photo['id'] ?> -->
                                <a href="crud_galeri.php?action=hapus&id=<?= $photo['id'] ?>" 
                                   onclick="return confirmDelete(event, '<?= htmlspecialchars($photo['judul']) ?>')" 
                                   class="btn-primary btn-delete admin-control-btn danger" 
                                   style="padding: 5px 10px; font-size: 0.75rem;">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="far fa-images"></i>
                    <h3>Belum ada foto di galeri. 😔</h3>
                    <p>Silakan upload foto kegiatan untuk ditampilkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <br><br>
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
                            <span>(061)4524356</span>
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

    <div id="galleryModal" class="modal" aria-hidden="true" role="dialog">
        <div class="modal-content">
            <span class="modal-close" aria-label="Tutup Galeri">&times;</span>
            <img id="modalImage" class="modal-image" src="" alt="Foto Galeri">
            <div class="modal-info">
                <h2 id="modalTitle" class="modal-title"></h2>
                <p id="modalCaption" class="modal-caption"></p>
            </div>
        </div>
    </div>
    <script>
        // Fungsi konfirmasi hapus yang lebih baik
        function confirmDelete(event, judul) {
            if (!confirm(`Yakin mau hapus foto "${judul}" dari galeri?`)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // ======================================
            // 1. MOBILE MENU FIX (Memastikan Toggle Berfungsi)
            // ======================================
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const nav = document.querySelector('.nav');
            const navOverlay = document.querySelector('.nav-overlay');
            const body = document.body;

            if (mobileMenuBtn && nav) {
                function toggleMobileMenu() {
                    nav.classList.toggle('active');
                    if (navOverlay) navOverlay.classList.toggle('active');
                    
                    // Mengubah ikon dari ☰ ke ✕ saat menu aktif
                    mobileMenuBtn.innerHTML = nav.classList.contains('active') ? '✕' : '☰';
                    
                    // Mencegah body scroll saat menu terbuka
                    body.style.overflow = nav.classList.contains('active') ? 'hidden' : ''; 
                }
                
                // Menambahkan Event Listener untuk Tombol Toggle
                mobileMenuBtn.addEventListener('click', toggleMobileMenu);
                
                // Menutup menu saat klik overlay
                if (navOverlay) {
                    navOverlay.addEventListener('click', toggleMobileMenu);
                }
                
                // Menutup menu saat link di klik (Good UX)
                nav.querySelectorAll('.nav__link').forEach(link => {
                    link.addEventListener('click', function() {
                        if (nav.classList.contains('active')) {
                            toggleMobileMenu(); 
                        }
                    });
                });
            }

            // ======================================
            // 2. LOGIKA LIGHTBOX / MODAL GALERI (Implementasi Lengkap)
            // ======================================
            const galleryModal = document.getElementById('galleryModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalCaption = document.getElementById('modalCaption');
            const modalClose = document.querySelector('.modal-close');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            function openModal(src, title, caption) {
                modalImage.src = src;
                modalImage.alt = title;
                modalTitle.textContent = title;
                modalCaption.textContent = caption;
                
                galleryModal.classList.add('active');
                body.style.overflow = 'hidden'; // Mencegah scrolling di background
                galleryModal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                galleryModal.classList.remove('active');
                body.style.overflow = '';
                galleryModal.setAttribute('aria-hidden', 'true');
            }

            galleryItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Jangan buka modal jika yang diklik adalah tombol hapus
                    if (e.target.closest('.admin-actions')) {
                        return;
                    }
                    
                    e.preventDefault();
                    
                    const imageElement = this.querySelector('.gallery-item__image');
                    const src = imageElement.src;
                    const title = this.dataset.title;
                    const caption = this.dataset.caption;
                    
                    openModal(src, title, caption);
                });
            });
            
            // Menutup modal saat tombol close diklik
            if (modalClose) {
                modalClose.addEventListener('click', closeModal);
            }

            // Menutup modal saat klik di luar area modal content (overlay)
            galleryModal.addEventListener('click', function(e) {
                if (e.target === galleryModal) {
                    closeModal();
                }
            });

            // Menutup modal saat tombol ESC ditekan
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && galleryModal.classList.contains('active')) {
                    closeModal();
                }
            });
        });
    </script>
    
</body>
</html>