# What's next?

## Usefull commands:

### Server commands

* `php bin/console server:run [boundIp[:port]]`: Open a server. Same as `php -S 127.0.0.1:8000 -t public`
* `php bin/console server:start [boundIp[:port]]`: Start as a service
* `php bin/console server:stop`: Stop as a service
* `npm run webpack:run:dev`: Start the assets server

### Models related

* `php bin/console make:entity Something`: create model named *Something*
* `php bin/console doctrine:migrations:diff`: Generate SQL diff for models
* `php bin/console doctrine:migrations:migrate`: Apply diff

## Links:

For MySQL connection: https://symfony.com/doc/current/doctrine.html
Read the documentation at https://symfony.com/doc

