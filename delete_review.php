<?php
session_start();
require_once 'config/db.php';

// 1. GÜVENLİK DUVARI: Giriş yapmamışsa durdur
if (!isset($_SESSION['user_id'])) {
    show_result("Bu işlemi yapmak için giriş yapmalısınız!", "error", "login_register.php");
}

// 2. VERİLERİ AL (GET Metoduyla Linkten Gelir)
if (isset($_GET['id']) && isset($_GET['movie_id'])) {
    
    $review_id = intval($_GET['id']);
    $movie_id = intval($_GET['movie_id']);
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['role'];

    try {
        
        $sql = $db->prepare("SELECT user_id FROM reviews WHERE review_id = :id");
        $sql->execute(['id' => $review_id]);
        $review = $sql->fetch(PDO::FETCH_ASSOC);

        // Yorum yoksa
        if (!$review) {
            show_result("Silinecek yorum bulunamadı.", "error", "detay.php?id=$movie_id");
        }

        $owner_id = $review['user_id'];

        
        if ($user_role == 'admin' || $user_id == $owner_id) {
            
            
            $delete_sql = $db->prepare("DELETE FROM reviews WHERE review_id = :id");
            $sonuc = $delete_sql->execute(['id' => $review_id]);

            if ($sonuc) {
                show_result("Yorum başarıyla silindi.", "success", "detay.php?id=$movie_id");
            } else {
                show_result("Silme işlemi sırasında bir hata oluştu.", "error", "detay.php?id=$movie_id");
            }

        } else {
            // Yetkisiz silme girişimi
            show_result("Bu yorumu silme yetkiniz yok!", "error", "detay.php?id=$movie_id");
        }

    } catch (PDOException $e) {
        show_result("Veritabanı Hatası: " . $e->getMessage(), "error");
    }

} else {
    header("Location: index.php");
    exit;
}
?>