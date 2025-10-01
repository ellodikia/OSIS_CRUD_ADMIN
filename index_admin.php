<?php
// index_admin.php
session_start();
// Jangan lupa include koneksi database kita
include 'koneksi.php';

// ----------------------------------------------------------------------
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk! ---\
// ----------------------------------------------------------------------
// Cek apakah user sudah login dan levelnya benar-benar 'admin'.
// Kalau tidak, tendang balik ke halaman login.
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ----------------------------------------------------------------------
// --- DATA FETCHING (Mengambil Data) ---\
// ----------------------------------------------------------------------

// PERBAIKAN: Hapus LIMIT 3 agar semua konten terbaru ditarik untuk admin
$sql_berita = "SELECT * FROM berita WHERE level='berita' ORDER BY tanggal_publikasi DESC";
$berita_result = $koneksi->query($sql_berita);

// PERBAIKAN: Hapus LIMIT 3
$sql_pengumuman = "SELECT * FROM berita WHERE level='pengumuman' ORDER BY tanggal_publikasi DESC";
$pengumuman_result = $koneksi->query($sql_pengumuman);

// Ambil notifikasi dari sesi (sukses/error dari crud_berita.php)
$notification = '';
if (isset($_SESSION['message'])) {
    $notification = '<div class="alert alert-success container" style="margin-top: 20px;">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
} elseif (isset($_SESSION['error'])) {
    $notification = '<div class="alert alert-error container" style="margin-top: 20px;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

$koneksi->close(); // Tutup koneksi setelah selesai ambil data
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Raksana - Admin Panel</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/index_admin.css">
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
                <a href="index_admin.php" class="nav__link active">Beranda (Admin)</a>
                <a href="struktur.php" class="nav__link">Struktur OSIS</a>
                <a href="kalender.php" class="nav__link">Kalender Kegiatan</a>
                <a href="gallery.php" class="nav__link">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>
                <a href="logout.php" class="nav__link logout-btn">Logout <i class="fas fa-sign-out-alt"></i></a>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    
    
    <main id="main-content">
    
    <?= $notification ?>

    <section class="section">
        <div class="container">
            <h2 class="section__title">
                Berita & Pengumuman Terbaru 
                <span class="admin-badge"><i class="fas fa-user-shield"></i> Admin Mode</span>
            </h2>
            
            <div class="admin-controls" style="margin-bottom: 20px;">
                <a href="#crud-form" class="btn-primary" style="text-decoration: none; margin-right: 10px;"><i class="fas fa-plus-circle"></i> Tambah Konten</a>
                
            </div>
            
            <div class="news-grid admin-mode">
                
                <?php 
                // Kita gabungkan hasil berita dan pengumuman untuk ditampilkan bersama
                $combined_results = array_merge(
                    ($berita_result->num_rows > 0) ? $berita_result->fetch_all(MYSQLI_ASSOC) : [],
                    ($pengumuman_result->num_rows > 0) ? $pengumuman_result->fetch_all(MYSQLI_ASSOC) : []
                );
                
                // Urutkan ulang berdasarkan tanggal publikasi DESC (jika perlu, walaupun query sudah mengurutkan)
                usort($combined_results, function($a, $b) {
                    return strtotime($b['tanggal_publikasi']) - strtotime($a['tanggal_publikasi']);
                });

                // Tampilkan maksimal 6 item gabungan (jika ada)
                $counter = 0;
                foreach ($combined_results as $row):
                    if ($counter >= 6) break; // Batasi tampilan agar tidak terlalu panjang
                    $counter++;
                ?>
                <article class="news-card">
                    <span class="news-card__type" style="background: <?= $row['level'] === 'pengumuman' ? '#ffc107' : '#28a745'; ?>;">
                        <?= $row['level'] === 'pengumuman' ? '📢 Pengumuman' : '📰 Berita' ?>
                    </span>
                    
                    <?php 
                    // Tampilkan foto jika ada, kalau tidak ada pakai foto default
                    // Path sudah benar: foto_berita/
                    $foto_path = !empty($row['foto']) ? "foto_berita/" . htmlspecialchars($row['foto']) : "foto/1.jpg"; 
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
                        
                        <div class="news-card__actions" style="margin-top: 10px;">
                            <a href="crud_berita.php?action=edit&id=<?= $row['id'] ?>" class="btn-primary" style="background: #17a2b8; text-decoration: none; padding: 5px 10px; font-size: 0.8em;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="crud_berita.php?action=hapus&id=<?= $row['id'] ?>" 
                               onclick="return confirm('Yakin ingin menghapus konten ini? Tindakan ini tidak bisa dibatalkan!')" 
                               class="news-card__btn news-card__btn--danger" style="margin-left: 5px;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
                
                <?php if (empty($combined_results)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 30px; border: 1px dashed #ddd;">
                        <i class="fas fa-box-open" style="font-size: 2em; color: #aaa;"></i>
                        <p>Belum ada konten Berita atau Pengumuman yang tersimpan.</p>
                    </div>
                <?php endif; ?>
                
            </div>
            
            
            
            <div class="crud-form" id="crud-form">
                <h3><i class="fas fa-plus-circle"></i> Tambah Berita / Pengumuman Baru</h3>
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
                        <label for="foto_berita"><i class="fas fa-image"></i> Upload Foto:</label>
                        <input type="file" id="foto_berita" name="foto_berita" class="form-control" accept="image/*">
                        <small class="form-text">Format: JPG, PNG, GIF. Foto ini akan disimpan di folder `foto_berita/`</small>
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
        
    <hr>
    
    <section class="section">
        <div class="container">
            <h2 class="section__title">Menu Cepat Administrasi</h2>
            <div class="quick-links">
                <a href="struktur.php" class="quick-link-card" style="background: #ffe0e0;">
                    <div class="quick-link-card__icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="quick-link-card__title">Kelola Pengurus</h3>
                    <p class="quick-link-card__description">Tambah, edit, atau hapus data struktur pengurus OSIS</p>
                </a>
                
                <a href="kalender.php" class="quick-link-card" style="background: #e0f7ff;">
                    <div class="quick-link-card__icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="quick-link-card__title">Kelola Kegiatan</h3>
                    <p class="quick-link-card__description">Tambah, edit, atau hapus jadwal kegiatan OSIS</p>
                </a>
                
                <a href="gallery.php" class="quick-link-card" style="background: #e0ffe0;">
                    <div class="quick-link-card__icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="quick-link-card__title">Kelola Galeri Foto</h3>
                    <p class="quick-link-card__description">Upload dan hapus foto kegiatan di galeri</p>
                </a>
                
                <a href="news.php" class="quick-link-card" style="background: #fff8e0;">
                    <div class="quick-link-card__icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="quick-link-card__title">Lihat Semua Konten</h3>
                    <p class="quick-link-card__description">Lihat daftar lengkap semua berita dan pengumuman</p>
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
                    <p>Organisasi Siswa Intra Sekolah (OSIS) merupakan organisasi resmi sekolah yang bertujuan untuk mengembangkan potensi siswa dan menyalurkan aspirasi siswa.</p>
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
                <p>&copy; 2025 OSIS Yayasan Pendidikan Raksana. Semua Hak Cipta Dilindungi. | **Admin Mode**</p>
            </div>
        </div>
    </footer>

    <script src="js/index.js"></script>
</body>
</html>