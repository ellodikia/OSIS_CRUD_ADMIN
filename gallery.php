<?php
session_start();
include 'koneksi.php';

// CEK SESSION DENGAN CARA YANG SAMA
$is_admin = isset($_SESSION['level']) && $_SESSION['level'] == 'admin';

// Ambil data foto dari database
$sql_galeri = "SELECT * FROM galeri ORDER BY tanggal_upload DESC, id DESC";
$galeri_result = $koneksi->query($sql_galeri);

$photos = [];
if ($galeri_result && $galeri_result->num_rows > 0) {
    while($row = $galeri_result->fetch_assoc()) {
        $photos[] = $row;
    }
}

// Tambahkan blok notifikasi sesi
$notification = '';
if (isset($_SESSION['message'])) {
    // Menggunakan class alert dari gallery.css
    $notification = '<div class="alert alert-success container" style="margin-top: var(--space-md);">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
} elseif (isset($_SESSION['error'])) {
    // Menggunakan class alert dari gallery.css
    $notification = '<div class="alert alert-error container" style="margin-top: var(--space-md);">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Format URL untuk lightbox (jika diimplementasikan)
function get_lightbox_url($photo) {
    return '#'; // Placeholder, karena lightbox JS belum ada. Di CSS class .gallery-item berfungsi sebagai trigger
}

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
            </nav>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <main class="container section visible">
        <div class="section__title">
            <h1>Galeri Kegiatan OSIS <?php if($is_admin) echo '<span class="admin-badge">Admin Mode</span>'; ?></h1>
        </div>
        
        <?= $notification ?>

        <?php if ($is_admin): ?>
        <section class="crud-form" style="margin-bottom: var(--space-xl);">
            <h3><i class="fas fa-upload"></i> Upload Foto Baru</h3>
            <form action="crud_galeri.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_foto">
                
                <div class="form-group">
                    <label for="judul">Judul Foto:</label>
                    <input type="text" id="judul" name="judul" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan Singkat:</label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="foto_file">Pilih File Foto (JPG, PNG, maksimal 2MB):</label>
                    <input type="file" id="foto_file" name="foto_file" accept=".jpg, .jpeg, .png" class="form-control" required>
                </div>
                
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Upload Foto</button>
            </form>
        </section>
        <?php endif; ?>
        
        <div class="gallery-grid">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="gallery-item" data-lightbox-url="<?= get_lightbox_url($photo) ?>" 
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
                                <a href="crud_galeri.php?action=hapus&id=<?= $photo['id'] ?>&file=<?= urlencode($photo['path_foto']) ?>" 
                                   onclick="return confirm('Hapus foto ini dari galeri?')" 
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
                    <h3>Belum ada foto di galeri.</h3>
                    <p>Silakan upload foto kegiatan untuk ditampilkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    </body>
</html>