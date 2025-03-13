# Pour lanccer le projet se mettre dans le dossier du projet test-technique-broken et lancer la commande suivante :

```bash
 sudo docker compose up -d
```

ensuite installer composer avce composer install
chez moi ca fct pas message d'erreur il il manque le dependances :

- asmblah/php-amqp-compat
  il faut l'installer avec la commande suivante :
  sudo apt update
  sudo apt install php-pear php8.3-dev -y

  puis
  sudo pecl install amqp

puis:
sudo apt update
sudo apt install librabbitmq-dev -y
relancer l'installation en faison:
sudo pecl install ampq

ensuite faire:
echo "extension=amqp.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=amqp.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
La première ligne ajoute extension=amqp.so dans le fichier de configuration PHP utilisé en ligne de commande (CLI).
La deuxième ligne l'ajoute dans le fichier de configuration PHP utilisé par PHP-FPM (ton serveur web).

pour checker mes conteneurs:
docker logs test-technique-broken-mysql-1
docker logs test-technique-broken-rabbitmq-1
docker logs test-technique-broken-elasticsearch-1

Ensuite pour lancer ma bdd j'ai du definir un mdp por root et mettre egalement mysql car j'vais postgres sur mon poste
sudo mysql -u root
pour voir si ma table existe :php bin/console doctrine:query:sql "SHOW TABLES;"

Routes existantes:
php bin/console debug:router
PHP Warning: Module "amqp" is already loaded in Unknown on line 0

---

Name Method Scheme Host Path

---

\_preview_error ANY ANY ANY /\_error/{code}.{\_format}  
 freelances_search GET ANY ANY /freelances  
 status_up POST ANY ANY /status/up

---

instalation du maker bundle et du nesbot/carbon bundle qui etait pas importé

ophelie@ophelie-BMH-WCX9:~/jm-test-technique-060325/test-technique-broken$

sudo docker ps | grep mysql
php bin/console doctrine:migrations:status
php bin/console debug:container --parameter database_url

probleme rencontres :en faisant mphp bin/console doctrine:query:sql "SHOW TABLES;"
j'ai 0 rows dans la table

Rappel pour relancer mon appli :
php bin/console cache:clear
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate

symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:migrations:migrate
