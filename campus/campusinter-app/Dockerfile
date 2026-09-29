FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql pgsql pdo_pgsql

# Installer les extensions pour l'upload de fichiers et le client PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite
RUN a2enmod rewrite

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
COPY database/ /var/www/database/

# Créer les dossiers de stockage avec les bonnes permissions
RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/uploads \
    && chmod -R 755 /var/www/storage

# Script d'initialisation au démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
