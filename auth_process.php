<?php

// oturum açılmış mı kontrol ediyorum
session_start();

// veritabanını bu belgeye çağırıyorum.
require_once 'config/db.php';

// Kayıt olma aşamaları
if(isset($_POST['username'])) {

    // formdan gelen verileri alıp form temizliyorum
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($username) || empty($email) || empty($password)) {
        show_result("Lütfen tüm alanları eksiksiz doldurun!", "error");
    }

    try {
        // girilen username veya maile sahip bir kullanıcı var mı diye kontrol ediyorum
        $control = $db->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email");
        $control->execute(['username' => $username, 'email' => $email]);

        // istenen bilgilere sahip kaç satır bulundu?
        if($control->rowCount() > 0) {
            show_result("Bu kullanıcı adı veya e-posta zaten kullanılıyor!", "error", "login_register.php");
        }

        // Güvenlik açıklarını engellemek için şifreyi hash'liyorum
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Veritabanına bilgileri kaydediyorum
        $sql = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')");
         


        // Hazırladığım sql'e gerçek verileri ekliyorum
        $sonuc = $sql->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password_hash
        ]);

        // Kayıt işlemi başarılı olursa
        if($sonuc) {
            show_result("Kayıt Başarılı! Şimdi giriş yapabilirsiniz.", "success", "login_register.php");
        }

    }
    catch(PDOException $e) {
        show_result("Veritabanı Hatası: " . $e->getMessage(), "error");
    }

}

// Giriş yapma aşamaları
elseif(isset($_POST['login'])) {
    
    // email ve şifreyi alıyorum
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Boş bırakılan bir yer var mı diye kontrol ediyorum
    if(empty($email) || empty($password)) {
        show_result("E-posta ve şifre boş bırakılamaz!", "error");
    }

    try {

        // veritabanında kullanıcıyı buluyorum
        $sorgu = $db->prepare("SELECT * FROM users WHERE email = :email");
        $sorgu->execute(['email' => $email]);

        // veritabanından gelen satırı $user değişkenine alıyorum
        $user = $sorgu->fetch(PDO::FETCH_ASSOC);

        // Şifreyi kontrol ediyorum
        if($user && password_verify($password, $user['password'])) {
            // Giriş başarılı

            // Oturumu doldurma işlemine geçiyorum
            // Sunucuya useri tanıtıyorum
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Kullanıcıyı bilgilendirme ve yönlendirme işlemini yapıyorum
            // Rol kontrolüne göre yönlendirme
            $hedef = ($user['role'] === 'admin') ? 'admin.php' : 'index.php';
            show_result("Giriş Başarılı! Yönlendiriliyorsunuz...", "success", $hedef);
        }
        else {
            // şifre yanlış ya da kullanıcı bulunamadı
            show_result("Hatalı E-posta veya Şifre!", "error", "login_register.php");
        }
    }
    catch(PDOException $e) {
        show_result("Sistem Hatası: " . $e->getMessage(), "error");
    }
}

?>