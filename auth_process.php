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
        die("Hata: Tüm alanları doldurduğunuzdan emin olun!");
    }

    // if(strlen($password) < 6) {
    //     echo "<script>alert('Şifreniz en az 6 karakter olmalıdır!'); window.history.back();</script>";
    //     exit;
    // } 

    try {
        // girilen username veya maile sahip bir kullanıcı var mı diye kontrol ediyorum
        $control = $db->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email");
        $control->execute(['username' => $username, 'email' => $email]);

        // istenen bilgilere sahip kaç satır bulundu?
        if($control->rowCount() > 0) {
            echo "<script> alert('bu username ve/veya email zaten kullanılıyor, başka bir username/email deneyin'); window.location.href='login_register.php'; </script>";
            exit;
        }

        // Güvenlik açıklarını engellemek için şifreyi hash'liyorum
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Veritabanına bilgileri kaydediyorum
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')";
        $stmt = $db->prepare($sql);


        // Hazırladığım sql'e gerçek verileri ekliyorum
        $sonuc = $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password_hash
        ]);

        // Kayıt işlemi başarılı olursa
        if($sonuc) {
            echo "<script>alert('Başarıyla kayıt oldunuz!'); window.location.href='login_register.php'; </script>";
            exit;
        }

    }
    catch(PDOException $e) {
        die("Veritabanı hatası: " . $e->getMessage());
    }

}

// Giriş yapma aşamaları
elseif(isset($_POST['login'])) {
    
    // email ve şifreyi alıyorum
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Boş bırakılan bir yer var mı diye kontrol ediyorum
    if(empty($email) || empty($password)) {
        die("Hata: Eposta ve şifre boş bırakılamaz!");
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
            echo "<script> alert('Başarıyla giriş yaptınız!'); window.location.href='index.php'; </script>";
            exit;
        }
        else {
            // şifre yanlış ya da kullanıcı bulunamadı
            echo "<script> alert('Eposta veya şifreyi yanlış girdiniz!'); window.location.href='login_register.php'; </script>";
            exit;
        }
    }
    catch(PDOException $e) {
        die("Sistem hatası: " . $e->getMessage());
    }
}

?>