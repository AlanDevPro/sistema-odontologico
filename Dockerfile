FROM php:8.2-fpm-bookworm

# Instalar dependencias esenciales del sistema y la librería libaio1 para Oracle
# (Se incluye 'curl' para asegurar la descarga de Node.js)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    build-essential \
    libaio1 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Crear el directorio destino para Oracle dentro de Linux
RUN mkdir -p /opt/oracle/instantclient_19_31

# COPIAR directamente todo el contenido estructurado de tu carpeta local
COPY oracle-instantclient/ /opt/oracle/instantclient_19_31/

# REPARAR LOS ENLACES SIMBÓLICOS (Evita que Windows rompa la compilación)
RUN cd /opt/oracle/instantclient_19_31 && \
    rm -f libclntsh.so libocci.so libocci_gcc53.so && \
    ln -s libclntsh.so.19.1 libclntsh.so && \
    ln -s libocci.so.19.1 libocci.so && \
    ln -s libocci_gcc53.so.19.1 libocci_gcc53.so

# Crear un enlace simbólico estándar para que las rutas queden unificadas
RUN ln -s /opt/oracle/instantclient_19_31 /opt/oracle/instantclient

# Configurar variables de entorno requeridas para el sistema y el compilador
ENV ORACLE_HOME=/opt/oracle/instantclient
ENV LD_LIBRARY_PATH=/opt/oracle/instantclient

# Compilar e instalar extensiones oci8 y pdo_oci de PHP de forma nativa
RUN echo "instantclient,/opt/oracle/instantclient" | pecl install oci8-3.3.0 && \
    docker-php-ext-enable oci8 && \
    docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient && \
    docker-php-ext-install pdo_oci bcmath xml

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- NUEVO: Instalar Node.js y NPM (Necesario para Vite y Tailwind) ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Ajustar permisos para desarrollo en Laravel
RUN chown -R www-data:www-data /var/www

# --- MEJORAS DEL ENTRYPOINT ---

USER root

# Copiar el script al contenedor
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Dar permisos de ejecución dentro del contenedor
RUN chmod +x /usr/local/bin/entrypoint.sh

# Establecer el script como el punto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Mantener PHP-FPM corriendo (Nginx lo necesita, no uses php artisan serve aquí)
CMD ["php-fpm"]