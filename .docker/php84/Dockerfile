FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    ${PHPIZE_DEPS} \
    libxml2-dev \
    && docker-php-ext-install soap \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apk del ${PHPIZE_DEPS}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["php", "-v"]
