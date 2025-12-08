<?php
// =================================================================
// 🛠️ GELİŞTİRME ALANI (BURAYI SEN DOLDURACAKSIN)
// =================================================================

// ŞU AN: Sayfa boş görünmesin diye "Vizyondakiler" ve "Yakında" için sahte veriler var.
// SENİN GÖREVİN: Bu dizileri silip, yerine DB bağlantısını ve SQL sorgularını yazmak.

// 1. Vizyondakiler Listesi (Mock Data)
$vizyondakiler = [
    ["movie_id" => 1, "title" => "Oppenheimer", "category" => "Biyografi", "image_url" => "https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg"],
    ["movie_id" => 2, "title" => "Barbie", "category" => "Komedi", "image_url" => "https://image.tmdb.org/t/p/w500/iuFNMS8U5cb6xfzi51Dbkovj7vM.jpg"],
    ["movie_id" => 3, "title" => "John Wick 4", "category" => "Aksiyon", "image_url" => "https://image.tmdb.org/t/p/w500/vZloFAK7NmvMGKE7VkF5UHaz0I.jpg"],
    ["movie_id" => 4, "title" => "Örümcek Adam", "category" => "Animasyon", "image_url" => "https://image.tmdb.org/t/p/w500/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg"],
    ["movie_id" => 5, "title" => "Avatar 2", "category" => "Bilim Kurgu", "image_url" => "https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg"],
    ["movie_id" => 6, "title" => "Hızlı ve Öfkeli 10", "category" => "Suç", "image_url" => "https://image.tmdb.org/t/p/w500/fiVW06jE7z9YnO4trhaMEdclSiC.jpg"]
];

// 2. Yakında Gelecekler Listesi (Mock Data)
$yakindakiler = [
    ["movie_id" => 7, "title" => "Dune: Çöl Gezegeni 2", "category" => "Bilim Kurgu", "image_url" => "https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg"],
    ["movie_id" => 8, "title" => "Deadpool 3", "category" => "Aksiyon", "image_url" => "https://image.tmdb.org/t/p/w500/yF1eOkaYvwiORauRCPWznV9xVvi.jpg"]
];

// =================================================================
// 🎨 HTML ARAYÜZ (BURASI SABİT KALACAK)
// =================================================================
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFlux - Sinema Veritabanı</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- İkonlar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Özel CSS Dosyamız -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-play-circle me-2 text-info"></i>FilmFlux
            </a>
            
            <!-- Arama Kutusu -->
            <div class="d-none d-md-block w-50">
                <form action="index.php" method="GET" class="position-relative">
                    <input type="text" name="q" class="form-control search-input" placeholder="Film, yönetmen veya oyuncu ara...">
                    <button type="submit" class="btn position-absolute top-0 end-0 text-white"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- Sağ Taraf: Giriş / Kayıt -->
            <div class="d-flex align-items-center gap-2">
                <!-- Şimdilik sadece butonlar var. İlerde PHP ile SESSION kontrolü buraya gelecek -->
                <a href="login.php" class="btn btn-sm btn-outline-light px-3 rounded-pill">Giriş Yap</a>
                <a href="register.php" class="btn btn-sm btn-light px-3 rounded-pill text-primary fw-bold">Kayıt Ol</a>
            </div>
        </div>
    </nav>

    <!-- ALT MENÜ -->
    <div class="sub-menu">
        <div class="container d-flex overflow-auto">
            <a href="#"><i class="fas fa-film me-1"></i> Sinema Filmleri</a>
            <a href="#"><i class="fas fa-tv me-1"></i> Platform Filmleri</a>
            <a href="#"><i class="fas fa-clock me-1"></i> Son Çıkanlar</a>
            <a href="#"><i class="fas fa-fire me-1"></i> Haftanın Popülerleri</a>
            <a href="#"><i class="fas fa-layer-group me-1"></i> Tüm Filmler</a>
        </div>
    </div>

    <!-- ANA İÇERİK -->
    <div class="container pb-5" style="min-height: 600px;">
        
        <!-- BÖLÜM 1: VİZYONDAKİLER -->
        <h2 class="section-title">
            Vizyondaki Filmler 
            <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size: 12px;">Tümünü Gör</a>
        </h2>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if(empty($vizyondakiler)): ?>
                <div class="col-12"><p class="text-muted">Henüz film eklenmemiş.</p></div>
            <?php else: ?>
                <?php foreach($vizyondakiler as $movie): ?>
                <div class="col">
                    <a href="detay.php?id=<?php echo $movie['movie_id']; ?>" class="text-decoration-none">
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </div>
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-info"><?php echo htmlspecialchars($movie['category']); ?></div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- BÖLÜM 2: YAKINDA SİNEMALARDA -->
        <h2 class="section-title mt-5">
            Yakında Sinemalarda
            <span class="badge bg-danger rounded-pill fs-6 ms-2">Yeni</span>
        </h2>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if(empty($yakindakiler)): ?>
                <div class="col-12"><p class="text-muted">Yakında gelecek film bulunamadı.</p></div>
            <?php else: ?>
                <?php foreach($yakindakiler as $movie): ?>
                <div class="col">
                    <!-- Tıklayınca uyarı veren Yakında filmleri -->
                    <a href="#" onclick="alert('Bu film yakında vizyona girecek!'); return false;" class="text-decoration-none">
                        <div class="movie-card opacity-75"> 
                            <div class="movie-poster">
                                <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                <!-- Yakında Etiketi -->
                                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small rounded-start" style="font-size: 10px;">YAKINDA</div>
                            </div>
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-info text-danger">Tarih Bekleniyor</div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- COMPACT FOOTER -->
    <footer>
        <div class="container">
            <div class="row justify-content-between">
                
                <!-- Marka -->
                <div class="col-md-5 mb-3">
                    <h5 class="text-white"><i class="fas fa-play-circle me-2 text-primary"></i>FilmFlux</h5>
                    <p class="small text-secondary mb-3">
                        Sinema dünyasının nabzını tutan modern veri tabanı platformu. 
                    </p>
                </div>

                <!-- Linkler -->
                <div class="col-md-3 mb-3">
                    <h5>Hızlı Erişim</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Anasayfa</a></li>
                        <li><a href="#">Vizyondakiler</a></li>
                    </ul>
                </div>

                <!-- Sosyal -->
                <div class="col-md-3 mb-3">
                    <h5>Takip Et</h5>
                    <div class="d-flex">
                        <a href="#" class="social-icon bg-instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon bg-linkedin"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon bg-mail"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>

            </div>
        </div>

        <div class="copyright text-center">
            <p class="mb-0 text-secondary small">
                &copy; 2025 <strong>FilmFlux</strong>. Tasarım: <span class="text-white">Aydın ŞAHİN</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>