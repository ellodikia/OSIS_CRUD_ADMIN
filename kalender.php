<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="foto/logo-osis.png">
  <title>Kalender Kegiatan OSIS Raksana</title>
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/kalender.css">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="container header__container">
      <div class="header__logo-container">
        <img src="foto/logo-sekolah.jpg" alt="Logo Sekolah" class="header__logo">
        <img src="foto/logo-osis.png" alt="Logo OSIS" class="header__logo">
      </div>
      <nav class="nav">
        <a href="index.php" class="nav__link">Beranda</a>
        <a href="struktur.php" class="nav__link">Struktur OSIS</a>
        <a href="kalender.php" class="nav__link active">Kalender Kegiatan</a>
        <a href="gallery.php" class="nav__link">Galeri</a>
        <a href="news.php" class="nav__link">Berita & Pengumuman</a>
      </nav>
      <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
      <div class="nav-overlay"></div>
    </div>
  </header>

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
        <h3>Kegiatan Mendatang</h3>
        <ul id="events-list" class="events-list"></ul>
      </div>
    </div>
  </main>

  <!-- Modal -->
  <div id="event-modal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 id="modal-title"></h2>
      <p><strong>Tanggal:</strong> <span id="modal-date"></span></p>
      <p><strong>Waktu:</strong> <span id="modal-time"></span></p>
      <p><strong>Lokasi:</strong> <span id="modal-location"></span></p>
      <p><strong>Penanggung Jawab:</strong> <span id="modal-person"></span></p>
      <p id="modal-description"></p>
      <h4>Persiapan:</h4>
      <ul id="modal-preparations" class="modal-preparations"></ul>
      <div class="modal-actions">
        <button class="share-btn"><i class="fas fa-share-alt"></i> Bagikan</button>
        <button class="reminder-btn"><i class="fas fa-bell"></i> Ingatkan</button>
      </div>
    </div>
  </div>

  <script src="js/kalender.js"></script>
</body>
</html>