<?php
// crud_berita.php - Menangani Tambah, Edit, dan Hapus Berita/Pengumuman

session_start();
// --- PROTEKSI AKSES: Hanya ADMIN yang boleh masuk! ---
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    // Alihkan ke halaman login atau beranda jika bukan admin
    header("Location: login.php");
    exit;
}

// Pastikan file 'koneksi.php' sudah di-include untuk koneksi ke database
include 'koneksi.php';

// Lokasi folder tempat kita akan menyimpan semua foto berita
$folder_upload = "foto_berita/"; 

// Cek, kalau foldernya belum ada, kita buatkan!
if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0777, true);
}


// ======================================================================
// --- 1. BAGIAN POST REQUEST (Menambah ATAU Mengedit Data Berita) ---
// ======================================================================
// Cek jika ada data yang dikirim melalui method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID untuk membedakan mode EDIT atau TAMBAH. ID=0 berarti Tambah Baru.
    $id_berita = isset($_POST['id_berita']) ? (int)$_POST['id_berita'] : 0;
    
    // Ambil data dari form
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $level = $_POST['level'];
    $tanggal = date("Y-m-d H:i:s"); // Gunakan format lengkap untuk DATETIME
    
    // Default foto adalah foto lama (jika ada)
    $nama_file_foto = isset($_POST['foto_lama']) ? $_POST['foto_lama'] : ''; 
    $upload_baru = false;

    // Persiapkan status redirect
    $status_redirect = "";

    try {
        // 1. PROSES UPLOAD GAMBAR/FOTO BARU
        if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
            $upload_baru = true;
            $file_tmp = $_FILES['foto_berita']['tmp_name']; 
            $file_name = basename($_FILES['foto_berita']['name']); 
            
            // Generate nama file baru yang aman dan unik
            $nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file_name);
            $target_file = $folder_upload . $nama_baru; 

            // Pindahkan file yang diupload
            if (!move_uploaded_file($file_tmp, $target_file)) {
                 throw new Exception("Gagal memindahkan file foto ke server.");
            }
            
            // Kalau berhasil, set nama file foto baru
            $nama_file_foto = $nama_baru;
            
            // Khusus untuk EDIT: Hapus foto lama di server jika ada foto baru diupload
            // Pastikan foto lama ada dan berbeda dengan foto default/kosong
            if ($id_berita > 0 && !empty($_POST['foto_lama'])) {
                $foto_lama_path = $folder_upload . $_POST['foto_lama'];
                // Cek apakah foto lama BUKAN foto default ('1.jpg' atau semacamnya) sebelum dihapus
                if (file_exists($foto_lama_path) && $_POST['foto_lama'] !== '1.jpg') {
                    unlink($foto_lama_path);
                }
            }
        } 
        // END PROSES UPLOAD GAMBAR

        // 2. QUERY KE DATABASE (INSERT atau UPDATE)
        if ($id_berita > 0) {
            // --- EDIT/UPDATE DATA ---
            $sql = "UPDATE berita SET judul=?, isi=?, tanggal_publikasi=?, level=?, foto=? WHERE id=?";
            $stmt = $koneksi->prepare($sql);
            // "sssssi" = 5 string (judul, isi, tgl, level, foto) + 1 integer (id)
            $stmt->bind_param("sssssi", $judul, $isi, $tanggal, $level, $nama_file_foto, $id_berita);
            
            if (!$stmt->execute()) {
                 throw new Exception("Gagal mengubah berita: " . $stmt->error);
            }
            $status_redirect = "success_edit";
            
        } else {
            // --- TAMBAH/INSERT DATA BARU ---
            // Cek foto untuk berita baru (sudah diatur 'required' di form, tapi double-check lebih baik)
            if (empty($nama_file_foto)) {
                 throw new Exception("Foto harus diupload untuk konten baru.");
            }

            $sql = "INSERT INTO berita (judul, isi, tanggal_publikasi, level, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssss", $judul, $isi, $tanggal, $level, $nama_file_foto);
            
            if (!$stmt->execute()) {
                 throw new Exception("Gagal menambahkan berita: " . $stmt->error);
            }
            $status_redirect = "success_add";
        }

    } catch (Exception $e) {
        // Tangani error, dan kembalikan status error
        $status_redirect = "error_crud&pesan=" . urlencode($e->getMessage());
        // Hapus file yang baru diupload jika terjadi error database
        if ($upload_baru && file_exists($folder_upload . $nama_file_foto)) {
            unlink($folder_upload . $nama_file_foto);
        }
    }
    
    $koneksi->close();
    header("Location: index_admin.php?status=$status_redirect"); 
    exit;
}


// ======================================================================
// --- 2. BAGIAN DELETE (Hapus Data Berita) ---
// ======================================================================
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // 1. Ambil nama file foto lama dulu sebelum datanya dihapus
    $stmt_get = $koneksi->prepare("SELECT foto FROM berita WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    $stmt_get->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Cek apakah foto yang akan dihapus BUKAN foto default (misal: '1.jpg')
        if (!empty($row['foto']) && $row['foto'] !== '1.jpg') {
             $file_to_delete = $folder_upload . $row['foto'];
             // 2. Hapus file foto dari folder 'foto_berita'
             if (file_exists($file_to_delete)) {
                 unlink($file_to_delete);
             }
        }
    }

    // 3. Hapus data beritanya dari database
    $stmt_delete = $koneksi->prepare("DELETE FROM berita WHERE id = ?");
    $stmt_delete->bind_param("i", $id); 
    
    if ($stmt_delete->execute()) {
        header("Location: index_admin.php?status=success_delete");
    } else {
        header("Location: index_admin.php?status=error_delete&pesan=" . urlencode("Gagal menghapus data dari database."));
    }
    $stmt_delete->close();
    exit(); 
}


// ======================================================================
// --- 3. BAGIAN GET REQUEST (Menampilkan Form Edit/Tambah) ---
// ======================================================================
$berita_to_edit = [
    'id' => 0,
    'judul' => '',
    'isi' => '',
    'level' => 'berita',
    'foto' => '' // Nama file foto saja
];
$form_title = "Tambah Berita/Pengumuman Baru";
$is_tambah_mode = true; // Flag untuk mode tambah

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_berita = (int)$_GET['id'];
    $is_tambah_mode = false; //  mode edit
    
    // Ambil data dari database untuk diisi ke form
    $sql_edit = "SELECT id, judul, isi, level, foto FROM berita WHERE id = ?";
    $stmt_edit = $koneksi->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id_berita);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    
    if ($result_edit->num_rows > 0) {
        $berita_to_edit = $result_edit->fetch_assoc();
        $form_title = "Edit Berita/Pengumuman: " . htmlspecialchars($berita_to_edit['judul']);
    } else {
        header("Location: index_admin.php?status=error_not_found");
        exit;
    }
    $stmt_edit->close();
}

// Koneksi ditutup di bagian POST dan DELETE, tapi perlu ditutup juga di sini 
// jika tidak ada proses POST/DELETE yang terjadi (hanya menampilkan form)
if (isset($koneksi) && $koneksi instanceof mysqli && $koneksi->ping()) {
    $koneksi->close(); 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $form_title ?></title>
    <link rel="stylesheet" href="css/index.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Variabel CSS harus didefinisikan atau di-link dari index.css */
        
        .crud-form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--light, #f8f0e8); /* Fallback warna */
            border-radius: var(--border-radius-lg, 10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .crud-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--dark, #2a0a0a);
        }
        .crud-form input[type="text"],
        .crud-form textarea,
        .crud-form select,
        .crud-form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--light-gray, #e8e8e8);
            border-radius: var(--border-radius-sm, 5px);
            box-sizing: border-box;
        }
        .crud-form textarea {
            min-height: 150px;
        }
        .current-foto-preview {
            margin-bottom: 20px;
            border: 1px solid var(--light-gray, #e8e8e8);
            padding: 10px;
            border-radius: var(--border-radius-sm, 5px);
        }
        .current-foto-preview img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            display: block;
            border-radius: var(--border-radius-sm, 5px);
        }
        .btn-submit {
            background-color: var(--primary, #800000);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: var(--border-radius-sm, 5px);
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition, 0.3s);
            display: inline-block; /* Agar sejajar dengan tombol kembali */
            margin-right: 10px;
        }
        .btn-submit:hover {
            background-color: var(--primary-light, #a33333);
        }
        .btn-back {
            background-color: var(--gray, #64748b);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: var(--border-radius-sm, 5px);
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition, 0.3s);
        }
        .btn-back:hover {
            background-color: #5a6679;
        }
    </style>
</head>
<body>
    <div class="crud-form-container">
        <h1 class="section__title" style="text-align: center;"><?= $form_title ?></h1>
        
        <form class="crud-form" action="crud_berita.php" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id_berita" value="<?= $berita_to_edit['id'] ?>">
            
            <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($berita_to_edit['foto']) ?>">
            
            <div class="form-group">
                <label for="judul">Judul Berita/Pengumuman</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($berita_to_edit['judul']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="isi">Isi Berita/Pengumuman</label>
                <textarea id="isi" name="isi" required><?= htmlspecialchars($berita_to_edit['isi']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="level">Tipe Konten</label>
                <select id="level" name="level" required>
                    <option value="berita" <?= $berita_to_edit['level'] == 'berita' ? 'selected' : '' ?>>📰 Berita</option>
                    <option value="pengumuman" <?= $berita_to_edit['level'] == 'pengumuman' ? 'selected' : '' ?>>📢 Pengumuman</option>
                </select>
            </div>

            <div class="form-group">
                <label for="foto_berita">Foto Utama 
                    <?php if ($is_tambah_mode): ?>
                        <span style="color: var(--danger, #CC0000); font-weight: normal;">(Wajib diisi)</span>
                    <?php else: ?>
                        <span style="color: var(--gray, #64748b); font-weight: normal;">(Kosongkan jika tidak ingin diubah)</span>
                    <?php endif; ?>
                </label>
                <input type="file" id="foto_berita" name="foto_berita" accept="image/*" 
                    <?= $is_tambah_mode ? 'required' : '' ?>> 
                <small style="color: var(--gray, #64748b);">Hanya terima format JPG, PNG, GIF.</small>
            </div>
            
            <?php if ($berita_to_edit['id'] > 0 && !empty($berita_to_edit['foto'])): ?>
                <div class="current-foto-preview">
                    <p>Foto Saat Ini:</p>
                    <img src="<?= htmlspecialchars($folder_upload . $berita_to_edit['foto']) ?>" alt="Foto Berita Saat Ini">
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> 
                <?= $berita_to_edit['id'] > 0 ? 'Simpan Perubahan' : 'Terbitkan Konten' ?>
            </button>
            
            <a href="index_admin.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </div>

</body>
</html>