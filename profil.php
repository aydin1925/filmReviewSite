<?php
session_start();
require_once 'config/db.php';

// --- HATA ÖNLEYİCİ: Varsayılan Değerler ---
// Veritabanı bağlantısı koparsa veya veri gelmezse sayfa patlamasın diye
// değişkenlerin içini boşaltıyoruz.
$user = [];
$stats = [
    'yorum_sayisi' => 0,
    'favori_sayisi' => 0
];
$favorites = [];
$my_reviews = [];

// Güvenlik: Giriş yapmamışsa durdur
if (!isset($_SESSION['user_id'])) {
    show_result("Profilinizi görüntülemek için önce giriş yapmalısınız.", "error", "login_register.php");
}

$user_id = intval($_SESSION['user_id']);

try {
    // 1. KULLANICI BİLGİLERİNİ ÇEK
    $sql = $db->prepare("SELECT * FROM users WHERE user_id = :id");
    $sql->execute(['id' => $user_id]);
    $user_data = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        $user = $user_data; // Veri geldiyse ana değişkene aktar

        // 2. İSTATİSTİKLERİ ÇEK
        // COUNT(*) -> Toplam Yorum
        // SUM(...) -> 8.5 ve üzeri puanlar (Favori)
        $sql_stats = "SELECT 
                        COUNT(*) as yorum_sayisi, 
                        SUM(CASE WHEN rating >= 8.5 THEN 1 ELSE 0 END) as favori_sayisi 
                      FROM reviews WHERE user_id = :id";
        $stmt_stats = $db->prepare($sql_stats);
        $stmt_stats->execute(['id' => $user_id]);
        $fetched_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
        
        if ($fetched_stats) {
            $stats['yorum_sayisi'] = $fetched_stats['yorum_sayisi'] ?? 0;
            $stats['favori_sayisi'] = $fetched_stats['favori_sayisi'] ?? 0;
        }

        // 3. FAVORİLERİ ÇEK (8.5 ve Üzeri)
        $sql_fav = "SELECT m.movie_id, m.title, m.image_url, m.release_year, r.rating 
                    FROM reviews r 
                    JOIN movies m ON r.movie_id = m.movie_id 
                    WHERE r.user_id = :id AND r.rating >= 8.5 
                    ORDER BY r.rating DESC";
        $stmt_fav = $db->prepare($sql_fav);
        $stmt_fav->execute(['id' => $user_id]);
        $favorites = $stmt_fav->fetchAll(PDO::FETCH_ASSOC);

        // 4. YORUM GEÇMİŞİNİ ÇEK
        $sql_reviews = "SELECT r.*, m.title 
                        FROM reviews r 
                        JOIN movies m ON r.movie_id = m.movie_id 
                        WHERE r.user_id = :id 
                        ORDER BY r.created_at DESC";
        $stmt_rev = $db->prepare($sql_reviews);
        $stmt_rev->execute(['id' => $user_id]);
        $my_reviews = $stmt_rev->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    // Hata olursa sayfayı bozma, sadece mesaj göster (isteğe bağlı)
    // echo "<div class='alert alert-danger'>Veri Hatası: " . $e->getMessage() . "</div>";
}

// Avatar Oluşturma (İsimden)
$username_safe = $user['username'] ?? 'User';
$avatar_name = urlencode($username_safe);
$avatar_url = "https://ui-avatars.com/api/?name=$avatar_name&background=1e3a8a&color=fff&size=150&font-size=0.4";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - FilmFlux</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <!-- GÜNCELLEME: İstenilen orijinal yazı geri geldi -->
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
                <img src="<?php echo $avatar_url; ?>" alt="Profil">
            </div>
        </div>

        <!-- PROFİL MENÜSÜ & İSİM -->
        <div class="profile-nav d-flex justify-content-between align-items-center mb-4 px-4 bg-white rounded-bottom shadow-sm">
            <div>
                <!-- Güvenli Veri Gösterimi (?? '...') -->
                <h2 class="mb-0 fw-bold text-dark fs-3"><?php echo htmlspecialchars($user['username'] ?? 'Misafir'); ?></h2>
                <p class="text-muted small mb-2"><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($user['email'] ?? '-'); ?></p>
            </div>
            
            <div class="d-none d-md-block">
                <!-- GÜNCELLEME: Ayarlar butonu geri eklendi -->
                <a href="404.php" class="btn btn-outline-primary btn-sm rounded-pill me-2"><i class="fas fa-cog me-1"></i> Ayarlar</a>
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
                            <div class="stat-number"><?php echo $stats['yorum_sayisi']; ?></div>
                            <div class="stat-label">Yorum</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['favori_sayisi']; ?></div>
                            <div class="stat-label">Favori</div>
                        </div>
                    </div>
                </div>

                <!-- Yan Menü Listesi -->
                <div class="list-group custom-list-group">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-circle me-2"></i> Profil Bilgileri</span>
                        <i class="fas fa-chevron-right small"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-heart me-2 text-danger"></i> Favorilerim</span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $user['stats']['favorites'] ?? $stats['favori_sayisi']; ?></span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-comment-alt me-2 text-primary"></i> Yorumlarım</span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $user['stats']['comments'] ?? $stats['yorum_sayisi']; ?></span>
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
                    <i class="fas fa-heart text-danger me-2"></i>Favorilerim (8.5+ Puan)
                </h5>
                
                <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
                    <?php if (empty($favorites)): ?>
                        <div class="col-12"><p class="text-muted small fst-italic">Henüz favori listenizde film yok.</p></div>
                    <?php else: ?>
                        <?php foreach($favorites as $fav): ?>
                        <div class="col">
                            <a href="detay.php?id=<?php echo $fav['movie_id']; ?>" class="text-decoration-none">
                                <div class="movie-card">
                                    <div class="movie-poster position-relative">
                                        <img src="<?php echo $fav['image_url']; ?>" alt="<?php echo htmlspecialchars($fav['title']); ?>">
                                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-2 py-1 small rounded-start fw-bold">
                                            <?php echo number_format($fav['rating'], 1); ?>
                                        </div>
                                    </div>
                                    <div class="movie-title small text-truncate"><?php echo htmlspecialchars($fav['title']); ?></div>
                                    <div class="movie-info small"><?php echo $fav['release_year']; ?></div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Bölüm 2: Son Yorumlar -->
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fas fa-comment text-primary me-2"></i>Son Yorumların
                </h5>
                
                <div class="card border-0 shadow-sm">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($my_reviews)): ?>
                            <li class="list-group-item p-3 text-muted fst-italic">Henüz hiç yorum yapmadınız.</li>
                        <?php else: ?>
                            <?php foreach($my_reviews as $review): ?>
                            <li class="list-group-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <a href="detay.php?id=<?php echo $review['movie_id']; ?>" class="text-decoration-none">
                                            <i class="fas fa-film me-1 text-muted small"></i>
                                            <?php echo htmlspecialchars($review['title']); ?>
                                        </a>
                                    </h6>
                                    
                                    <?php if($review['rating']): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i><?php echo number_format($review['rating'], 1); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border">Puansız</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted small mb-1 fst-italic">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                                <small class="text-secondary" style="font-size: 11px;">
                                    <i class="far fa-clock me-1"></i> <?php echo date("d.m.Y", strtotime($review['created_at'])); ?>
                                </small>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>