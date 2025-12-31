# Resmi PHP 8.2 ve Apache imajını kullan
FROM php:8.2-apache

# Gerekli PHP eklentilerini kur (MySQL bağlantısı için pdo ve pdo_mysql şart)
RUN docker-php-ext-install pdo pdo_mysql

# Apache mod_rewrite modülünü aktif et (SEO dostu URL'ler için gerekli olabilir)
RUN a2enmod rewrite

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Proje dosyalarını konteynerin içine kopyala
COPY . /var/www/html/

# Dosya izinlerini ayarla (Resim yükleme vs. için www-data kullanıcısına yetki ver)
RUN chown -R www-data:www-data /var/www/html