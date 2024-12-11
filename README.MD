# Subscription service

## Installation steps
1. Run `composer install`
2. Run `php bin/console importmap:install`
3. Run `docker compose up -d`
4. Enter to the docker container with `docker compose exec php bash` and 
run inside docker container `php bin/console doctrine:migrations:migrate`
5. Open https://localhost in your browser
