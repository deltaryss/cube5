# Utilisez l'image de base PHP 7.4 avec Apache
FROM php:apache

# Activer le module Rewrite
RUN a2enmod rewrite

# Copiez le code source dans le conteneur
COPY . /var/www/html

# Définissez le répertoire de travail
WORKDIR /var/www/html

# Modifiez la configuration d'Apache pour autoriser l'accès à /var/www/html
RUN sed -i 's/Require all denied/Require all granted/' /etc/apache2/apache2.conf
RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Modifier le fichier de configuration du host apache
RUN sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/' /etc/apache2/sites-enabled/000-default.conf


# Installez les dépendances nécessaires
RUN apt-get update && \
    apt-get install -y nodejs npm && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip && \
    composer install && \
    npm install

# Exposez le port 80 pour accéder à votre application via Apache
EXPOSE 80

# Démarrez le serveur Apache
CMD ["apache2-foreground"]
