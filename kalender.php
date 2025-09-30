<?php
// kalender.php
session_start();
// Include koneksi database kita
include 'koneksi.php'; 

// ----------------------------------------------------------------------
// --- Cek Status Admin ---
// ----------------------------------------------------------------------
// Variabel untuk menentukan apakah user adalah admin
$is_admin = isset($_SESSION['level']) && $_SESSION['level'] == 'admin';

// ----------------------------------------------------------------------
// --- Notifikasi CRUD ---
// ----------------------------------------------------------------------
$notification = '';
if (isset($_SESSION['message'])) {
    $notification = '<div class="alert alert-success container" style="margin-top: 20px;">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
} elseif (isset($_SESSION['error'])) {
    $notification = '<div class="alert alert-error container" style="margin-top: 20px;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// ----------------------------------------------------------------------
// --- DATA FETCHING (Mengambil Data Kegiatan) ---
// ----------------------------------------------------------------------
// Ambil semua kegiatan yang belum lewat hari ini
$sql_kegiatan = "SELECT * FROM kegiatan WHERE tanggal >= CURDATE() ORDER BY tanggal ASC";

$events_data = [];
try {
    $kegiatan_result = $koneksi->query($sql_kegiatan);
    
    if ($kegiatan_result) {
        // Ambil data untuk PHP list dan siapkan untuk JSON JavaScript
        while($row = $kegiatan_result->fetch_assoc()) {
            $events_data[] = [
                'id' => $row['id'],
                // Format tanggal untuk Kalender JS
                'date' => date('Y-m-d', strtotime($row['tanggal'])), 
                'title' => htmlspecialchars($row['judul']),
                'time' => htmlspecialchars($row['waktu']),
                'location' => htmlspecialchars($row['lokasi']),
                'person' => htmlspecialchars($row['penanggung_jawab']),
                'description' => htmlspecialchars($row['deskripsi']),
                'preparations' => [htmlspecialchars($row['deskripsi'])] // Menggunakan deskripsi sebagai item persiapan
            ];
        }
    }
} catch (mysqli_sql_exception $e) {
    error_log("Database Error: " . $e->getMessage()); 
    $kegiatan_result = null;
    $events_data = [];
}

// Reset pointer result untuk ditampilkan di daftar kegiatan PHP
if ($kegiatan_result) {
    $kegiatan_result->data_seek(0);
}

// Konversi array PHP ke JSON untuk JavaScript
$events_json = json_encode($events_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    <title>Kalender Kegiatan OSIS Raksana</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/index.css"> 
    <link rel="stylesheet" href="css/kalender.css">
    
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="header__logo-container">
                <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" class="header__logo">
                <a href="<?= $is_admin ? 'index_admin.php' : 'login.php' ?>">
                    <img src="foto/logo-osis.png" alt="Logo OSIS" class="header__logo">
                </a>
            </div>
            
            <nav class="nav">
                <a href="<?= $is_admin ? 'index_admin.php' : 'index.php' ?>" class="nav__link">Beranda</a>
                <a href="struktur.php" class="nav__link">Struktur OSIS</a>
                <a href="kalender.php" class="nav__link active">Kalender Kegiatan</a>
                <a href="gallery.php" class="nav__link">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>
                <?php if ($is_admin): ?>
                    <a href="logout.php" class="nav__link" style="color: #dc3545;">Logout <i class="fas fa-sign-out-alt"></i></a>
                <?php endif; ?>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <?= $notification ?>

    <main class="container">
        <h1 class="page-title">Kalender Kegiatan OSIS Raksana 📅
            <?php if($is_admin) echo '<span class="admin-badge"><i class="fas fa-user-shield"></i> Admin Mode</span>'; ?>
        </h1>
        
        <div class="clock">
            <p id="current-datetime"></p>
        </div>

        <div class="calendar-layout">
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                    <h2 id="current-month-year"></h2>
                    <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                            </tr>
                        </thead>
                        <tbody id="calendar-table">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="events-container">
                
                <h3>Daftar Kegiatan Mendatang 📝</h3>
                
                <ul class="events-list">
                <?php 
                if ($kegiatan_result && $kegiatan_result->num_rows > 0): 
                    while($row = $kegiatan_result->fetch_assoc()): 
                ?>
                    <li class="event-item db-item">
                        <div class="date-tag"><?= date('d/m', strtotime($row['tanggal'])) ?></div>
                        <div class="event-info">
                            <h4><?= htmlspecialchars($row['judul']) ?></h4>
                            <p>
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['lokasi']) ?> 
                                | PJ: <?= htmlspecialchars($row['penanggung_jawab']) ?>
                            </p>
                        </div>
                        <?php if ($is_admin): ?>
                        <div class="admin-actions">
                            <a href="crud_kegiatan.php?action=hapus&id=<?= $row['id'] ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')" 
                               class="btn-delete"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        <?php endif; ?>
                    </li>
                <?php endwhile; 
                else: ?>
                    <li style="text-align: center; padding: 15px; color: #777;">
                        <i class="fas fa-check-circle"></i> Tidak ada kegiatan OSIS mendatang yang terjadwal.
                    </li>
                <?php endif; ?>
                </ul>
                
                <h3 style="margin-top: 30px;">Detail Kegiatan Bulan (<span id="current-month-events"></span>)</h3>
                <ul id="events-list" class="events-list">
                    </ul>
            </div>
        </div>
        
        <?php if ($is_admin): ?>
        <section class="admin-crud-panel">
            <h2><i class="fas fa-calendar-plus"></i> Tambah Kegiatan Mendatang</h2>
            <form action="crud_kegiatan.php" method="POST" class="crud-form">
                <input type="hidden" name="action" value="tambah_kegiatan">
                
                <label for="judul_k">Judul Kegiatan:</label>
                <input type="text" id="judul_k" name="judul" required>
                
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label for="tanggal">Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="waktu">Waktu (Opsional):</label>
                        <input type="time" id="waktu" name="waktu">
                    </div>
                </div>
                
                <label for="lokasi">Lokasi:</label>
                <input type="text" id="lokasi" name="lokasi" required>

                <label for="penanggung_jawab">Penanggung Jawab:</label>
                <input type="text" id="penanggung_jawab" name="penanggung_jawab">
                
                <label for="deskripsi">Deskripsi Kegiatan:</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"></textarea>
                
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan Kegiatan</button>
            </form>
        </section>
        <?php endif; ?>
    </main>
    
    <script src="js/kalender.js"></script>
    <script>
        // Data kegiatan dari PHP di-inject ke JavaScript
        const eventsData = <?= $events_json ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi kalender dengan data kegiatan dari database
            if (typeof initCalendar === 'function') {
                initCalendar(eventsData); 
            } else {
                console.error('Fungsi initCalendar belum dimuat.');
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah user adalah admin
            if (<?= $is_admin ? 'true' : 'false' ?>) {
                // Tambahkan konfirmasi untuk aksi hapus
                const deleteLinks = document.querySelectorAll('.btn-delete');
                deleteLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (!confirm('Apakah Anda yakin ingin menghapus kegiatan ini? Tindakan ini tidak dapat dibatalkan.')) {
                            e.preventDefault();
                        }
                    });
                });
                
                // Form validation sederhana
                const adminForm = document.querySelector('.crud-form');
                if (adminForm) {
                    adminForm.addEventListener('submit', function(e) {
                        const judul = document.getElementById('judul_k').value.trim();
                        const tanggal = document.getElementById('tanggal').value;
                        
                        if (!judul || !tanggal) {
                            e.preventDefault();
                            alert('Judul dan Tanggal kegiatan harus diisi!');
                            return;
                        }
                        
                        // Menampilkan loading state saat submit
                        const submitBtn = adminForm.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                        submitBtn.disabled = true;
                        
                        // Revert loading state (jika terjadi error client-side atau sebagai fallback)
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 5000); 
                    });
                }
            }
        });
    </script>
</body>
</html>