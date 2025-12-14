<?php
// Oturumu başlat
session_start();

// Veritabanı bağlantısı
require_once 'config/db.php';

// 1. GÜVENLİK KONTROLÜ
// Kullanıcı giriş yapmamışsa veya Admin değilse ana sayfaya at
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    show_result("Yetkisiz erişim!", "error", "index.php");
    exit;
}

// 2. ID KONTROLÜ VE SİLME İŞLEMİ
if (isset($_GET['id'])) {
    
    $movie_id = intval($_GET['id']);

    try {
        // PDO ile silme sorgusu
        $sql = $db->prepare("DELETE FROM movies WHERE movie_id = :id");
        $delete = $sql->execute(['id' => $movie_id]);

        if ($delete) {
            // BAŞARILI: Mesajı kur ve index.php'ye geri gönder
            // show_result fonksiyonu db.php içinde tanımlıydı,
            // Session'a veriyi yazar ve header() ile yönlendirir.
            show_result("Film başarıyla silindi.", "success", "index.php");
        } else {
            // Veritabanı sildi diyemedi (0 satır etkilendi vs.)
            show_result("Silme işlemi sırasında bir sorun oluştu.", "error", "index.php");
        }

    } catch (PDOException $e) {
        // SQL Hatası
        show_result("Hata: " . $e->getMessage(), "error", "index.php");
    }

} else {
    // ID gönderilmemişse
    show_result("Geçersiz film ID'si.", "error", "index.php");
}
?>