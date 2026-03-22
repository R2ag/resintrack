FROM php:8.2-apache

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para URLs amigáveis (se necessário)
RUN a2enmod rewrite

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto (opcional, pois usamos volumes)
# COPY . /var/www/html

# Expor porta 80
EXPOSE 80