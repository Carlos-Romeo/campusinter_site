FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer MySQL dans le conteneur
RUN apt-get update && apt-get install -y \
    mariadb-server \
    && rm -rf /var/lib/apt/lists/*

# Copier la configuration Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Créer la structure correcte
RUN mkdir -p /var/www/html

# Copier l'application (public = racine web)
COPY public/ /var/www/html/

# Copier les dossiers au-dessus de public pour que index.php les trouve
COPY app/ /var/www/app/
COPY config/ /var/www/config/
COPY vendor/ /var/www/vendor/
COPY storage/ /var/www/storage/
COPY database/ /var/www/database/

# Script d'initialisation au démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
