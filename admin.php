<?php

session_start();

require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    show_result("Bu sayfaya erişim yetkiniz yok! Ana sayfaya yönlendiriliyorsunuz...", "error", "index.php");
}

// 3. FORM İŞLEME (PDO İLE)
if (isset($_POST['add_movie'])) {

    $title = trim($_POST['title']);
    $director = trim($_POST['director']);
    $description = trim($_POST['description']);
    $cast = trim($_POST['cast']);
    $image_url = trim($_POST['image_url']);
    $release_date = !empty($_POST['release_date']) ? $_POST['release_date'] : null;
    $release_year = intval($_POST['release_year']);
    $duration     = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $status       = intval($_POST['status']);

    if (isset($_POST['category']) && is_array($_POST['category'])) {
        $category = implode(', ', $_POST['category']);
    } else {
        $category = "Genel";
    }

    try {
        $sql = $db->prepare("INSERT INTO movies (
                    title, description, director, release_year, cast, 
                    image_url, category, status, release_date, duration
                ) VALUES (
                    :title, :description, :director, :release_year, :cast, 
                    :image_url, :category, :status, :release_date, :duration
                )");

        // D. Verileri Eşleştir ve Çalıştır
        $result = $sql->execute([
            ':title' => $title,
            ':description' => $description,
            ':director' => $director,
            ':release_year' => $release_year,
            ':cast' => $cast,
            ':image_url' => $image_url,
            ':category' => $category,
            ':status' => $status,
            ':release_date' => $release_date,
            ':duration' => $duration
        ]);

        if ($result) {
            show_result("Film başarıyla eklendi!", "success", "admin.php");
        }

    } catch (PDOException $e) {
        show_result("Veritabanı Hatası: " . $e->getMessage(), "error", "admin.php");
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Ekle - Yönetim Paneli</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <nav class="custom-navbar mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-user-shield me-2"></i>FilmFlux <span class="fs-6 opacity-75">| Yönetim Paneli</span>
            </a>
            <a href="index.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-arrow-left me-1"></i> Siteye Dön
            </a>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row">
            
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-plus-circle me-2"></i>Yeni Film Ekle</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="admin.php" method="POST">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">FİLM BAŞLIĞI</label>
                                    <input type="text" name="title" id="titleInput" class="form-control" placeholder="Örn: Inception" required oninput="updatePreviewText()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-bold">YAPIM YILI</label>
                                    <input type="number" min="1900" name="release_year" id="yearInput" class="form-control" placeholder="2024" required oninput="updatePreviewText()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-bold">SÜRE (DK)</label>
                                    <input type="number" min="1" name="duration" class="form-control" placeholder="120">
                                </div>
                            </div>

                            <div class="row mb-3 p-3 bg-light rounded mx-0 border">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">VİZYON DURUMU</label>
                                    <div class="d-flex gap-3 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status1" value="1" checked onclick="toggleDateInput(false)">
                                            <label class="form-check-label" for="status1"><i class="fas fa-play-circle text-success me-1"></i>Vizyonda</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status0" value="0" onclick="toggleDateInput(true)">
                                            <label class="form-check-label" for="status0"><i class="fas fa-hourglass-half text-warning me-1"></i>Yakında</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">VİZYON TARİHİ</label>
                                    <input type="date" name="release_date" id="releaseDateInput" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">KATEGORİLER (Birden fazla seçilebilir)</label>
                                <div class="card p-3 border-light bg-light">
                                    <div class="row row-cols-2 row-cols-md-3 g-2">
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Bilim Kurgu" id="cat1" onchange="updatePreviewText()"><label class="form-check-label" for="cat1">Bilim Kurgu</label></div></div>
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Aksiyon" id="cat2" onchange="updatePreviewText()"><label class="form-check-label" for="cat2">Aksiyon</label></div></div>
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Dram" id="cat3" onchange="updatePreviewText()"><label class="form-check-label" for="cat3">Dram</label></div></div>
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Komedi" id="cat4" onchange="updatePreviewText()"><label class="form-check-label" for="cat4">Komedi</label></div></div>
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Korku" id="cat5" onchange="updatePreviewText()"><label class="form-check-label" for="cat5">Korku</label></div></div>
                                        <div class="col"><div class="form-check"><input class="form-check-input category-check" type="checkbox" name="category[]" value="Macera" id="cat6" onchange="updatePreviewText()"><label class="form-check-label" for="cat6">Macera</label></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">YÖNETMEN</label>
                                <input type="text" name="director" class="form-control" placeholder="Örn: Christopher Nolan">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">AFİŞ RESMİ (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-link text-secondary"></i></span>
                                    <input type="url" name="image_url" id="imgInput" class="form-control" placeholder="https://..." oninput="previewImage()">
                                </div>
                                <div class="form-text">TMDB veya IMDb'den görsel bağlantısı yapıştırın.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">FİLM KONUSU</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Filmin özeti..." style="resize: none;"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">OYUNCULAR</label>
                                <input type="text" name="cast" class="form-control" placeholder="Leonardo DiCaprio, Joseph Gordon-Levitt...">
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-light text-muted">Temizle</button>
                                <button type="submit" name="add_movie" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Filmi Kaydet</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <h6 class="text-muted text-uppercase small fw-bold mb-3">Önizleme</h6>
                
                <div class="card border-0 shadow-sm" style="width: 100%; max-width: 250px; margin: 0 auto;">
                    <div class="movie-poster bg-dark d-flex align-items-center justify-content-center text-white" style="height: 350px; overflow: hidden;">
                        <img id="imgPreview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <i id="imgIcon" class="fas fa-image fa-3x opacity-25"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-dark mb-1" id="previewTitle">Film Başlığı</h6>
                        <small class="text-muted" id="previewMeta">Kategori • Yıl</small>
                    </div>
                </div>

                <div class="alert alert-info mt-4 small">
                    <i class="fas fa-info-circle me-2"></i>
                    Bu panelden eklediğiniz filmler anında veritabanına işlenir.
                </div>
            </div>

        </div>
    </div>

    <footer class="mt-auto py-3">
        <div class="container text-center">
            <span class="small" style="color: #94a3b8;">&copy; 2025 FilmFlux Admin Paneli</span>
        </div>
    </footer>

    <script>
        // Resim Önizleme fonksiyonu
        function previewImage() {
            const input = document.getElementById('imgInput');
            const preview = document.getElementById('imgPreview');
            const icon = document.getElementById('imgIcon');
            const url = input.value;

            if (url) {
                preview.src = url;
                preview.style.display = 'block';
                icon.style.display = 'none';
            } else {
                preview.style.display = 'none';
                icon.style.display = 'block';
            }
        }


        function toggleDateInput(show) {
            const dateInput = document.getElementById('releaseDateInput');
            if (show) {
                dateInput.removeAttribute('disabled');
                dateInput.focus();
            } else {
                dateInput.setAttribute('disabled', 'disabled');
                dateInput.value = ''; 
            }
        }

        function updatePreviewText() {
            // 1. Verileri Al
            let title = document.getElementById('titleInput').value;
            let year = document.getElementById('yearInput').value;
            
            // Seçili kategorileri bul
            let checkboxes = document.querySelectorAll('.category-check:checked');
            let categories = [];
            checkboxes.forEach((checkbox) => {
                categories.push(checkbox.value);
            });

            // 2. Varsayılan Değerler (Eğer boşsa)
            if (!title) title = "Film Başlığı";
            if (!year) year = "Yıl";
            
            // Kategorileri birleştir
            let catString = categories.length > 0 ? categories.join(', ') : "Kategori";

            // 3. Ekrana Bas
            document.getElementById('previewTitle').innerText = title;
            document.getElementById('previewMeta').innerText = catString + " • " + year;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>