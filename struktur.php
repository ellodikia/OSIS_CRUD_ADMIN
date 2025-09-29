<?php
session_start();
include 'koneksi.php';

// Cek status admin
$is_admin = isset($_SESSION['level']) && $_SESSION['level'] == 'admin';

// Ambil semua data pengurus
$sql_pengurus = "SELECT * FROM pengurus ORDER BY id ASC";
$pengurus_result = $koneksi->query($sql_pengurus);

// --- Filtering Data Berdasarkan Jabatan ---
$data_pengurus = [];
$data_pembina = [];
$data_ketua_wakil = [];
$data_bph = [];
$data_departemen = [];

if ($pengurus_result) {
    while ($row = $pengurus_result->fetch_assoc()) {
        $data_pengurus[] = $row; // Menyimpan semua data untuk looping modal

        $jabatan = trim(strtolower($row['jabatan']));

        if (strpos($jabatan, 'pembina') !== false) {
            $data_pembina[] = $row;
        } elseif (strpos($jabatan, 'ketua osis') !== false || strpos($jabatan, 'wakil ketua osis') !== false) {
            // Memfilter Ketua dan Wakil Ketua
            $data_ketua_wakil[] = $row;
        } elseif (strpos($jabatan, 'sekretaris') !== false || strpos($jabatan, 'bendahara') !== false) {
            // Memfilter BPH: Sekretaris, Wakil Sekretaris, Bendahara
            $data_bph[] = $row;
        } else {
            // Semua posisi lain dianggap Departemen
            $data_departemen[] = $row;
        }
    }
}

// Mengelompokkan Departemen berdasarkan Jabatan Utama untuk tampilan
$departemen_grouped = [];
foreach ($data_departemen as $dep) {
    $title = $dep['jabatan'];
    // Coba identifikasi judul departemen utama (misalnya: 'Keimanan', 'Kreativitas', dll.)
    // Ini mengasumsikan teks 'jabatan' mengandung judul departemen.
    $group_key = $title; 
    
    // Logika pengelompokan yang lebih spesifik berdasarkan judul utama:
    if (preg_match('/(Keimanan dan Ketakwaan TYME|Kreativitas Sastra dan Budaya|Bahasa Asing|Kesehatan, Gizi dan Lingkungan|Prestasi Akademik dan Olahraga|Humas dan Infokum|Pengamanan Strategis Setiap Unit)/i', $title, $matches)) {
        $group_key = $matches[0];
    }
    
    if (!isset($departemen_grouped[$group_key])) {
        $departemen_grouped[$group_key] = [];
    }
    $departemen_grouped[$group_key][] = $dep;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur OSIS Raksana</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
    <link rel="stylesheet" href="css/struktur.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <a href="gallery.php" class="nav__link">Galeri</a>
                <a href="news.php" class="nav__link">Berita & Pengumuman</a>

            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
            <div class="nav-overlay"></div>
        </div>
    </header>

    <main class="container struktur">
        <h1 class="title">Struktur Pengurus OSIS</h1>

        <?php if ($is_admin): ?>
        <section class="section admin-crud-panel" style="margin-bottom: 30px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <div class="container">
                <h2>Tambah Pengurus Baru <i class="fas fa-user-plus"></i></h2>
                <form action="crud_pengurus.php" method="POST" enctype="multipart/form-data" class="crud-form">
                    <input type="hidden" name="action" value="tambah_pengurus">
                    
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" required>
                    
                    <label for="jabatan">Jabatan:</label>
                    <input type="text" id="jabatan" name="jabatan" placeholder="Contoh: Ketua OSIS, Sekretaris, Koordinator Dep. Keimanan" required>
                    
                    <label for="foto">Foto Profil (Max 2MB):</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>

                    <label for="visi_misi">Visi/Misi/Tugas (Detail Modal):</label>
                    <textarea id="visi_misi" name="visi_misi" rows="5" required></textarea>
                    
                    <button type="submit" class="btn btn-primary" style="background-color: #800000; color: white;"><i class="fas fa-save"></i> Simpan Pengurus</button>
                </form>
            </div>
        </section>
        <?php endif; ?>

        <section class="struktur-section">
            <h2>Pembina OSIS</h2>
            <div class="card-container">
            <?php if (count($data_pembina) > 0): ?>
                <?php foreach ($data_pembina as $row): 
                    $foto_path = empty($row['foto']) ? 'https://via.placeholder.com/140x140?text=Foto+Pembina' : 'uploads/pengurus/' . $row['foto'];
                ?>
                <div class="card clickable" data-modal="pengurus-<?= $row['id'] ?>">
                    <img src="<?= htmlspecialchars($foto_path) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Pembina'">
                    <h3><?= htmlspecialchars($row['nama']) ?></h3>
                    <p><?= htmlspecialchars($row['jabatan']) ?></p>
                    <?php if ($is_admin): ?>
                    <div class="admin-actions mt-2">
                        <a href="crud_pengurus.php?action=hapus&id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus <?= $row['nama'] ?>?')" 
                           class="btn btn-sm btn-delete" style="color: red;"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Data Pembina belum ditambahkan.</p>
            <?php endif; ?>
            </div>
        </section>

        <section class="struktur-section">
            <h2>Ketua & Wakil Ketua OSIS</h2>
            <div class="grid">
            <?php if (count($data_ketua_wakil) > 0): ?>
                <?php foreach ($data_ketua_wakil as $row): 
                    $foto_path = empty($row['foto']) ? 'https://via.placeholder.com/140x140?text=Foto+Ketua' : 'uploads/pengurus/' . $row['foto'];
                ?>
                <div class="card clickable" data-modal="pengurus-<?= $row['id'] ?>">
                    <img src="<?= htmlspecialchars($foto_path) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+BPH'">
                    <h3><?= htmlspecialchars($row['nama']) ?></h3>
                    <p><?= htmlspecialchars($row['jabatan']) ?></p>
                    <?php if ($is_admin): ?>
                    <div class="admin-actions mt-2">
                        <a href="crud_pengurus.php?action=hapus&id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus <?= $row['nama'] ?>?')" 
                           class="btn btn-sm btn-delete" style="color: red;"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Data Ketua/Wakil Ketua belum ditambahkan.</p>
            <?php endif; ?>
            </div>
        </section>

        <section class="struktur-section">
            <h2>Badan Pengurus Harian (BPH)</h2>
            <div class="grid">
            <?php if (count($data_bph) > 0): ?>
                <?php foreach ($data_bph as $row): 
                    $foto_path = empty($row['foto']) ? 'https://via.placeholder.com/140x140?text=Foto+BPH' : 'uploads/pengurus/' . $row['foto'];
                ?>
                <div class="card clickable" data-modal="pengurus-<?= $row['id'] ?>">
                    <img src="<?= htmlspecialchars($foto_path) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+BPH'">
                    <h3><?= htmlspecialchars($row['nama']) ?></h3>
                    <p><?= htmlspecialchars($row['jabatan']) ?></p>
                    <?php if ($is_admin): ?>
                    <div class="admin-actions mt-2">
                        <a href="crud_pengurus.php?action=hapus&id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus <?= $row['nama'] ?>?')" 
                           class="btn btn-sm btn-delete" style="color: red;"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Data BPH belum ditambahkan.</p>
            <?php endif; ?>
            </div>
        </section>

        <section class="struktur-section">
            <h2>Departemen</h2>
            
            <?php if (count($data_departemen) > 0): ?>
                <?php foreach ($departemen_grouped as $group_title => $members): ?>
                    <h3 style="margin-top: 20px; border-bottom: 2px solid #ccc; padding-bottom: 5px;"><?= htmlspecialchars($group_title) ?></h3>
                    <div class="grid">
                        <?php foreach ($members as $row): 
                            $foto_path = empty($row['foto']) ? 'https://via.placeholder.com/140x140?text=Foto+Dept' : 'uploads/pengurus/' . $row['foto'];
                        ?>
                        <div class="card clickable" data-modal="pengurus-<?= $row['id'] ?>">
                            <img src="<?= htmlspecialchars($foto_path) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
                            <h3><?= htmlspecialchars($row['nama']) ?></h3>
                            <p><?= htmlspecialchars($row['jabatan']) ?></p>
                            <?php if ($is_admin): ?>
                            <div class="admin-actions mt-2">
                                <a href="crud_pengurus.php?action=hapus&id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus <?= $row['nama'] ?>?')" 
                                   class="btn btn-sm btn-delete" style="color: red;"><i class="fas fa-trash-alt"></i> Hapus</a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Data Departemen belum ditambahkan.</p>
            <?php endif; ?>

        </section>
        
    </main>

    <?php foreach ($data_pengurus as $row): 
        $foto_path = empty($row['foto']) ? 'https://via.placeholder.com/180x180?text=No+Photo' : 'uploads/pengurus/' . $row['foto'];
    ?>
    <div class="modal" id="modal-pengurus-<?= $row['id'] ?>">
        <div class="modal-header">
            <span class="close-modal">&times;</span>
            <img src="<?= htmlspecialchars($foto_path) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
            <h2><?= htmlspecialchars($row['nama']) ?></h2>
            <h3><?= htmlspecialchars($row['jabatan']) ?></h3>
        </div>
        <div class="modal-content">
            <p><strong>Deskripsi Tugas/Visi Misi:</strong></p>
            <p style="white-space: pre-wrap;"><?= htmlspecialchars($row['visi_misi']) ?></p> 
        </div>
    </div>
    <?php endforeach; ?>

    <div class="modal-overlay"></div>
    
    <script src="struktur.js"></script>
</body>
</html>