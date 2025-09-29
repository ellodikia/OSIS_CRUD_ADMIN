<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struktur OSIS Raksana</title>
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
  <link rel="stylesheet" href="struktur.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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
        <a href="gallery.php" class="nav__link">Galeri</a>
        <a href="news.php" class="nav__link">Berita & Pengumuman</a>
      </nav>
      
      <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
      <div class="nav-overlay"></div>
    </div>
  </header>

  <!-- Struktur OSIS -->
  <main class="container struktur">
    <h1 class="title">Struktur Pengurus OSIS</h1>

    <!-- Pembina -->
    <section>
      <h2>Pembina OSIS</h2>
      <div class="card clickable" data-modal="pembina" data-img="foto/pembina.jpg">
        <img src="foto/pembina.jpg" alt="Pembina OSIS" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Pembina'">
        <h3>Nama Pembina</h3>
        <p>Pembina OSIS</p>
      </div>
    </section>

    <!-- Ketos & Waketos -->
    <section>
      <h2>Ketua & Wakil Ketua OSIS</h2>
      <div class="grid">
        <div class="card clickable" data-modal="ketos" data-img="foto/ketos.jpg">
          <img src="foto/ketos.jpg" alt="Ketua OSIS" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Ketua'">
          <h3>Jesika</h3>
          <p>Ketua OSIS</p>
        </div>
        <div class="card clickable" data-modal="waketos" data-img="foto/kak_farid.jpg">
          <img src="foto/kak_farid.jpg" alt="Wakil Ketua OSIS" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Wakil'">
          <h3>Farid</h3>
          <p>Wakil Ketua OSIS</p>
        </div>
      </div>
    </section>

    <!-- BPH -->
    <section>
      <h2>Badan Pengurus Harian (BPH)</h2>
      <div class="grid">
        <div class="card clickable" data-modal="sekretaris" data-img="foto/kak_raysa.jpg">
          <img src="foto/kak_raysa.jpg" alt="Sekretaris" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Sekretaris'">
          <h3>Raysa</h3>
          <p>Sekretaris</p>
        </div>
        <div class="card clickable" data-modal="wakil-sekretaris" data-img="foto/kak_angel.jpg">
          <img src="foto/kak_angel.jpg" alt="wakil-sekretaris" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Bendahara'">
          <h3>Angel</h3>
          <p>Wakil Sekretaris</p>
        </div>
        <div class="card clickable" data-modal="bendahara" data-img="foto/kak_clara.jpg">
          <img src="foto/kak_clara.jpg" alt="Bendahara" onerror="this.src='https://via.placeholder.com/140x140?text=Foto+Koordinator'">
          <h3>Clara</h3>
          <p>Bendahara</p>
        </div>
      </div>
    </section>

    <!-- Departemen -->
    <section>
      <h2>Departemen</h2>
      <div class="grid">
        <!-- Dep. Keimanan dan ketakwaan TYME -->
        <div class="card clickable" data-modal="dep1" data-img="foto/dep1-kor.jpg">
          <img src="foto/kak_nita.jpg" alt="Koordinator Dep. Keimanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Keimanan dan Ketakwaan TYME (Agama Islam)</h3>
          <p>Nita</p>
        </div>

        <div class="card clickable" data-modal="dep1" data-img="foto/dep1-kor.jpg">
          <img src="foto/kak_kevin.jpg" alt="Koordinator Dep. Keimanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Keimanan dan Ketakwaan TYME (Agama Kristen)</h3>
          <p>Kevin</p>
        </div>

        <!-- Dep. Kreativitas sastra dan budaya -->
        <div class="card clickable" data-modal="dep2" data-img="foto/dep2-kor.jpg">
          <img src="foto/kak_rizzy.jpg" alt="Koordinator Dep. Kreativitas" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Kreativitas Sastra dan Budaya</h3>
          <p>Rizzy</p>
        </div>

        <!-- Dep. Bahasa asing -->
        <div class="card clickable" data-modal="dep3" data-img="foto/dep3-kor.jpg">
          <img src="foto/kak_cindi.jpg" alt="Koordinator Dep. Bahasa Asing" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Bahasa Asing</h3>
          <p>Cindi</p>
        </div>

        <!-- Dep Kesehatan gizi dan lingkungan -->
        <div class="card clickable" data-modal="dep4" data-img="foto/dep4-kor.jpg">
          <img src="foto/kak_sandra.jpg" alt="Koordinator Dep. Kesehatan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Kesehatan, Gizi dan Lingkungan</h3>
          <p>Sandra</p>
        </div>

        <!-- Dep. Prestasi akademik dan olahraga -->
        <div class="card clickable" data-modal="dep5" data-img="foto/dep5-kor.jpg">
          <img src="foto/kak_ivan.jpg" alt="Koordinator Dep. Prestasi" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Prestasi Akademik dan Olahraga</h3>
          <p>Koordinator: Nama Koordinator</p>
        </div>

        <!-- Dep. Humas dan infokum -->
        <div class="card clickable" data-modal="dep6" data-img="foto/dep6-kor.jpg">
          <img src="foto/kak_ridho.jpg" alt="Koordinator Dep. Humas" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Humas dan Infokum</h3>
          <p>Ridho</p>
        </div>

        <!-- Dep. Pengamanan Strategis setiap unit -->
        <div class="card clickable" data-modal="dep7" data-img="foto/dep7-kor.jpg">
          <img src="foto/kak_kyara.jpg" alt="Koordinator Dep. Pengamanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Pengamanan Strategis Setiap Unit (SMP)</h3>
          <p>Kyara</p>
        </div>
        
        <div class="card clickable" data-modal="dep7" data-img="foto/dep7-kor.jpg">
          <img src="foto/kak_jesi.jpg" alt="Koordinator Dep. Pengamanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Pengamanan Strategis Setiap Unit (SMA)</h3>
          <p>Jesie</p>
        </div>
        <div class="card clickable" data-modal="dep7" data-img="foto/dep7-kor.jpg">
          <img src="foto/kak_kenzo.jpg" alt="Koordinator Dep. Pengamanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Pengamanan Strategis Setiap Unit (SMK 1)</h3>
          <p>Kenzo</p>
        </div>

        <div class="card clickable" data-modal="dep7" data-img="foto/dep7-kor.jpg">
          <img src="foto/kak_mutia.jpg" alt="Koordinator Dep. Pengamanan" onerror="this.src='https://via.placeholder.com/140x140?text=Koor+Dept'">
          <h3>Pengamanan Strategis Setiap Unit (SMK 2)</h3>
          <p>Mutia</p>
        </div>
      </div>
    </section>

    
  </main>

  <!-- Modal Template -->
  <div class="modal-overlay"></div>
  
  <!-- Modal untuk Pembina -->
  <div class="modal" id="modal-pembina">
    <div class="modal-header">
      <img src="foto/pembina.jpg" alt="Pembina OSIS" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Pembina'">
      <h2>Nama Pembina</h2>
      <h3>Pembina OSIS</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> - </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Ketua OSIS -->
  <div class="modal" id="modal-ketos">
    <div class="modal-header">
      <img src="foto/ketos.jpg" alt="Ketua OSIS" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Ketua'">
      <h2>Nama Ketua</h2>
      <h3>Ketua OSIS</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Wakil Ketua OSIS -->
  <div class="modal" id="modal-waketos">
    <div class="modal-header">
      <img src="foto/kak_farid.jpg" alt="Wakil Ketua OSIS" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Wakil'">
      <h2>Farid</h2>
      <h3>Wakil Ketua OSIS</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Sekretaris -->
  <div class="modal" id="modal-sekretaris">
    <div class="modal-header">
      <img src="foto/kak_raysa.jpg" alt="Sekretaris" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Sekretaris'">
      <h2>Raysa</h2>
      <h3>Sekretaris</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Wakil Sekretaris -->
  <div class="modal" id="modal-wakil-sekretaris">
    <div class="modal-header">
      <img src="foto/kak_angel.jpg" alt="Wakil Sekretaris" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Wakil+Sekretaris'">
      <h2>Angel</h2>
      <h3>Wakil Sekretaris</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Bendahara -->
  <div class="modal" id="modal-bendahara">
    <div class="modal-header">
      <img src="foto/kak_clara.jpg" alt="Bendahara" onerror="this.src='https://via.placeholder.com/180x180?text=Foto+Bendahara'">
      <h2>Clara</h2>
      <h3>Bendahara</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> - </p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li> - </li>
        <li> - </li>
        <li> - </li>
        <li> - </li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Koordinator Departemen -->
  <div class="modal" id="modal-dep1-kor">
    <div class="modal-header">
      <img src="foto/dep1-kor.jpg" alt="Koordinator Dep. Keimanan" onerror="this.src='https://via.placeholder.com/180x180?text=Koor+Dept'">
      <h2>Nama Koordinator</h2>
      <h3>Koordinator Dep. Keimanan dan Ketakwaan TYME</h3>
    </div>
    <div class="modal-content">
      <p><strong>Visi:</strong> Meningkatkan keimanan dan ketakwaan seluruh siswa.</p>
      <p><strong>Misi:</strong></p>
      <ul>
        <li>Menyelenggarakan kegiatan keagamaan</li>
        <li>Mengkoordinir kegiatan rohani Islam dan Kristen</li>
        <li>Meningkatkan toleransi beragama di sekolah</li>
        <li>Memfasilitasi siswa dalam kegiatan keagamaan</li>
      </ul>
    </div>
  </div>

  <!-- Modal untuk Anggota Departemen -->
  <div class="modal" id="modal-dep1-ang1">
    <div class="modal-header">
      <img src="foto/dep1-ang1.jpg" alt="Anggota Dep. Keimanan" onerror="this.src='https://via.placeholder.com/180x180?text=Anggota'">
      <h2>Nama Anggota</h2>
      <h3>Anggota Dep. Keimanan dan Ketakwaan TYME (Agama Islam)</h3>
    </div>
    <div class="modal-content">
      <p><strong>Tugas:</strong></p>
      <ul>
        <li>Mengkoordinir kegiatan keagamaan Islam</li>
        <li>Mempersiapkan materi untuk kegiatan keagamaan</li>
        <li>Menjadi contoh dalam berperilaku sesuai ajaran agama</li>
        <li>Membantu pelaksanaan kegiatan keagamaan</li>
      </ul>
    </div>
  </div>

 

  <script src="struktur.js"></script>
</body>
</html>