<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahne Bulunamadı - FilmFlux</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Kendi Stil Dosyamız -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR (Standart) -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-play-circle me-2 text-info"></i>FilmFlux
            </a>
            <a href="index.php" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="fas fa-home me-1"></i> Ana Sayfa
            </a>
        </div>
    </nav>

    <!-- HATA İÇERİĞİ -->
    <div class="container error-section">
        
        <!-- Arka Plan Dekoru -->
        <div class="film-strip-bg"></div>

        <div class="position-relative d-inline-block">
            <h1 class="error-code">404</h1>
            <!-- Süsleme İkonu -->
            <i class="fas fa-video-slash error-icon-overlay"></i>
        </div>

        <h2 class="error-title">Kestik! Bu Sahne Kayıp.</h2>
        <p class="error-desc">
            Aradığınız sayfa senaryodan çıkarılmış, silinmiş ya da hiç çekilmemiş olabilir.
        </p>

        <div class="d-flex gap-3">
            <a href="index.php" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="fas fa-undo me-2"></i>Geri Dön
            </a>
            <a href="#" class="btn btn-outline-dark px-4 py-2 rounded-pill shadow-sm">
                <i class="fas fa-envelope me-2"></i>Yönetmene Bildir
            </a>
        </div>

    </div>

    <!-- FOOTER (Sadeleştirilmiş) -->
    <footer>
        <div class="container text-center">
            <p class="mb-0 text-secondary small py-3">
                &copy; 2025 <strong>FilmFlux</strong>. Tasarım: <span class="text-white">Aydın ŞAHİN</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>