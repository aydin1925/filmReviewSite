<?php
session_start();
require_once 'config/db.php';

// 1. GÜVENLİK
if (!isset($_SESSION['user_id'])) {
    show_result("Bu sayfayı görüntülemek için giriş yapmalısınız.", "error", "login_register.php");
}

// 2. GÜNCELLEME İŞLEMİ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $review_id = intval($_POST['review_id']);

    $sql_check = $db->prepare("SELECT rating FROM reviews WHERE review_id = :rid");
    $sql_check->execute(['rid' => $review_id]);
    $current_data = $sql_check->fetch(PDO::FETCH_ASSOC);

    // Eğer veritabanındaki puan NULL ise, kullanıcı ne gönderirse göndersin biz NULL yaparız.
    if ($current_data['rating'] === NULL) {
        $rating = NULL; 
    } else {
        // Eğer zaten puanı varsa, yeni gelen puanı kabul et.
        $rating = !empty($_POST['rating']) ? floatval($_POST['rating']) : NULL;
    }

    $movie_id = intval($_POST['movie_id']);
    $rating = floatval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if (empty($comment)) {
        show_result("Yorum alanı boş bırakılamaz.", "error");
    }

    try {
        if ($role !== 'admin') {
            $check = $db->prepare("SELECT user_id FROM reviews WHERE review_id = :rid");
            $check->execute(['rid' => $review_id]);
            $owner = $check->fetch(PDO::FETCH_ASSOC);

            if (!$owner || $owner['user_id'] != $user_id) {
                show_result("Bu yorumu düzenleme yetkiniz yok!", "error", "detay.php?id=$movie_id");
            }
        }

        $sql = "UPDATE reviews SET rating = :rat, comment = :com, created_at = NOW() WHERE review_id = :rid";
        $stmt = $db->prepare($sql);
        $sonuc = $stmt->execute([
            'rat' => ($rating > 0 ? $rating : NULL),
            'com' => $comment,
            'rid' => $review_id
        ]);

        if ($sonuc) {
            show_result("Yorumunuz başarıyla güncellendi!", "success", "detay.php?id=$movie_id");
        } else {
            show_result("Güncelleme başarısız.", "error");
        }

    } catch (PDOException $e) {
        show_result("Hata: " . $e->getMessage(), "error");
    }
}

// 3. FORM GÖSTERME (GET)
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$review_id = intval($_GET['id']);

// Filmin resmini ve başlığını da çekelim ki arka plan güzel olsun (JOIN)
$sql_data = "SELECT r.*, m.title, m.image_url 
             FROM reviews r 
             JOIN movies m ON r.movie_id = m.movie_id 
             WHERE r.review_id = :rid";
$stmt = $db->prepare($sql_data);
$stmt->execute(['rid' => $review_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    show_result("Yorum bulunamadı.", "error", "index.php");
}

if ($_SESSION['role'] !== 'admin' && $review['user_id'] != $_SESSION['user_id']) {
    show_result("Bu yorumu düzenleme yetkiniz yok!", "error", "index.php");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yorumu Düzenle - <?php echo htmlspecialchars($review['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php"><i class="fas fa-play-circle me-2 text-info"></i>FilmFlux</a>
            <a href="detay.php?id=<?php echo $review['movie_id']; ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="fas fa-times me-1"></i> İptal
            </a>
        </div>
    </nav>

    <!-- ARKAPLAN VE FORM ALANI -->
    <!-- Detay sayfasındaki gibi bulanık arka plan kullanıyoruz -->
    <div class="movie-hero" style="background-image: url('<?php echo $review['image_url']; ?>'); min-height: 100vh; margin-bottom: 0; padding-top: 50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-white text-shadow">Yorumu Düzenle</h2>
                        <p class="text-white-50">Film: <?php echo htmlspecialchars($review['title']); ?></p>
                    </div>

                    <div class="card shadow-lg border-0" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                        <div class="card-body p-4">
                            
                            <form action="edit_review.php" method="POST">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <input type="hidden" name="movie_id" value="<?php echo $review['movie_id']; ?>">

                                <!-- Puan Alanı -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">Puanınız</label>
                                    <div class="d-flex align-items-center gap-2">
                                    
                                        
                                        <input type="number" step="0.1" name="rating" class="form-control w-50 fw-bold text-center" 
                                               value="<?php echo $review['rating']; ?>" min="1" max="10" 
                                               placeholder="<?php echo ($review['rating'] === NULL) ? 'Bu yorum puansızdır' : '-'; ?>"
                                               <?php echo ($review['rating'] === NULL) ? 'disabled' : ''; ?>>
                                    
                                        <?php if($review['rating'] === NULL): ?>
                                            <small class="text-muted fst-italic ms-2 text-danger">
                                                <i class="fas fa-lock me-1"></i>Bu yorum puansızdır, değiştirilemez.
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted small">/ 10</span>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <!-- Yorum Alanı -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">Yorumunuz</label>
                                    <textarea name="comment" class="form-control border-secondary bg-white" rows="6" required style="resize: none;"><?php echo htmlspecialchars($review['comment']); ?></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i>Değişiklikleri Kaydet
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer style="margin-top: 0;">
        <div class="container text-center">
            <p class="mb-0 text-secondary small py-3">
                &copy; 2025 <strong>FilmFlux</strong>. Tasarım: <span class="text-white">Aydın ŞAHİN</span>
            </p>
        </div>
    </footer>

</body>
</html>