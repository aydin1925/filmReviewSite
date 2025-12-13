<?php

session_start();

require_once 'config/db.php';

// 3. Kullanıcı Verilerini Temizle
// $_SESSION dizisindeki her şeyi silmiyoruz, sadece kimlik verilerini siliyoruz.
// Böylece 'swal_title' gibi mesaj verileri kalabilir.
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['role']);

// 4. Güvenlik Önlemi: Session ID'yi Değiştir
// Bu, "Session Fixation" saldırılarını önler.
// Eski oturum dosyası silinir, yeni ve temiz bir ID ile devam edilir.
session_regenerate_id(true);

// 5. Mesajı Hazırla ve Yönlendir
show_result("Başarıyla çıkış yaptınız. Güvenli günler!", "success", "index.php");
?>