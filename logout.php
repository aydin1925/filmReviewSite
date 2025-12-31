<?php

session_start();

require_once 'config/db.php';

// Kullanıcı Verilerini Temizle

unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['role']);


// Eski oturum dosyası silinir, yeni ve temiz bir ID ile devam edilir.
session_regenerate_id(true);

// 5. Mesajı Hazırla ve Yönlendir
show_result("Başarıyla çıkış yaptınız. Güvenli günler!", "success", "index.php");
?>