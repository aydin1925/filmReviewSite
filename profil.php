<?php
session_start();

// ========================================================
// 🛠️ MOCK DATA (Veritabanı Yerine Geçici Veri)
// ========================================================
// Veritabanı bağlandığında burası $_SESSION['user_id'] ile 
// veritabanından çekilecek.

$user = [
    "username" => "AydinSahin",
    "email" => "aydin@filmflux.com",
    "join_date" => "12 Aralık 2024",
    // Rastgele bir avatar (İsim baş harflerinden oluşur)
    "avatar" => "https://ui-avatars.com/api/?name=Aydin+Sahin&background=0D8ABC&color=fff&size=150&font-size=0.5",
    "stats" => [
        "comments" => 12,
        "favorites" => 5,
        "likes" => 24
    ]
];

// Favori Filmler (Mock)
$favorites = [
    ["title" => "Inception", "image" => "https://image.tmdb.org/t/p/w200/9gk7admal4zlck90G2sQ83spJC4.jpg", "year" => 2010],
    ["title" => "The Dark Knight", "image" => "https://image.tmdb.org/t/p/w200/qJ2tW6WMUDux911r6m7haRef0WH.jpg", "year" => 2008],
    ["title" => "Interstellar", "image" => "https://image.tmdb.org/t/p/w200/gEU2QniL6C8z1dY4kdNON4k6sKs.jpg", "year" => 2014],
    ["title" => "Matrix", "image" => "https://image.tmdb.org/t/p/w200/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg", "year" => 1999]
];

// Son Yorumlar (Mock)
$my_reviews = [
    [
        "movie" => "Oppenheimer", 
        "comment" => "Mükemmel bir sinematografi, Nolan yine yapmış yapacağını. Özellikle ses tasarımı inanılmazdı.", 
        "rating" => 9, 
        "date" => "2 gün önce"
    ],
    [
        "movie" => "Barbie", 
        "comment" => "Beklediğimden daha derin bir hikayesi vardı ama bazı sahneler çok uzatılmıştı.", 
        "rating" => 7, 
        "date" => "1 hafta önce"
    ]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - FilmFlux</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Özel CSS Dosyamız -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR (Index ile uyumlu) -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-play-circle me-2 text-info"></i>FilmFlux
            </a>
            
            <div class="d-flex align-items-center">
                <a href="index.php" class="text-white text-decoration-none small opacity-75 hover-opacity-100 transition">
                    <i class="fas fa-arrow-left me-1"></i> Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <!-- PROFİL BAŞLIĞI (HEADER) -->
        <div class="profile-header mt-4">
            <!-- Avatar Resmi -->
            <div class="profile-avatar">
                <img src="<?php echo $user['avatar']; ?>" alt="Profil Resmi">
            </div>
        </div>

        <!-- PROFİL MENÜSÜ & İSİM -->
        <div class="profile-nav d-flex justify-content-between align-items-center mb-4 px-4 bg-white rounded-bottom shadow-sm">
            <div>
                <h2 class="mb-0 fw-bold text-dark fs-3"><?php echo $user['username']; ?></h2>
                <p class="text-muted small mb-2"><i class="fas fa-envelope me-1"></i> <?php echo $user['email']; ?></p>
            </div>
            
            <div class="d-none d-md-block">
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill me-2"><i class="fas fa-cog me-1"></i> Ayarlar</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill"><i class="fas fa-sign-out-alt me-1"></i> Çıkış</a>
            </div>
        </div>

        <div class="row">
            
            <!-- SOL SÜTUN: İstatistikler ve Menü -->
            <div class="col-md-4 mb-4">
                
                <!-- İstatistikler (Stat Cards) -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $user['stats']['comments']; ?></div>
                            <div class="stat-label">Yorum</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $user['stats']['favorites']; ?></div>
                            <div class="stat-label">Favori</div>
                        </div>
                    </div>
                </div>

                <!-- Yan Menü Listesi -->
                <div class="list-group custom-list-group">
                    <a href="#" class="list-group-item list-group-item-action active d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-circle me-2"></i> Profil Bilgileri</span>
                        <i class="fas fa-chevron-right small"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-heart me-2 text-danger"></i> Favorilerim</span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $user['stats']['favorites']; ?></span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-comment-alt me-2 text-primary"></i> Yorumlarım</span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $user['stats']['comments']; ?></span>
                    </a>
                    <!-- Mobilde görünen çıkış butonu -->
                    <a href="logout.php" class="list-group-item list-group-item-action text-danger d-md-none">
                        <i class="fas fa-sign-out-alt me-2"></i> Çıkış Yap
                    </a>
                </div>
            </div>

            <!-- SAĞ SÜTUN: İçerik (Favoriler & Son Yorumlar) -->
            <div class="col-md-8">
                
                <!-- Bölüm 1: Favori Filmler -->
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fas fa-heart text-danger me-2"></i>Favori Filmler
                </h5>
                
                <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
                    <?php foreach($favorites as $fav): ?>
                    <div class="col">
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="<?php echo $fav['image']; ?>" alt="<?php echo htmlspecialchars($fav['title']); ?>">
                            </div>
                            <div class="movie-title small text-truncate"><?php echo htmlspecialchars($fav['title']); ?></div>
                            <div class="movie-info small"><?php echo $fav['year']; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bölüm 2: Son Yorumlar -->
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fas fa-comment text-primary me-2"></i>Son Yorumların
                </h5>
                
                <div class="card border-0 shadow-sm">
                    <ul class="list-group list-group-flush">
                        <?php foreach($my_reviews as $review): ?>
                        <li class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-film me-1 text-muted small"></i>
                                    <?php echo htmlspecialchars($review['movie']); ?>
                                </h6>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i><?php echo $review['rating']; ?>
                                </span>
                            </div>
                            <p class="text-muted small mb-1 fst-italic">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                            <small class="text-secondary" style="font-size: 11px;">
                                <i class="far fa-clock me-1"></i> <?php echo $review['date']; ?>
                            </small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>

        </div>
    </div>

    <!-- DENGELİ FOOTER -->
    <footer>
        <div class="container">
            <div class="row">
                
                <!-- 1. Marka (4 Birim) -->
                <div class="col-md-4 mb-4">
                    <h5 class="text-white"><i class="fas fa-play-circle me-2 text-primary"></i>FilmFlux</h5>
                    <p class="small text-secondary mb-3">
                        Sinema dünyasının nabzını tutan modern veri tabanı platformu. 
                        İzlediğiniz filmleri keşfedin, detaylarına ulaşın.
                    </p>
                </div>

                <!-- 2. Linkler (4 Birim - Ortalanmış) -->
                <div class="col-md-4 mb-4 text-md-center">
                    <h5>Hızlı Erişim</h5>
                    <ul class="list-unstyled d-inline-block text-start">
                        <li><a href="index.php">Anasayfa</a></li>
                        <li><a href="#">Vizyondakiler</a></li>
                        <li><a href="#">Yakında</a></li>
                        <li><a href="#">İletişim</a></li>
                    </ul>
                </div>

                <!-- 3. Sosyal Medya (4 Birim - Sağa Yaslı) -->
                <div class="col-md-4 mb-4 text-md-end">
                    <h5>Bizi Takip Edin</h5>
                    <div class="d-flex justify-content-md-end justify-content-start">
                        <a href="#" class="social-icon bg-instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon bg-linkedin"><i class="fab fa-linkedin-in"></i></a>
                        <a href="mailto:info@filmflux.com" class="social-icon bg-mail"><i class="fas fa-envelope"></i></a>
                    </div>
                    <p class="small text-secondary mt-3">
                        &copy; 2025 FilmFlux.<br>Tasarım: <span class="text-white">Aydın ŞAHİN</span>
                    </p>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>