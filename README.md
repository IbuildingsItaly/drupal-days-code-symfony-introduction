drupal-days-code-symfony-introduction
======================================

The sample code used for the Introductory Symfony talk held by Alessio Barnini during the Drupal Days 2014 on 8th May in Milan

## Usage
First of all, clone the repo:

```bash
$ git clone https://github.com/IbuildingsItaly/drupal-days-code-symfony-introduction
```
Then

```bash
$ cd drupal-days-code-symfony-introduction/talksymfony
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
```

Now you can connect to  

```http://localhost/path/to/symfony/app/web/config.php```

If you get any warnings or recommendations, fix them before moving on.

After That

```http://localhost/path/to/symfony/app_dev.php```
