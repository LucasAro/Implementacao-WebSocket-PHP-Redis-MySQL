FROM php:7.4-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
	libzip-dev \
	zip \
	&& docker-php-ext-install pdo pdo_mysql mysqli \
	&& pecl install redis \
	&& docker-php-ext-enable redis

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos PHP para o diretório do servidor
COPY . /var/www/html/

# Habilitar o módulo rewrite do Apache
RUN a2enmod rewrite

# Configurações de permissões
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80

# Comando para iniciar o servidor Apache
CMD ["apache2-foreground"]
