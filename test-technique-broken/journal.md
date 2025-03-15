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

| Name              | Method | Scheme | Host | Path                       |
| ----------------- | ------ | ------ | ---- | -------------------------- |
| \_preview_error   | ANY    | ANY    | ANY  | /\_error/{code}.{\_format} |
| freelances_search | GET    | ANY    | ANY  | /freelances                |
| status_up         | POST   | ANY    | ANY  | /status/up                 |

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

php bin/console debug:container --env-vars | grep ELASTICSEARCH_URL
ophelie@ophelie-BMH-WCX9:~/jm-test-technique-060325/test-technique-broken$ export IS_DOCKER=true
ophelie@ophelie-BMH-WCX9:~/jm-test-technique-060325/test-technique-broken$ php bin/phpunit --filter testEnvDocker tests/TechnicalTest.php
PHP Warning: Module "amqp" is already loaded in Unknown on line 0
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Testing App\Tests\TechnicalTest
. 1 / 1 (100%)

Time: 00:00.062, Memory: 14.00 MB

OK (1 test, 1 assertion)

A quoi sert le ScrapLinkCommand ?

- Récupérer les données de LinkedIn
- Les insérer dans la base de données

Quel est le rôle du message InsertFreelanceLinkedInMessage ?
ommande Symfony (ScrapLinkedInCommand) :
La commande scrap:linkedin lit un fichier JSON, le désérialise en objets DTO, puis envoie ces objets au Message Bus pour un traitement ultérieur.

Fichier JSON :
Le fichier jean-paul.json contient les données à désérialiser en objets DTO.

# Point nouveau que je connaissais pas

Ajout du circular_reference_handler dans le contexte : Le contexte circular_reference_handler permet de spécifier comment gérer les références circulaires. Ici, nous avons choisi de simplement retourner l'ID de l'objet pour éviter une boucle infinie dans les relations entre objets.
Gestion des groupes : Nous avons conservé les groupes de sérialisation dans le contexte ('groups' => $groups), car tu en as besoin pour spécifier quelles propriétés sérialiser.

Today: Connexion à ElasticSearch ok mais j'etais partie ds la mauuvaise directin en voulant mettre une barre de recherche sur une page twig le premier jour à mon avis
manip et resultatat :

curl http://localhost:9201
{
"name" : "423ac1b1157b",
"cluster_name" : "docker-cluster",
"cluster_uuid" : "F2PNM0yHT6ei_zavUxbZYg",
"version" : {
"number" : "7.16.3",
"build_flavor" : "default",
"build_type" : "docker",
"build_hash" : "4e6e4eab2297e949ec994e688dad46290d018022",
"build_date" : "2022-01-06T23:43:02.825887787Z",
"build_snapshot" : false,
"lucene_version" : "8.10.1",
"minimum_wire_compatibility_version" : "6.8.0",
"minimum_index_compatibility_version" : "6.0.0-beta1"
},
"tagline" : "You Know, for Search"
}
PROBLEME: la connexion se fait avec ElasticSearck mais par contre ca recupere pas les données de la base de données:
curl -X GET "localhost:9201/freelances/\_search?pretty"
{
"took" : 9,
"timed_out" : false,
"\_shards" : {
"total" : 1,
"successful" : 1,
"skipped" : 0,
"failed" : 0
},
"hits" : {
"total" : {
"value" : 1,
"relation" : "eq"
},
"max_score" : 1.0,
"hits" : [
{
"_index" : "freelances",
"_type" : "_doc",
"_id" : "1",
"_score" : 1.0,
"_source" : {
"firstName" : "John",
"lastName" : "Doe",
"jobTitle" : "Developer"
}
}
]
}
}
Commande de deugage :
php bin/console debug:container FreelanceSearchService

Ok je crois que le mapping est pas juste car car j'ontiens les resultas des donnes rentrees manuelement en faisant :
curl -X GET "localhost:9201/freelances/\_mapping?pretty"

15/03/2025
Rectification de mon controller FreelanceController.php

TODO; a voir si je le supprime ou pas car y'a une route qui va faire un fichier twig , donc voir sir je la supprime oou pas ou si je met un template en place si j'ai le temps

Mapping rectifié pour Elasticsearch mais un statut Yellow:
curl -X GET "localhost:9201/\_cluster/health?pretty"
{
"cluster_name" : "docker-cluster",
"status" : "yellow",
"timed_out" : false,
"number_of_nodes" : 1,
"number_of_data_nodes" : 1,
"active_primary_shards" : 4,
"active_shards" : 4,
"relocating_shards" : 0,
"initializing_shards" : 0,
"unassigned_shards" : 1,
"delayed_unassigned_shards" : 0,
"number_of_pending_tasks" : 0,
"number_of_in_flight_fetch" : 0,
"task_max_waiting_in_queue_millis" : 0,
"active_shards_percent_as_number" : 80.0
}
sudo docker logs test-technique-broken-elasticsearch-1
