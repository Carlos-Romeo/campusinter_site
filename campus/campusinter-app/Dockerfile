FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activer mod_rewrite
RUN a]2enmod rewrite

# Installer MySQL dans le conteneur
RUN apt-get update && apt-get install -y \
    mariadb-server \
    && rm -rf /var/lib/apt/lists/*

# Copier la configuration Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copier l'application
COPY public/ /var/www/html/
COPY app/ /var/www/html/app/
COPY config/ /var/www/html/config/
COPY vendor/ /var/www/html/vendor/
COPY storage/ /var/www/html/storage/
COPY database/ /var/www/html/database/

# Script d'initialisation au démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
