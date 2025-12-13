<?php
session_start();
require_once 'config/db.php';

$sonuclar = [];
$arama_terimi = "";

// Arama yapıldı mı kontrol et
if (isset($_GET['q'])) {
    $arama_terimi = trim($_GET['q']);
    
    if (!empty($arama_terimi)) {
        try {
            // Arama Sorgusu (Başlık veya Yönetmen)
            $sql = "SELECT * FROM movies WHERE title LIKE :key OR director LIKE :key ORDER BY status DESC, release_year DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute(['key' => "%$arama_terimi%"]);
            $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Arama hatası: " . $e->getMessage());
        }
    }
} else {
    // Eğer q yoksa anasayfaya at
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları: <?php echo htmlspecialchars($arama_terimi); ?> - FilmFlux</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR (Aynen Kopyala) -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php"><i class="fas fa-play-circle me-2 text-info"></i>FilmFlux</a>
            
            <div class="d-none d-md-block w-50">
                <form action="search.php" method="GET" class="position-relative">
                    <!-- Arama kutusunda aranan kelime kalsın (value) -->
                    <input type="text" name="q" class="form-control search-input" placeholder="Yeni arama..." value="<?php echo htmlspecialchars($arama_terimi); ?>">
                    <button type="submit" class="btn position-absolute top-0 end-0 text-white"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="index.php" class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Anasayfa</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="text-white small"><i class="far fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <?php else: ?>
                    <a href="login_register.php" class="btn btn-sm btn-light px-3 rounded-pill text-primary fw-bold">Giriş Yap</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- SONUÇ ALANI -->
    <div class="container pb-5" style="min-height: 600px;">
        
        <div class="mt-4 mb-4 pb-2 border-bottom d-flex justify-content-between align-items-center">
            <h2 class="h4 text-dark mb-0">
                Arama Sonuçları: <span class="text-primary fw-bold">"<?php echo htmlspecialchars($arama_terimi); ?>"</span>
            </h2>
            <span class="badge bg-secondary rounded-pill"><?php echo count($sonuclar); ?> Sonuç</span>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if(empty($sonuclar)): ?>
                <div class="col-12 text-center py-5">
                    <div class="text-muted mb-3"><i class="fas fa-search fa-3x opacity-25"></i></div>
                    <h5 class="text-muted">Aradığınız kriterlere uygun film bulunamadı.</h5>
                    <p class="small text-secondary">Farklı anahtar kelimelerle tekrar deneyebilirsiniz.</p>
                </div>
            <?php else: ?>
                <?php foreach($sonuclar as $movie): ?>
                <div class="col">
                    <div class="movie-card position-relative">
                        
                        <!-- ADMIN BUTONU (Varsa) -->
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="delete_movie.php?id=<?php echo $movie['movie_id']; ?>" 
                               class="admin-delete-btn"
                               onclick="return confirm('Silmek istediğine emin misin?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>

                        <a href="detay.php?id=<?php echo $movie['movie_id']; ?>" class="text-decoration-none d-block">
                            <div class="movie-poster">
                                <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                <!-- DURUM ETİKETİ (Senin istediğin özellik) -->
                                <?php if($movie['status'] == 0): ?>
                                    <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small rounded-start" style="font-size: 10px;">YAKINDA</div>
                                <?php else: ?>
                                    <div class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 small rounded-start" style="font-size: 10px;">VİZYONDA</div>
                                <?php endif; ?>
                            </div>
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-info"><?php echo htmlspecialchars($movie['category']); ?></div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="mt-auto py-4 bg-dark text-center">
        <span class="text-white-50 small">&copy; 2025 FilmFlux. Tasarım: Aydın ŞAHİN</span>
    </footer>

</body>
</html>