APP = filament_app
USER = bitnami

up:
	docker compose up -d
build:
	docker compose build --no-cache --force-rm
laravel-install:
	docker compose exec -it -u ${USER} ${APP} composer create-project --prefer-dist laravel/laravel .
create-project:
	@make build
	@make up
	@make laravel-install
	docker compose exec -it -u ${USER} ${APP} php artisan key:generate
	docker compose exec -it -u ${USER} ${APP} php artisan storage:link
	@make fresh
install-recommend-packages:
	docker compose exec -it -u ${USER} ${APP} composer require doctrine/dbal
	docker compose exec -it -u ${USER} ${APP} composer require --dev barryvdh/laravel-ide-helper
	docker compose exec -it -u ${USER} ${APP} composer require --dev beyondcode/laravel-dump-server
	docker compose exec -it -u ${USER} ${APP} composer require --dev barryvdh/laravel-debugbar
	docker compose exec -it -u ${USER} ${APP} composer require --dev roave/security-advisories:dev-master
	docker compose exec -it -u ${USER} ${APP} php artisan vendor:publish --provider="BeyondCode\DumpServer\DumpServerServiceProvider"
	docker compose exec -it -u ${USER} ${APP} php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
init:
	docker compose up -d --build
	docker compose exec -it -u ${USER} ${APP} composer install
	docker compose exec -it -u ${USER} ${APP} cp .env.example .env
	docker compose exec -it -u ${USER} ${APP} php artisan key:generate
	docker compose exec -it -u ${USER} ${APP} php artisan storage:link
	@make fresh
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
destroy-volumes:
	docker compose down --volumes --remove-orphans
ps:
	docker compose ps
logs:
	docker compose logs
logs-watch:
	docker compose logs --follow
log-${APP}:
	docker compose logs ${APP}
log-${APP}-watch:
	docker compose logs --follow ${APP}
log-app:
	docker compose logs app
log-app-watch:
	docker compose logs --follow app
log-db:
	docker compose logs db
log-db-watch:
	docker compose logs --follow db

C=--version
artisan:
	docker compose exec -it -u ${USER} ${APP} php artisan ${C}

bash:
	docker compose exec -it -u ${USER} ${APP} bash
migrate:
	docker compose exec -it -u ${USER} ${APP} php artisan migrate
fresh:
	docker compose exec -it -u ${USER} ${APP} php artisan migrate:fresh --seed
seed:
	docker compose exec -it -u ${USER} ${APP} php artisan db:seed
rollback-test:
	docker compose exec -it -u ${USER} ${APP} php artisan migrate:fresh
	docker compose exec -it -u ${USER} ${APP} php artisan migrate:refresh
tinker:
	docker compose exec -it -u ${USER} ${APP} php artisan tinker
test:
	docker compose exec -it -u ${USER} ${APP} php artisan test
optimize:
	docker compose exec -it -u ${USER} ${APP} php artisan optimize
optimize-clear:
	docker compose exec -it -u ${USER} ${APP} php artisan optimize:clear
cache:
	docker compose exec -it -u ${USER} ${APP} composer dump-autoload -o
	@make optimize
	docker compose exec -it -u ${USER} ${APP} php artisan event:cache
	docker compose exec -it -u ${USER} ${APP} php artisan view:cache
cache-clear:
	docker compose exec -it -u ${USER} ${APP} composer clear-cache
	@make optimize-clear
	docker compose exec -it -u ${USER} ${APP} php artisan event:clear

MODEL = foo
OPTIONS = 
model-generate:
	docker compose exec -it -u ${USER} ${APP} php artisan make:model ${MODEL} ${OPTIONS}
resource-generate:
	docker compose exec -it -u ${USER} ${APP} php artisan make:filament-resource ${MODEL} --generate
relation-generate:
	docker compose exec -it -u ${USER} ${APP} php artisan make:filament-relation-manager ${MODEL}Resource 

npm:
	@make npm-install
npm-install:
	docker compose exec -it -u ${USER} ${APP} npm install
npm-dev:
	docker compose exec -it -u ${USER} ${APP} npm run dev
npm-watch:
	docker compose exec -it -u ${USER} ${APP} npm run watch
npm-watch-poll:
	docker compose exec -it -u ${USER} ${APP} npm run watch-poll
npm-hot:
	docker compose exec -it -u ${USER} ${APP} npm run hot
yarn:
	docker compose exec -it -u ${USER} ${APP} yarn
yarn-install:
	@make yarn
yarn-dev:
	docker compose exec -it -u ${USER} ${APP} yarn dev
yarn-watch:
	docker compose exec -it -u ${USER} ${APP} yarn watch
yarn-watch-poll:
	docker compose exec -it -u ${USER} ${APP} yarn watch-poll
yarn-hot:
	docker compose exec -it -u ${USER} ${APP} yarn hot
db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker compose exec redis redis-cli
ide-helper:
	docker compose exec -it -u ${USER} ${APP} php artisan clear-compiled
	docker compose exec -it -u ${USER} ${APP} php artisan ide-helper:generate
	docker compose exec -it -u ${USER} ${APP} php artisan ide-helper:meta
	docker compose exec -it -u ${USER} ${APP} php artisan ide-helper:models --nowrite
