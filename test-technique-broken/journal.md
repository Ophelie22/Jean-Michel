# Guide d'installation et de configuration du projet

## 1. Lancer le projet

Se placer dans le dossier du projet `test-technique-broken` et exécuter la commande suivante :

```bash
sudo docker compose up -d
```

## 2. Installation de Composer

Installer les dépendances PHP avec :

```bash
composer install
```

### 2.1. Erreur de dépendances manquantes

Si une erreur apparaît indiquant l'absence de `asmblah/php-amqp-compat`, installez les paquets nécessaires :

```bash
sudo apt update
sudo apt install php-pear php8.3-dev -y
```

Puis installez l'extension `amqp` :

```bash
sudo pecl install amqp
```

Ensuite, installez la bibliothèque `librabbitmq-dev` :

```bash
sudo apt update
sudo apt install librabbitmq-dev -y
```

Relancez l'installation de l'extension `amqp` :

```bash
sudo pecl install amqp
```

Ajoutez ensuite l'extension à PHP :

```bash
echo "extension=amqp.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=amqp.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

- La première ligne ajoute `extension=amqp.so` au fichier de configuration PHP utilisé en ligne de commande (CLI).
- La deuxième ligne l'ajoute au fichier de configuration PHP utilisé par PHP-FPM (serveur web).

## 3. Vérification des conteneurs Docker

```bash
docker logs test-technique-broken-mysql-1
docker logs test-technique-broken-rabbitmq-1
docker logs test-technique-broken-elasticsearch-1
```

## 4. Configuration de MySQL

Si vous avez PostgreSQL installé sur votre machine, définissez un mot de passe root pour MySQL et assurez-vous d'utiliser MySQL par défaut :

```bash
sudo mysql -u root
```

Vérifiez si la base de données contient des tables :

```bash
php bin/console doctrine:query:sql "SHOW TABLES;"
```

## 5. Vérification des routes disponibles

```bash
php bin/console debug:router
```

Si vous obtenez l'avertissement suivant :

```
PHP Warning: Module "amqp" is already loaded in Unknown on line 0
```

C'est que le module `amqp` est déjà chargé.

### Routes existantes :

| Name                 | Method | Scheme | Host | Path                    |
|----------------------|--------|--------|------|-------------------------|
| _preview_error      | ANY    | ANY    | ANY  | /_error/{code}.{_format} |
| freelances_search   | GET    | ANY    | ANY  | /freelances              |
| status_up          | POST   | ANY    | ANY  | /status/up               |

## 6. Installation de bundles manquants

Installation de `maker-bundle` et `nesbot/carbon` :

```bash
composer require symfony/maker-bundle
composer require nesbot/carbon
```

## 7. Vérification de l'état de la base de données

```bash
sudo docker ps | grep mysql
php bin/console doctrine:migrations:status
php bin/console debug:container --parameter database_url
```

### Problèmes rencontrés

- En exécutant :

```bash
php bin/console doctrine:query:sql "SHOW TABLES;"
```

  - **Problème :** `0 rows in the table`

## 8. Rappel pour relancer l'application

```bash
php bin/console cache:clear
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

Création et migration de la base de données :

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

## 9. Création de l'index Elasticsearch

```bash
php bin/console fos:elastica:create
php bin/console fos:elastica:populate
```

### Problèmes rencontrés

- **Problème :** `PHP Warning: Module "amqp" is already loaded in Unknown on line 0`
- **Problème :** `Cannot autowire service "App\Service\FreelanceSearchService": argument "$elasticsearchClient" of method "__construct()" references class "Elasticsearch\Client" but no such service exists.`

### TODO : Vérifier Elasticsearch

- **Vérifier si Elasticsearch tourne bien** :

```bash
sudo docker ps | grep elasticsearch
```

- **Vérifier l'accès à Elasticsearch** :

```bash
curl http://localhost:9201
```

Si l'accès est fonctionnel, le problème vient probablement de la configuration.

### TODO : Vérifier pourquoi les données ne s'affichent pas dans la recherche

## 10. Recherche Freelance

Exécution de la commande de recherche :

```bash
php bin/console app:freelance:search "jobTitle"
```

Recherche avec l'URL :

```
/freelances/search?query=Développeur&page=1&limit=10
```

---

## DONE : Tests unitaires

### `TechnicalTest.php`

#### `testConnector`

Le test `testConnector` fonctionne.

**Manipulation :**

```bash
export IS_DOCKER=true
php bin/phpunit --filter testConnector tests/TechnicalTest.php
```

#### `testImportLinkedIn`

```bash
export IS_DOCKER=true
php bin/phpunit --filter testImportLinkedIn tests/TechnicalTest.php
```

---

Cette version met en avant les **TODO**, les **problèmes rencontrés**, et le **suivi des tests** comme tu l'avais demandé. 😊 N'hésite pas à me dire si tu veux d'autres ajustements ! 🚀

