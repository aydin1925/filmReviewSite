<?php

// oturum açılmış mı bunun kontrolünü yapıyorum
session_start();

// veritabanını çağırıyorum

require_once 'config/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die('Yetkisiz erişim teşebbüsü!');
}

if(isset($_GET['id'])) {
    $movie_id = intval($_GET['id']);

    try {
    
        $sql = $db->prepare("DELETE FROM movies WHERE movie_id = :id");
        $sonuc = $sql->execute(['id' => $movie_id]);

        if($sonuc) {
            echo "<script>alert('Film başarıyla silindi!'); window.location.href='index.php'; </script>";
        }
        else {
            die('Silme işlemi başarısız oldu!');
        }

    }
    catch(PDOException $e) {
        die("Kullanıcı silinemedi: " . $e->getMessage());
    }
}
else {
    // EĞER ID YOKSA (Doğrudan erişim)
    // Kullanıcıyı anasayfaya geri fırlat
    header("Location: index.php");
    exit;
}