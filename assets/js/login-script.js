// ==========================================
// 1. ARAYÜZ ETKİLEŞİMİ (KART DÖNDÜRME)
// ==========================================

const cardWrapper = document.getElementById('cardWrapper');
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');

function flipCard() {
    if(cardWrapper) {
        cardWrapper.classList.toggle('is-flipped');
        if(loginBtn) loginBtn.classList.toggle('active');
        if(registerBtn) registerBtn.classList.toggle('active');
    }
}

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        if (cardWrapper.classList.contains('is-flipped')) {
            flipCard();
        }
    });
}

if (registerBtn) {
    registerBtn.addEventListener('click', () => {
        if (!cardWrapper.classList.contains('is-flipped')) {
            flipCard();
        }
    });
}

// ==========================================
// 2. İSTEMCİ TARAFLI DOĞRULAMA (ŞİFRE KONTROLÜ)
// ==========================================

const regSubmitBtn = document.getElementById('register_submit_button');
const regPasswordInput = document.getElementById('register_password_input');

if (regSubmitBtn && regPasswordInput) {
    
    // Butona tıklandığında (Form gitmeden hemen önce)
    regSubmitBtn.addEventListener('click', function(event) {
        
        const password = regPasswordInput.value;

        // Şifre boş değilse VE 6 karakterden kısaysa
        if (password.length > 0 && password.length < 6) {
            
            // 1. Formun gönderilmesini ENGELLE
            event.preventDefault(); 
            
            // 2. Özel hata mesajını ayarla
            regPasswordInput.setCustomValidity("Güvenliğiniz için şifreniz en az 6 karakter olmalıdır.");
            
            // 3. Mesajı kullanıcıya göster (Baloncuk)
            regPasswordInput.reportValidity();
        } 
        else {
            // Şifre uygunsa hata mesajını temizle (Temizlemezsen form yine gitmez)
            regPasswordInput.setCustomValidity("");
        }
    });

    // Kullanıcı yazarken hatayı anlık temizle
    regPasswordInput.addEventListener('input', function() {
        this.setCustomValidity("");
    });
}