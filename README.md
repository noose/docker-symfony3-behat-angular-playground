# Before start
1. `composer install`
1. `npm install`
1. Configure your `parameters.yml` (for docker - look below)
1. `php bin/console doctrine:schema:create`

# Docker
You can use docker if you like! Simply run 
```bash
docker-compose up -d
```
And it's just works!
For Windows/OSX users - go to the `http://YourDockerMachineIp:8000` (If you don't know what that IP is execute `docker-machine ip MachineName` (in most cases `MachineName` is `default`)
For Linux users - go to the `http://localhost:8000` 

There is _smart_ `entrypoint.sh` which set proper configurations for DB. 
If you want to run commands inside docker container you can run:
```docker-compose run app cmd```

## docker parameters.yml
```yml
# This file is auto-generated during the composer install
parameters:
    database_host: '%database.host%'
    database_port: '%database.port%'
    database_name: '%database.database%'
    database_user: '%database.user%'
    database_password: '%database.password%'
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: 725e89d89b88296adc36bcfd3cf1b71c3ce5f081
```

# How to run tests
## AngularJS
```bash
./node_modules/.bin/webdriver-manager update --standalone
./node_modules/.bin/webdriver-manager start
./node_modules/.bin/protractor tests/js/protractor.conf.js
```

## PHP
```bash
php bin/console server:start -e test --force 127.0.0.1:8001
vendor/bin/behat -c behat.yml
```