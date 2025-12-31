# 🎬 FilmFlux (Project Codename)

![Status](https://img.shields.io/badge/Status-Active%20Development-success?style=for-the-badge)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![PHP](https://img.shields.io/badge/Backend-PHP%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

> **Not:** Bu proje geliştirme aşamasındadır ve kodlarda şimdilik "FilmFlux" kod adıyla anılmaktadır.

**FilmFlux**, modern web teknolojileri kullanılarak geliştirilmiş, ölçeklenebilir ve güvenli bir sinema veritabanı yönetim sistemidir. Kullanıcıların filmleri keşfedebildiği, puanlayıp yorumlayabildiği; yöneticilerin ise içerik havuzunu yönetebildiği dinamik bir platformdur.

Bu proje, özellikle **İlişkisel Veritabanı Mimarisi**, **Backend Mantığı** ve **Dockerizasyon** yeteneklerini sergilemek amacıyla geliştirilmiştir.

---

## 🚀 Proje Mimarisi ve Geliştirme Yaklaşımı

Bu proje geliştirilirken **"AI-Assisted Development" (Yapay Zeka Destekli Geliştirme)** metodolojisi izlenmiştir.

* **👨‍💻 Backend & Mimarisi (İnsan Odaklı):** Veritabanı normalizasyonu, SQL sorgu optimizasyonu, PHP oturum yönetimi (Session), güvenlik katmanları (Prepared Statements) ve Docker altyapısı tarafımca kurgulanmış ve kodlanmıştır.
* **🤖 Frontend & Prototipleme (AI Destekli):** Kullanıcı arayüzü (UI) tasarımı ve CSS/Bootstrap bileşenlerinin hızlı prototiplenmesi sürecinde LLM araçlarından faydalanılmıştır. Bu sayede odak noktası, sistemin kararlılığına ve backend mantığına verilmiştir.

---

## ✨ Temel Özellikler

### 🛡️ Backend & Altyapı
* **Docker Containerization:** Proje; PHP, Apache, MySQL ve phpMyAdmin servislerini içeren tam izole bir Docker ortamında çalışmaktadır.
* **MVC Benzeri Yapı:** Kodun okunabilirliği için mantıksal ayrımlar yapılmıştır.
* **Güvenlik:** SQL Injection'a karşı PDO Prepared Statements ve XSS korumaları.
* **Session Yönetimi:** Güvenli kullanıcı oturumları ve yetkilendirme (Role-Based Access Control).

### 👤 Kullanıcı Deneyimi
* **Canlı Arama (Live Search):** jQuery ve AJAX ile sayfa yenilenmeden anlık veri getirme.
* **Dinamik Bildirimler:** İşlem sonuçları için SweetAlert2 entegrasyonu.
* **Responsive Tasarım:** Bootstrap 5 ile tüm cihazlara uyumlu arayüz.

---

## 🛠️ Kurulum (Docker ile Saniyeler İçinde)

Projeyi yerel makinenizde çalıştırmak için bilgisayarınızda **Docker Desktop**'ın yüklü olması yeterlidir.

1.  **Depoyu Klonlayın:**
    ```bash
    git clone [https://github.com/aydin1925/filmReviewSite.git](https://github.com/aydin1925/filmReviewSite.git)
    cd filmReviewSite
    ```

2.  **Konteynerleri Ayağa Kaldırın:**
    ```bash
    docker compose up -d --build
    ```

3.  **Tarayıcıda Açın:**
    * **Uygulama:** [http://localhost](http://localhost)
    * **Veritabanı Yönetimi:** [http://localhost:8080](http://localhost:8080)

*(Not: Veritabanı tabloları, `docker-compose` başlatıldığında `/sql` klasöründeki dosya sayesinde otomatik kurulur.)*

---

## 🗄️ Veritabanı Şeması

Proje **İlişkisel Veritabanı (Relational Database)** yapısına sahiptir:
* **Users:** Kullanıcı bilgileri ve rol (admin/user) tanımları.
* **Movies:** Film detayları, vizyon durumu ve teknik veriler.
* **Reviews:** Kullanıcıların filmlere verdiği puanlar ve yorumlar (Foreign Key ile bağlı).

---

## 👨‍💻 Geliştirici

**Aydın ŞAHİN**


* **GitHub:** [@aydin1925](https://github.com/aydin1925)
* **E-Posta:** [aydinsahin1925@gmail.com](mailto:aydinsahin1925@gmail.com)
* **LinkedIn:** [www.linkedin.com/in/aydinsahin1925](www.linkedin.com/in/aydinsahin1925)
