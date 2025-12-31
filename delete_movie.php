<?php

session_start();

require_once 'config/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    show_result("Yetkisiz erişim!", "error", "index.php");
    exit;
}


if (isset($_GET['id'])) {
    
    $movie_id = intval($_GET['id']);

    try {
        // PDO ile silme sorgusu
        $sql = $db->prepare("DELETE FROM movies WHERE movie_id = :id");
        $delete = $sql->execute(['id' => $movie_id]);

        if ($delete) {
            
            show_result("Film başarıyla silindi.", "success", "index.php");
        } else {
            show_result("Silme işlemi sırasında bir sorun oluştu.", "error", "index.php");
        }

    } catch (PDOException $e) {
        show_result("Hata: " . $e->getMessage(), "error", "index.php");
    }

} else {
    show_result("Geçersiz film ID'si.", "error", "index.php");
}
?>