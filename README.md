# Filament-init

- init
```
$ cp .env.example .env && cp ./src/.env.example ./src/.env
$ cd ./src && composer install
$ docker compose up -d
$ docker compose exec filament_app bash
# php artisan key:generate
# php artisan migrate --seed
# php artisan filament-access-control:install
```

- 管理者アカウント作成  
`# php artisan filament-access-control:user`

- 新規画面作成  
`# php artisan make:filament-resource XXX --generate`

