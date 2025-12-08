<?php
// ========================================================
// 🛠️ MOCK DATA (Veritabanı Yerine Geçici Veri)
// ========================================================
// Sen veritabanı bağlantısını yapana kadar sayfa bu verilerle çalışacak.
// Bağlantıyı kurduğunda bu diziyi silip yerine SQL sorgusu yazacaksın.

$film = [
    "movie_id" => 7,
    "title" => "Dune: Çöl Gezegeni Bölüm İki",
    "description" => "Paul Atreides, ailesini yok eden komploculara karşı intikam savaşına girerken Chani ve Fremenlerle güçlerini birleştirir. Hayatının aşkı ile bilinen evrenin kaderi arasında bir seçim yapmak zorunda kalan Paul, yalnızca kendisinin öngörebileceği korkunç bir geleceği engellemeye çalışır.",
    "director" => "Denis Villeneuve",
    "release_year" => 2024,
    "category" => "Bilim Kurgu, Macera",
    "image_url" => "https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg",
    "bg_image" => "https://image.tmdb.org/t/p/original/xOMo8BRK7PfcJv9JCnx7s5hj0PX.jpg",
    "rating" => 8.8,
    "cast" => "Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem"
];

// Yorumlar da şimdilik sahte
$yorumlar = [
    ["user" => "Ahmet Y.", "comment" => "Nolan yine yapmış yapacağını. Ses tasarımı inanılmazdı.", "date" => "12.10.2023"],
    ["user" => "Zeynep K.", "comment" => "Biraz uzundu ama her dakikasına değdi. Oyunculuklar muazzam.", "date" => "15.10.2023"]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $film['title']; ?> - FilmFlux</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- İkonlar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Senin Oluşturduğun Özel CSS Dosyası -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR (Index ile aynı) -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php"><i class="fas fa-play-circle me-2 text-info"></i>FilmFlux</a>
            
            <div class="d-none d-md-block w-50">
                <form class="position-relative">
                    <input type="text" class="form-control search-input" placeholder="Film ara...">
                    <button type="button" class="btn position-absolute top-0 end-0 text-white"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="d-flex align-items-center">
                <a href="#" class="nav-icon"><i class="far fa-user"></i></a>
            </div>
        </div>
    </nav>

    <!-- 1. BÖLÜM: HERO ALANI (Bulanık Arka Planlı Bölüm) -->
    <!-- Dinamik arka plan resmi PHP'den geliyor -->
    <div class="movie-hero" style="background-image: url('<?php echo $film['bg_image']; ?>');">
        <div class="container">
            <div class="row align-items-start">
                
                <!-- Sol: Film Afişi -->
                <div class="col-md-3 text-center text-md-start">
                    <img src="<?php echo $film['image_url']; ?>" class="img-fluid detail-poster" alt="Film Afişi">
                    
                    <!-- Aksiyon Butonları -->
                    <div class="mt-3 d-grid gap-2">
                        <button class="btn btn-primary"><i class="fas fa-play me-2"></i>Fragman</button>
                        <button class="btn btn-outline-light"><i class="fas fa-plus me-2"></i>Listeme Ekle</button>
                    </div>
                </div>

                <!-- Sağ: Film Bilgileri -->
                <div class="col-md-9 mt-4 mt-md-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <!-- Film Başlığı -->
                            <h1 class="display-5 fw-bold mb-2"><?php echo $film['title']; ?></h1>
                            
                            <!-- Etiketler (Yıl, Tür, Süre) -->
                            <div class="mb-3">
                                <span class="movie-meta-badge"><i class="far fa-calendar me-1"></i> <?php echo $film['release_year']; ?></span>
                                <span class="movie-meta-badge"><i class="fas fa-film me-1"></i> <?php echo $film['category']; ?></span>
                                <span class="movie-meta-badge"><i class="far fa-clock me-1"></i> 180 dk</span>
                            </div>
                        </div>
                        
                        <!-- Puan Kutusu (CSS ile tasarladığımız) -->
                        <div class="rating-box">
                            <span class="rating-score"><?php echo $film['rating']; ?></span>
                            <span class="rating-max">/10</span>
                        </div>
                    </div>

                    <!-- Film Özeti -->
                    <p class="lead fs-6 mt-3" style="color: #cbd5e1; line-height: 1.8;">
                        <?php echo $film['description']; ?>
                    </p>

                    <!-- Yönetmen Bilgisi -->
                    <div class="director-box">
                        <h6 class="text-uppercase text-white-50" style="font-size: 12px; letter-spacing: 1px;">Yönetmen</h6>
                        <span class="fs-5 fw-medium"><?php echo $film['director']; ?></span>
                    </div>

                    <!-- Oyuncular -->
                    <div class="mt-3">
                        <h6 class="text-uppercase text-white-50" style="font-size: 12px; letter-spacing: 1px;">Oyuncular</h6>
                        <span class="text-white-50"><?php echo $film['cast']; ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- 2. BÖLÜM: YORUMLAR VE YAN MENÜ -->
    <div class="container pb-5">
        <div class="row">
            
            <!-- Sol Geniş Alan: Yorumlar -->
            <div class="col-md-8">
                <h3 class="section-title border-0 p-0 mt-0 mb-4" style="color: var(--koyu-renk);">Kullanıcı Yorumları</h3>
                
                <!-- Yorum Yazma Formu -->
                <div class="card border-0 shadow-sm mb-4 p-3 bg-white">
                    <h6 class="mb-3 text-dark">Senin Düşüncen Ne?</h6>
                    <textarea class="form-control mb-2 bg-light border-0" rows="3" placeholder="Bu film hakkında ne düşünüyorsun?"></textarea>
                    <div class="text-end">
                        <button class="btn btn-dark px-4">Yorumu Gönder</button>
                    </div>
                </div>

                <!-- Yorum Listesi (Döngü ile basıyoruz) -->
                <?php foreach($yorumlar as $y): ?>
                <div class="comment-card">
                    <div class="d-flex justify-content-between">
                        <div class="comment-user">
                            <i class="fas fa-user-circle me-2 text-secondary"></i><?php echo $y['user']; ?>
                        </div>
                        <div class="comment-date"><?php echo $y['date']; ?></div>
                    </div>
                    <p class="mb-0 text-muted small mt-2"><?php echo $y['comment']; ?></p>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- Sağ Dar Alan: Benzer Filmler (Sidebar) -->
            <div class="col-md-4">
                <h4 class="h5 mb-3 fw-bold text-dark">Bunları da Sevebilirsin</h4>
                
                <div class="list-group">
                    <!-- Örnek Statik Yan Liste -->
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-2 shadow-sm rounded">
                        <img src="https://image.tmdb.org/t/p/w200/gEU2QniL6C8z1dY4kdNON4k6sKs.jpg" class="rounded me-3" width="50" height="75" style="object-fit:cover;">
                        <div>
                            <h6 class="mb-0 text-dark" style="font-size: 14px;">Interstellar</h6>
                            <small class="text-muted">Bilim Kurgu</small>
                        </div>
                    </a>
                    
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-2 shadow-sm rounded">
                        <img src="https://image.tmdb.org/t/p/w200/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg" class="rounded me-3" width="50" height="75" style="object-fit:cover;">
                        <div>
                            <h6 class="mb-0 text-dark" style="font-size: 14px;">Dune: Part Two</h6>
                            <small class="text-muted">Bilim Kurgu</small>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container text-center">
            <p class="mb-0 text-secondary small py-3">
                &copy; 2025 <strong>FilmFlux</strong>. Tasarım: <span class="text-white">Aydın ŞAHİN</span>
            </p>
        </div>
    </footer>

</body>
</html>