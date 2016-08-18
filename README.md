# php-connect-four

This is my implementation of the game "Connect Four" written in PHP. It's fully tested and object oriented.

## Try out

This project can easily be started with [Vagrant](https://www.vagrantup.com) (only tested with Mac OS X). For some reasons you
don't use Vagrant. Skip to the "Without Vagrant" part then.

### With Vagrant

The project directory in the machine is "/project". Please take a look at the Vagrantfile for further information.

```
vagrant up
vagrant ssh
bin/play
```

### Without Vagrant

Note that you have to install [Composer](https://getcomposer.org) first.

```
cd /path/to/project
composer install
bin/play
```

## Run PHPUnit

```
cd /path/to/project
vendor/bin/phpunit
```