<?php

$movies = [
    [
        "title" => "Matrix",
        "director" => "Wachowski Kardeşler",
        "category" => "Bilim Kurgu",
        "image" => "https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg"
    ],
    [
        "title" => "Esaretin Bedeli",
        "director" => "Frank Darabont",
        "category" => "Dram",
        "image" => "https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg"
    ],
    [
        "title" => "Kara Şövalye",
        "director" => "Christopher Nolan",
        "category" => "Aksiyon",
        "image" => "https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg"
    ]
];

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFlux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-img-top {
            height: 350px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02s);
        }
    </style>
</head>
<body class="bg-light">
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <div class="container">
            <a href="#" class="navbar-brand fw-bold">🎬 FilmFlux</a>
        </div>
    </nav>

    <div class="container">
        <!-- Başlık -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-6 fw-bold">Vizyondaki Filmler</h1>
                <p class="text-muted">Manuel Veri Testi Aşaması</p>
            </div>
        </div>
    
        <!-- Film Listesi -->
        <div class="row">

            <?php
            // Veritabanındaki her film için aşağıda yazacağım HTML kodu çalışacak
            foreach($movies as $movie):
            ?>

                <!-- 
                   col-md-4: Orta ve büyük ekranlarda 3'lü yan yana (12/4 = 3)
                   col-sm-6: Tablette 2'li yan yana (12/6 = 2)
                   col-12: Telefonda tekli (Tam genişlik)
                -->

                <!-- Bir tane şablon film  kutusu hazırlıyorum -->
                 <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- PHP'den image linkini alıp buraya ekliyorum -->
                         <img src="<?php echo $movie['image']; ?>" class="card-img-top" alt="<?php echo $movie['title']; ?>">

                         <div class="card-body d-flex flex-column">
                            <!-- Filmin ismini ekrana getir -->
                             <h5 class="card-title fw-bold"><?php echo $movie['title']; ?></h5>

                            <div class="mb-3">
                                <span class="badge bg-primary"><?php echo $movie['category']; ?></span>
                            </div>

                             <!-- Yönetmen ve kategoriyi ekrana getir -->
                              <p class="card-text text-muted small">
                                <strong>Yönetmen:</strong> <?php echo $movie['director']; ?> <br>
                              </p>

                              <!-- mt-auto: Butonu her zaman en alta iter -->
                              <button class="btn btn-dark w-100 mt-auto">Detayları Gör</button>
                         </div>
                    </div>
                 </div>
            
            <?php
            // Döngüyü bitir.
            endforeach;
            ?>

        </div>

    </div>
    
    <!-- Bootstrap JS (Opsiyonel: Açılır menüler için gerekli) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>