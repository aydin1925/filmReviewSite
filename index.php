<?php

// oturum kontrolü yapıyorum
// session_status kontrolü db.php içinde de var ama burada da olması zarar vermez
session_start();

// veritabanını çağırıyorum
require_once 'config/db.php';

$vizyondakiler = [];
$yakindakiler = [];

try {

    $vizyon_sql = $db->prepare("SELECT * FROM movies WHERE status = 1 ORDER BY movie_id DESC LIMIT 6");
    $vizyon_sql->execute();

    $vizyondakiler= $vizyon_sql->fetchAll(PDO::FETCH_ASSOC);

    $yakin_sql = $db->prepare("SELECT * FROM movies WHERE status = 0 ORDER BY movie_id DESC LIMIT 6");
    $yakin_sql->execute();

    $yakindakiler = $yakin_sql->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    die("Hata: " . $e->getMessage());
}


?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFlux - Sinema Veritabanı</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <nav class="custom-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-play-circle me-2 text-info"></i>FilmFlux
            </a>
            
            <div class="d-none d-md-block w-50">
                <form action="search.php" method="GET" class="position-relative">
                    <input type="text" name="q" id="search_text" class="form-control search-input" placeholder="Film, yönetmen veya oyuncu ara...">
                    <button type="submit" class="btn position-absolute top-0 end-0 text-white"><i class="fas fa-search"></i></button>

                    <div id="show-list" class="list-group position-absolute w-100 shadow" style="z-index: 1000; top: 100%;"></div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="404.php" class="nav-icon" title="Favorilerim"><i class="fas fa-heart text-danger"></i></a>
                    <div class="dropdown">
                        <a href="#" class="nav-icon dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="far fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <?php if($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item text-primary fw-bold" href="admin.php">⚙️ Yönetim Paneli</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="profil.php">Hesabım</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Çıkış Yap</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login_register.php" class="btn btn-sm btn-outline-light px-3 rounded-pill">Giriş Yap</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="sub-menu">
        <div class="container d-flex overflow-auto">
            <a href="404.php"><i class="fas fa-film me-1"></i> Sinema Filmleri</a>
            <a href="404.php"><i class="fas fa-tv me-1"></i> Platform Filmleri</a>
            <a href="404.php"><i class="fas fa-clock me-1"></i> Son Çıkanlar</a>
            <a href="404.php"><i class="fas fa-fire me-1"></i> Haftanın Popülerleri</a>
            <a href="movies.php"><i class="fas fa-layer-group me-1"></i> Tüm Filmler</a>
        </div>
    </div>

    <div class="container pb-5" style="min-height: 600px;">
        
        <h2 class="section-title">
            Vizyondaki Filmler 
            <a href="movies.php" class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size: 12px;">Tümünü Gör</a>
        </h2>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if(empty($vizyondakiler)): ?>
                <div class="col-12"><p class="text-muted">Henüz film eklenmemiş.</p></div>
            <?php else: ?>
                <?php foreach($vizyondakiler as $movie): ?>
                <div class="col">
                    
                    <div class="movie-card position-relative">
                        
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="javascript:void(0);" 
                               class="admin-delete-btn"
                               onclick="return confirmDelete('delete_movie.php?id=<?php echo $movie['movie_id']; ?>');"
                               title="Filmi Sil">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>
                        
                        <a href="detay.php?id=<?php echo $movie['movie_id']; ?>" class="text-decoration-none d-block">
                            <div class="movie-poster">
                                <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </div>
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-info"><?php echo htmlspecialchars($movie['category']); ?></div>
                        </a>
                    </div>

                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

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
                    
                    <div class="movie-card position-relative opacity-75"> 
                        
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="javascript:void(0);" 
                               class="admin-delete-btn"
                               onclick="return confirmDelete('delete_movie.php?id=<?php echo $movie['movie_id']; ?>');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>
                        
                        <a href="detay.php?id=<?php echo $movie['movie_id']; ?>" class="text-decoration-none d-block">
                            <div class="movie-poster">
                                <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small rounded-start" style="font-size: 10px;">YAKINDA</div>
                            </div>
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            
                            <div class="movie-info fw-bold <?php echo (!empty($movie['release_date']) && $movie['release_date'] !== '0000-00-00') ? 'text-primary' : 'text-danger'; ?>" style="font-size: 0.85rem;">
                                <?php if (!empty($movie['release_date']) && $movie['release_date'] !== '0000-00-00'): ?>
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?php echo date("d.m.Y", strtotime($movie['release_date'])); ?>
                                <?php else: ?>
                                    Tarih Bekleniyor
                                <?php endif; ?>
                            </div>

                        </a>
                    </div>

                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <footer>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-5 mb-3">
                    <h5 class="text-white"><i class="fas fa-play-circle me-2 text-primary"></i>FilmFlux</h5>
                    <p class="small text-secondary mb-3">Sinema dünyasının nabzını tutan modern veri tabanı platformu.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Hızlı Erişim</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Anasayfa</a></li>
                        <li><a href="#">Vizyondakiler</a></li>
                    </ul>
                </div>
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
            <p class="mb-0 text-secondary small">&copy; 2025 <strong>FilmFlux</strong>. Tasarım: <span class="text-white">Aydın ŞAHİN</span></p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search_text").keyup(function() {
                let searchText = $(this).val();
                
                if (searchText != "") {
                    $.ajax({
                        url: "ajax_search.php", 
                        method: "POST",
                        data: { query: searchText },
                        success: function(response) {
                            $("#show-list").html(response);
                            $("#show-list").fadeIn();
                        }
                    });
                } else {
                    $("#show-list").html("");
                    $("#show-list").fadeOut();
                }
            });

            $(document).on('click', function() {
                $("#show-list").fadeOut();
            });
            
            $("#show-list").on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <?php
    if (isset($_SESSION['swal_text'])) {
        $s_title = $_SESSION['swal_title'];
        $s_text = addslashes($_SESSION['swal_text']); 
        $s_icon = $_SESSION['swal_icon'];
        
        unset($_SESSION['swal_title']);
        unset($_SESSION['swal_text']);
        unset($_SESSION['swal_icon']);
        
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '$s_title',
                    text: '$s_text',
                    icon: '$s_icon',
                    width: 400,
                    background: '#f1f5f9',
                    color: '#1e3a8a',
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#1e3a8a',
                    customClass: {
                        popup: 'border-top-5',
                        title: 'fw-bold'
                    }
                });
            });
        </script>
        ";
    }
    ?>

</body>
</html>