<?php
session_start();
include 'koneksi.php';

// PASTIKAN PENGEcekan SAMA DENGAN FILE LAIN
$is_admin = isset($_SESSION['level']) && $_SESSION['level'] == 'admin';

// Ambil semua kegiatan yang belum lewat hari ini
$sql_kegiatan = "SELECT * FROM kegiatan WHERE tanggal >= CURDATE() ORDER BY tanggal ASC";

try {
    $kegiatan_result = $koneksi->query($sql_kegiatan);
    
    $events_data = [];
    if ($kegiatan_result) {
        while($row = $kegiatan_result->fetch_assoc()) {
            $events_data[] = [
                'id' => $row['id'],
                'date' => date('j M', strtotime($row['tanggal'])), 
                'title' => htmlspecialchars($row['judul']),
                'time' => htmlspecialchars($row['waktu']),
                'location' => htmlspecialchars($row['lokasi']),
                'person' => htmlspecialchars($row['penanggung_jawab']),
                'description' => htmlspecialchars($row['deskripsi']),
                'preparations' => [htmlspecialchars($row['deskripsi'])] 
            ];
        }
    }
} catch (mysqli_sql_exception $e) {
    error_log("Database Error: " . $e->getMessage()); 
    $kegiatan_result = null;
    $events_data = [];
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
    <link rel="stylesheet" href="css/kalender.css">
    <style>
        .admin-badge {
            background: #800000;
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.7em;
            margin-left: 10px;
        }
        .admin-crud-panel {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #800000;
        }
        .crud-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .crud-form input, .crud-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-delete {
            color: #dc3545;
            text-decoration: none;
        }
        .btn-primary {
            background: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
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
                <a href="kalender.php" class="nav__link active">Kalender Kegiatan
                <a href="gallery.php" class="nav__link">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>


            </nav>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>
<?php if ($is_admin): ?>

</div>
<?php endif; ?>
    <main class="container">
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
                        <tbody id="calendar-table"></tbody>
                    </table>
                </div>
            </div>

            <div class="events-container">
                <h3>Daftar Kegiatan Mendatang <?php if($is_admin) echo '<span class="admin-badge">Admin</span>'; ?></h3>
                <ul class="events-list">
                <?php 
                if ($kegiatan_result && $kegiatan_result->num_rows > 0): 
                    $kegiatan_result->data_seek(0);
                    while($row = $kegiatan_result->fetch_assoc()): 
                ?>
                    <li class="event-item db-item">
                        <div class="date-tag"><?= date('d/m', strtotime($row['tanggal'])) ?></div>
                        <div class="event-info">
                            <h4><?= htmlspecialchars($row['judul']) ?></h4>
                            <p><?= htmlspecialchars($row['lokasi']) ?> - PJ: <?= htmlspecialchars($row['penanggung_jawab']) ?></p>
                        </div>
                        <?php if ($is_admin): ?>
                        <div class="admin-actions">
                            <a href="crud_kegiatan.php?action=hapus&id=<?= $row['id'] ?>" 
                               onclick="return confirm('Hapus kegiatan ini?')" 
                               class="btn-delete"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        <?php endif; ?>
                    </li>
                <?php endwhile; 
                else: ?>
                    <li>Tidak ada kegiatan mendatang di database.</li>
                <?php endif; ?>
                </ul>
                <h3>Kegiatan Kalender (<span id="current-month-events"></span>)</h3>
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
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Kegiatan dari database dimuat:", <?= $events_json ?>);
        });
    </script>
    <script>// Enhanced admin functionality
document.addEventListener('DOMContentLoaded', function() {
    // Highlight admin elements
    if (<?= $is_admin ? 'true' : 'false' ?>) {
        // Add admin class to body for global styling
        document.body.classList.add('admin-mode');
        
        // Add confirmation for delete actions
        const deleteLinks = document.querySelectorAll('.btn-delete');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus kegiatan ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                }
            });
        });
        
        // Form validation enhancement
        const adminForm = document.querySelector('.crud-form');
        if (adminForm) {
            adminForm.addEventListener('submit', function(e) {
                const judul = document.getElementById('judul_k').value.trim();
                const tanggal = document.getElementById('tanggal').value;
                
                if (!judul) {
                    e.preventDefault();
                    alert('Judul kegiatan harus diisi!');
                    document.getElementById('judul_k').focus();
                    return;
                }
                
                if (!tanggal) {
                    e.preventDefault();
                    alert('Tanggal kegiatan harus diisi!');
                    document.getElementById('tanggal').focus();
                    return;
                }
                
                // Show loading state
                const submitBtn = adminForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;
                
                // Revert after 3 seconds if still processing
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        }
    }
});</script>
</body>
</html>