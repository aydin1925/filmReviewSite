<?php 

// veritabanı bağlantı ayarları

$host = "localhost";
$kullanici = "root";
$sifre = "";
$veritabani = "filmflux_db";

$port = 3306;

try {

    // PDO nesnesi oluşturma (Bağlantıyı başlatma)
    // Bu satır, veritabanına "Alo" dediğimiz yer.

    $dsn = "mysql:host=$host;port=$port;dbname=$veritabani;charset=utf8";
    $db = new PDO($dsn, $kullanici, $sifre);

    //Hata modunu açmak
    // SQL sorgusunda hata yapılırsa hata bilgisini versin
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e) {
    // Bağlantı kopması ya da yanlış şifre durumunda burası çalışacak
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>