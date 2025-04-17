<div align="center">
    <h1 align="center">SendPulse Custom Telephony Starter Kit</h1>
    <h3 align="center">Laravel + Angular</h3>
    <p align="center">
        <a href="https://sendpulse.ua/integrations/api">API Documentation</a>
        ·    
        <a href="https://sendpulse.com/knowledge-base/app-directory/developers">Developer Documentation</a>
    </p>
</div>

This starter kit will help you quickly start creating integrations between SendPulse and any telephony.

## Requirements

- Docker
- Docker Compose

## Getting Started

### Installation

1. Create .env file:
```shell
cp .env.example .env
```

2. Set `SENDPULSE_APP_ID` and `SENDPULSE_APP_SECRET` variables in the .env file.

3. Build containers:
```bash
docker compose build
```

4. Start containers:
```bash
docker compose up -d
```

5. Install Composer libraries and dependencies:
```bash
docker compose exec app sh -c 'composer install'
```

6. Set the application key:
```shell
docker compose exec app sh -c 'php artisan key:generate'
```

7. Run the database migrations:
```shell
docker compose exec app sh -c 'php artisan migrate'
```

### Serve the application on port 8000:

```shell
docker compose exec app sh -c 'php artisan serve --host=0.0.0.0 --port=8000'
```

The application is available at the link: http://localhost:8000

### Run the application tests:

```shell
docker compose exec app sh -c 'php artisan test'
```

## Docker Configuration

docker-compose.yml configuration:
```yaml
version: '3.7'

services:
    app:
        build: ./
        container_name: "${APP_NAME}_app"
        working_dir: /home/www
        ports:
            - '8000:8000'
        volumes:
            - ./:/home/www
    mysql:
        image: mariadb:latest
        container_name: "${APP_NAME}_mysql"
        volumes:
            - ./data/mysql:/var/lib/mysql
        environment:
            MYSQL_USER: ${DB_USERNAME:-user}
            MYSQL_PASSWORD: ${DB_PASSWORD:-password}
            MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-password}
            MYSQL_DATABASE: ${DB_DATABASE:-custom_telephony}
        restart: always
        ports:
            - '3306:3306'
```

Dockerfile configuration:
```dockerfile
FROM php:8.4-fpm

RUN apt update \
&& apt install -y git zip unzip libzip-dev libmemcached-dev zlib1g-dev \
&& pecl install memcache \
&& docker-php-ext-install pdo_mysql \
&& docker-php-ext-enable memcache

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
```

## License

This project is licensed under the MIT License.
