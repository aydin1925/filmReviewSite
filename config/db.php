<?php 
// Oturum başlatılmamışsa başlat (Session kullanacağız)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// veritabanı bağlantı ayarları

// DOCKER AYARI
// docker-compose dosyasında servisin adına 'db' dedik, o yüzden host 'db' olmalı.
$host = "db"; 
$kullanici = "root"; // compose dosyasında belirlediğimiz şifre
$sifre = "root";
$veritabani = "filmflux_db";

$port = 3306;

try {

    // PDO nesnesi oluşturma (Bağlantıyı başlatma)
    // Bu satır, veritabanına "Alo" dediğimiz yer.

    $dsn = "mysql:host=$host;port=$port;dbname=$veritabani;charset=utf8"; // charset=utf8 buraya da eklendi
    $db = new PDO($dsn, $kullanici, $sifre);

    //Hata modunu açmak
    // SQL sorgusunda hata yapılırsa hata bilgisini versin
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e) {
    // Bağlantı kopması ya da yanlış şifre durumunda burası çalışacak
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// ========================================================
// 🛠️ GLOBAL FONKSİYON: Flash Message (Yönlendirmeli Bildirim)
// ========================================================
function show_result($message, $type = 'error', $redirect = 'back') {
    
    // 1. Mesajı ve Tipi Session'a (Geçici Hafızaya) Kaydet
    $_SESSION['swal_title'] = ($type === 'success') ? 'Harika!' : 'Dikkat!';
    $_SESSION['swal_text'] = $message;
    $_SESSION['swal_icon'] = $type; // success veya error
    
    // 2. Yönlendirme Hedefini Belirle
    if ($redirect === 'back' || $redirect === null) {
        // Geldiği yere geri gönder
        $target = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    } else {
        // Belirtilen sayfaya gönder
        $target = $redirect;
    }
    
    // 3. Kullanıcıyı Gönder ve Çık
    header("Location: $target");
    exit;
}

// ========================================================
// ⚡ OTOMATİK POPUP TETİKLEYİCİSİ
// ========================================================

if (isset($_SESSION['swal_text'])) {
    
    $s_title = $_SESSION['swal_title'];
    $s_text = addslashes($_SESSION['swal_text']); 
    $s_icon = $_SESSION['swal_icon'];
    
    // Mesajı hafızadan sil (Flash mantığı)
    unset($_SESSION['swal_title']);
    unset($_SESSION['swal_text']);
    unset($_SESSION['swal_icon']);
    
    // Sayfanın altına JS kodunu ekle
    echo <<<HTML
    <!-- SweetAlert2 Kütüphanesi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$s_title',
                text: '$s_text',
                icon: '$s_icon',
                width: 400, // Daha küçük genişlik
                background: '#f1f5f9', // Hafif gri arka plan (Siteyle uyumlu)
                color: '#1e3a8a', // Koyu lacivert yazı rengi
                confirmButtonText: 'Tamam',
                confirmButtonColor: '#1e3a8a', // Buton rengi
                customClass: {
                    popup: 'border-top-5', // İsteğe bağlı özel sınıf eklenebilir
                    title: 'fw-bold'
                }
            });
        });
    </script>
HTML;
}
?>