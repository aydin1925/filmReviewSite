<?php

session_start();

// veritabanını çağırıyorum
require_once 'config/db.php';

if(!isset($_SESSION['user_id'])) {
    show_result("Yorum yapmak için önce giriş yapmalısınız!", "error", "login_register.php");
}
else {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $movie_id = intval($_POST['movie_id']);
        $rating = floatval($_POST['rating']);
        $comment = trim($_POST['comment']);
        $user_id = $_SESSION['user_id']; // Giriş yapan kişinin ID'si

        // Boş Alan Kontrolü
        if ($movie_id <= 0 || empty($comment)) {
            show_result("Lütfen bir yorum yazın!", "error");
        }

        try {
            // --- KONTROL: DAHA ÖNCE PUAN VERMİŞ Mİ? ---
            // Sadece PUANI olan (NULL olmayan) kayıtları sayıyoruz.
            $kontrol_sql = "SELECT review_id FROM reviews WHERE user_id = :uid AND movie_id = :mid AND rating IS NOT NULL";
            $stmt_kontrol = $db->prepare($kontrol_sql);
            $stmt_kontrol->execute(['uid' => $user_id, 'mid' => $movie_id]);
            
            $daha_once_puan_vermis = ($stmt_kontrol->rowCount() > 0);

            // --- KARAR ANI ---
            if ($daha_once_puan_vermis) {
                // Evet, daha önce puan vermiş.
                // O zaman bu yeni yorumun puanını NULL (Boş) yapıyoruz.
                $final_rating = NULL; 
                $mesaj = "Yorumunuz eklendi! (Daha önce puan verdiğiniz için bu yorum puansız kaydedildi.)";
            } else {
                // Hayır, ilk defa puan veriyor.
                if ($rating <= 0) {
                    show_result("Lütfen bir puan seçin!", "error");
                }
                $final_rating = $rating;
                $mesaj = "Puanınız ve yorumunuz kaydedildi!";
            }

            // SQL Hazırlığı (INSERT)
            // reviews tablosuna yeni bir satır ekliyoruz.
            $sql = "INSERT INTO reviews (user_id, movie_id, rating, comment) VALUES (:uid, :mid, :rat, :com)";
            $stmt = $db->prepare($sql);

            // Çalıştır
            $sonuc = $stmt->execute([
                'uid' => $user_id,
                'mid' => $movie_id,
                'rat' => $final_rating, // Hesapladığımız puanı (Sayı veya NULL) gönderiyoruz
                'com' => $comment
            ]);

            if ($sonuc) {
                show_result($mesaj, "success", "detay.php?id=$movie_id");
            } else {
                show_result("Yorum kaydedilemedi.", "error");
            }

        } catch (PDOException $e) {
            show_result("Veritabanı Hatası: " . $e->getMessage(), "error");
        }

    } else {
        // Eğer formdan gelmediyse (URL'den girdiyse) anasayfaya at
        header("Location: index.php");
        exit;
    }
}
?>