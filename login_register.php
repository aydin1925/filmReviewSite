<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap / Kayıt Ol - FilmFlux</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- İkonlar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- 1. ADIM: Kendi CSS Dosyamızı Çağırıyoruz -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <!-- Arka Plan Süsü -->
    <div class="bg-shape"></div>

    <div class="scene">
        
        <!-- Üstteki Kayan Geçiş Butonları -->
        <div class="switch-wrap">
            <button class="switch-btn active" id="loginBtn">Giriş Yap</button>
            <button class="switch-btn" id="registerBtn">Kayıt Ol</button>
        </div>

        <!-- Dönen Kart Kapsayıcısı -->
        <div class="card-3d-wrapper" id="cardWrapper">
            
            <!-- ÖN YÜZ: Giriş Yap Formu -->
            <div class="card-face card-front">
                <div class="brand-logo"><i class="fas fa-play-circle me-2"></i>FilmFlux</div>
                <h4 class="mb-4 text-center">Hoş Geldiniz</h4>
                
                <form action="auth_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-muted">E-Posta Adresi</label>
                        <input type="email" name="email" class="form-control" placeholder="ornek@mail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Şifre</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">Beni Hatırla</label>
                        </div>
                        <a href="#" class="small text-decoration-none">Şifremi Unuttum</a>
                    </div>

                    <button type="submit" name="login" id="login_submit_button" class="btn btn-primary">Giriş Yap</button>
                </form>
                
                <!-- Alttaki link de döndürme fonksiyonunu çağırır -->
                <p class="text-center mt-4 small text-muted">
                    Hesabın yok mu? <a href="#" class="text-primary fw-bold" onclick="flipCard()">Hemen Kayıt Ol</a>
                </p>
            </div>

            <!-- ARKA YÜZ: Kayıt Ol Formu -->
            <div class="card-face card-back">
                <div class="brand-logo"><i class="fas fa-play-circle me-2"></i>FilmFlux</div>
                <h4 class="mb-4 text-center">Hesap Oluştur</h4>
                
                <form action="auth_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Kullanıcı Adı</label>
                        <input type="text" name="username" class="form-control" placeholder="Kullanıcı adınız" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">E-Posta Adresi</label>
                        <input type="email" name="email" class="form-control" placeholder="ornek@mail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Şifre</label>
                        <input type="password" name="password" id="register_password_input" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-text small text-muted ms-1" style="font-size: 11px;">
                            * En az 6 karakter uzunluğunda olmalıdır.
                    </div>

                    <button type="submit" name="register" id="register_submit_button" class="btn btn-primary">Kayıt Ol</button>
                </form>

                <p class="text-center mt-4 small text-muted">
                    Zaten üye misin? <a href="#" class="text-primary fw-bold" onclick="flipCard()">Giriş Yap</a>
                </p>
            </div>

        </div>
    </div>

    <!-- 2. ADIM: Kendi JS Dosyamızı Çağırıyoruz -->
    <script src="assets/js/login-script.js"></script>

</body>
</html>