<?php

// oturum kontrolü yapıyorum
session_start();

// veritabanını çağırıyorum
require_once 'config/db.php';

if(isset($_GET['id'])) {
    
    $movie_id = intval($_GET['id']);

    try {

        $sql = $db->prepare("SELECT * FROM movies WHERE movie_id = :id");
        $sql->execute(['id' => $movie_id]);

        // veritabanında belirlediğim veriyi çekiyorum
        // bu yazdığım kod çektiği veriyi 'film' adında bir diziye atıyor
        $film = $sql->fetch(PDO::FETCH_ASSOC);

        // film geldi mi diye kontrol ediyorum
        if(!$film) {
            // film yoksa anasayfaya geri gönderiyorum
            show_result("Aradığınız film bulunamadı veya kaldırılmış.", "error", "index.php");
        }

        // oyuncu değişkenlerini tutmak için bir dizi oluşturuyorum
        $oyuncu_listesi = [];

        if(!empty($film['cast'])) {
            $oyuncu_listesi = explode(', ', $film['cast']);
        }

        $yorumlar = [];

        $yorumlar_sql = $db->prepare("SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.user_id WHERE reviews.movie_id = :id ORDER BY reviews.created_at DESC");
        $yorumlar_sql->execute(['id' => $movie_id]);

        $yorumlar = $yorumlar_sql->fetchAll(PDO::FETCH_ASSOC);

        // filmin ortalama puanını hesaplama
        $puan_sql = $db->prepare("SELECT AVG(rating) as ortalama FROM reviews WHERE movie_id = :id");
        $puan_sql->execute(['id' => $movie_id]);

        $puan_veri = $puan_sql->fetch(PDO::FETCH_ASSOC);

        // virgülden sonra 1 basamak göstermesi için
        if($puan_veri['ortalama']) {
            $film_puani = number_format($puan_veri['ortalama'], 1);
        }
        else {
            $film_puani = '-';
        }

        // 4. BENZER FİLMLERİ ÇEK (Aynı kategoriden, şu anki film hariç 4 tane)
        $sql_benzer = "SELECT * FROM movies WHERE category = :cat AND movie_id != :id LIMIT 4";
        $stmt_benzer = $db->prepare($sql_benzer);
        $stmt_benzer->execute(['cat' => $film['category'], 'id' => $movie_id]);
        $benzer_filmler = $stmt_benzer->fetchAll(PDO::FETCH_ASSOC);

    }
    catch(PDOException $e) {
        show_result("Sistem Hatası: " . $e->getMessage(), "error");
    }
}
else {
    show_result("Geçersiz film ID'si.", "error", "index.php");
}

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
                <a href="profil.php" class="nav-icon"><i class="far fa-user"></i></a>
            </div>
        </div>
    </nav>

    <!-- 1. BÖLÜM: HERO ALANI (Bulanık Arka Planlı Bölüm) -->
    <!-- Dinamik arka plan resmi PHP'den geliyor -->
    <div class="movie-hero" style="background-image: url('<?php echo $film['image_url']; ?>');">
        <div class="container">
            <div class="row align-items-start">
                
                <!-- Sol: Film Afişi -->
                <div class="col-md-3 text-center text-md-start">
                    <img src="<?php echo $film['image_url']; ?>" class="img-fluid detail-poster" alt="Film Afişi">
                    
                    <!-- Aksiyon Butonları -->
                    <div class="mt-3 d-grid gap-2">
                        <a href="404.php" class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Fragman
                        </a>
    
                        <a href="404.php" class="btn btn-outline-light">
                            <i class="fas fa-plus me-2"></i>Listeme Ekle
                        </a>
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
                                <span class="movie-meta-badge">
                                    <i class="far fa-clock me-1"></i>
                                    <?php 
                                    $saat = floor($film['duration'] / 60); 
                                    $dakika = $film['duration'] % 60;
                                    echo "{$saat}s {$dakika}dk"; 
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Puan Kutusu (CSS ile tasarladığımız) -->
                        <div class="rating-box">
                            <span class="rating-score"><?php echo $film_puani; ?></span>
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
                        <span class="text-white-50">
                        <?php 
                        // Dizi elemanlarını aralarına ' • ' koyerek birleştirip yazdırır
                        // Örn: Brad Pitt • Edward Norton
                        if (!empty($oyuncu_listesi)) {
                            echo implode(' • ', $oyuncu_listesi);
                        }
                        ?>
                        </span>
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
                    
                    <!-- FORM ETİKETİNİ AÇIYORUZ -->
                    <form action="submit_review.php" method="POST">
                        
                        <!-- 1. GİZLİ VERİ: Hangi filme yorum yapıyoruz? -->
                        <!-- Kullanıcı görmez ama arka plana movie_id göndeririz -->
                        <input type="hidden" name="movie_id" value="<?php echo $film['movie_id']; ?>">

                        <!-- 2. PUAN SEÇİMİ (Backend bunu bekliyor) -->
                        <!-- PUAN SLIDER ALANI -->
                        <div class="mb-3">
                            
                            <label for="ratingRange" class="form-label fw-bold text-dark d-flex justify-content-between align-items-center" style="max-width: 400px;">
                                <span>Puanın:</span>
                                <!-- Sabit genişlikli kapsayıcı -->
                                <span class="badge bg-primary fs-6 fixed-rating-badge">
                                    <span id="ratingValue">8.0</span>/10
                                </span>
                            </label>
                            
                            <div class="rating-container" style="width: 1000px;">
                                <!-- SLIDER -->
                                <input type="range" class="form-range custom-range flex-grow-1" 
                                    name="rating" id="ratingRange" 
                                    min="1" max="10" step="0.1" value="8.0" 
                                    oninput="updateRating(this.value)"
                                    style="max-width: 400px;"
                                >
                            </div>

                        </div>

                        <!-- 3. YORUM METNİ -->
                        <!-- name="comment" ekledik -->
                        <textarea name="comment" class="form-control mb-2 bg-light border-0" style="resize: none;" rows="3" placeholder="Bu film hakkında ne düşünüyorsun?" required></textarea>
                        
                        <!-- 4. GÖNDER BUTONU -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-dark px-4">Yorumu Gönder</button>
                        </div>

                    </form>
                </div>

                <!-- Yorum Listesi (Döngü ile basıyoruz) -->
                <?php if(empty($yorumlar)): ?>
                    <p class="text-muted fst-italic text-center py-3">Henüz bu filme yorum yapılmamış. İlk yorumu sen yap!</p>
                <?php else: ?>
                    <?php foreach($yorumlar as $y): ?>
                    
                    <!-- KART: position-relative ekledik ki buton sağ üste yapışabilsin -->
                    <div class="comment-card position-relative">
                        
                        <!-- 🛠️ İŞLEM BUTONLARI (Sadece Yetkili Görür) -->
                        <?php 
                        // Oturum açık mı? VE (Admin mi? VEYA Yorumun sahibi mi?)
                        if (isset($_SESSION['user_id']) && 
                           ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $y['user_id'])): 
                        ?>
                            <div style="position: absolute; top: 15px; right: 15px; display:flex; gap:10px;">
                                
                                <!-- DÜZENLEME BUTONU (YENİ EKLENEN) -->
                                <!-- Link: edit_review.php -->
                                <a href="edit_review.php?id=<?php echo $y['review_id']; ?>" 
                                   class="text-secondary text-decoration-none" 
                                   title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- SİLME BUTONU -->
                                <a href="javascript:void(0);" 
                                   class="text-secondary text-decoration-none" 
                                   onclick="confirmDelete('delete_review.php?id=<?php echo $y['review_id']; ?>&movie_id=<?php echo $film['movie_id']; ?>')"
                                   title="Sil">
                                   <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        <!-- BUTON BİTİŞ -->

                        <div class="d-flex justify-content-between">
                            <div class="comment-user">
                                <i class="fas fa-user-circle me-2 text-secondary"></i><?php echo htmlspecialchars($y['username']); ?>
                            </div>
                            <!-- Tarihi gün/ay/yıl olarak formatla -->
                            <div class="comment-date"><?php echo date("d.m.Y", strtotime($y['created_at'])); ?></div>
                        </div>
                        
                        <div class="mb-2">
                            <?php if($y['rating'] > 0): ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i><?php echo $y['rating']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border"><i class="far fa-comment me-1"></i>Sadece Yorum</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="mb-0 text-muted small"><?php echo htmlspecialchars($y['comment']); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <!-- Sağ Dar Alan: Benzer Filmler (Sidebar) -->
            <div class="col-md-4">
                <h4 class="h5 mb-3 fw-bold text-dark">Bunları da Sevebilirsin</h4>
                
                <div class="list-group">
                    <?php if(empty($benzer_filmler)): ?>
                        <p class="text-muted small">Benzer kategori bulunamadı.</p>
                    <?php else: ?>
                        <?php foreach($benzer_filmler as $benzer): ?>
                        <a href="detay.php?id=<?php echo $benzer['movie_id']; ?>" class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-2 shadow-sm rounded">
                            <img src="<?php echo $benzer['image_url']; ?>" class="rounded me-3" width="50" height="75" style="object-fit:cover;">
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-dark text-truncate" style="max-width: 150px; font-size: 14px;"><?php echo htmlspecialchars($benzer['title']); ?></h6>
                                    <!-- Eğer benzer filmin puanı yoksa gösterme -->
                                </div>
                                <small class="text-muted"><?php echo htmlspecialchars($benzer['category']); ?></small>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

    <script>
    function updateRating(val) {
        // Sadece sayıyı güncelle (Ondalıklı formatta: 7.0 gibi)
        document.getElementById('ratingValue').textContent = parseFloat(val).toFixed(1);
    }
    </script>
    
    <!-- JS DOSYALARI -->
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>