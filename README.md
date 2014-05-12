drupal-days-code-symfonfy-introduction
======================================

The sample code used for the Introductory Symfony talk held by Alessio Barnini during the Drupal Days 2014 on 8th May in Milan

Per l'installazione del progetto, posizionarsi nella root con il terminale ed installare composer

`cd drupalDays2014/talksymfony`

`curl -sS https://getcomposer.org/installer | php`

`php composer.phar update`

`php app/console doctrine:database:create`

`php app/console doctrine:schema:update --force`