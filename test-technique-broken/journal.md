# Notes techniques projet Symfony / Docker

---

## 1. Lancement du projet

Démarrage des conteneurs Docker :

```bash
sudo docker compose up -d
```

Vérification des logs des services :

```bash
docker logs test-technique-broken-mysql-1
docker logs test-technique-broken-rabbitmq-1
docker logs test-technique-broken-elasticsearch-1
docker logs app
```

---

## 2. Installation des dépendances PHP

Installer via Composer :

```bash
composer install
```

### Résolution erreur `asmblah/php-amqp-compat`

Si une erreur apparaît concernant `asmblah/php-amqp-compat`, installer l'extension AMQP :

```bash
sudo apt install librabbitmq-dev
sudo pecl install amqp
```

---

## 3. Vérifications Docker et base de données

- Vérifier les tables existantes :

```bash
php bin/console doctrine:query:sql "SHOW TABLES;"
```

- Statut des migrations Doctrine :

```bash
php bin/console doctrine:migrations:status
php bin/console debug:container --parameter database_url
```

---

## 4. Création / Migration BDD

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

---

## 4. Elasticsearch

Créer et peupler l'index Elasticsearch :

```bash
php bin/console fos:elastica:create
php bin/console fos:elastica:populate
```

### Vérifications Elasticsearch

- Elasticsearch actif :

```bash
sudo docker ps | grep elasticsearch
```

- Vérifier l'accès Elasticsearch :

```bash
curl http://localhost:9201
```

- Vérifier les données indexées :

```bash
curl -X GET "localhost:9201/freelances/_search?pretty"
```

- Vérifier le mapping :

```bash
curl -X GET "localhost:9201/freelances/_mapping?pretty"
```

- Vérifier l'état du cluster :

```bash
curl -X GET "localhost:9201/_cluster/health?pretty"
```

**Statut Yellow** : Unassigned shards à vérifier.

---

## 5. Routes disponibles

Afficher les routes Symfony :

```bash
php bin/console debug:router
```

| Name              | Method | Scheme | Host | Path                       |
| ----------------- | ------ | ------ | ---- | -------------------------- |
| \_preview_error   | ANY    | ANY    | ANY  | /\_error/{code}.{\_format} |
| freelances_search | GET    | ANY    | ANY  | /freelances                |
| status_up         | POST   | ANY    | ANY  | /status/up                 |

---

## 5. Installation de bundles manquants

```bash
composer require symfony/maker-bundle
composer require nesbot/carbon
```

---

## 5. Problèmes courants

- **Warning AMQP déjà chargé** :

```
PHP Warning: Module "amqp" is already loaded in Unknown on line 0
```

Résolu en vérifiant le chargement du module dans `php.ini`.

- **Erreur autowiring Elasticsearch** :

```
Cannot autowire service "App\Service\FreelanceSearchService": argument "$elasticsearchClient" of method "__construct()" references class "Elasticsearch\Client" but no such service exists.
```

Vérifier la déclaration du service Elasticsearch dans les services Symfony.

---

## 6. Commandes utiles

### Relancer l'application :

```bash
php bin/console cache:clear
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

### Créer et migrer la base :

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

### Commandes Docker fréquentes :

```bash
sudo docker compose ps
sudo docker compose up -d
sudo docker compose down
sudo docker network ls
sudo docker network inspect test-technique-broken_default
```

---

## 7. Tests unitaires

Exécuter des tests :

```bash
export IS_DOCKER=true
php bin/phpunit --filter testConnector tests/TechnicalTest.php
php bin/phpunit --filter testImportLinkedIn tests/TechnicalTest.php
php bin/phpunit --filter testEnvDocker tests/TechnicalTest.php
```

---

## 8. Commandes spécifiques du projet

- Scrap des données LinkedIn :

```bash
sudo docker compose exec app php bin/console app:scrap:jean-paul
```

- Recherche freelance :

```bash
php bin/console app:freelance:search "jobTitle"
```

Recherche via URL :

```
/freelances/search?query=Développeur&page=1&limit=10
```

---

## 9. Concepts appris récemment

### Circular Reference Handler

Permet de spécifier comment gérer les références circulaires en sérialisation :

```php
circular_reference_handler: fn($object) => $object->getId()
```

### ScrapLinkCommand

Cette commande récupère des données LinkedIn depuis un fichier JSON pour les insérer en base via un message bus (`InsertFreelanceLinkedInMessage`).

---

## 9. TODO à court terme

- Vérifier pourquoi Elasticsearch n'affiche pas les données dans la recherche
- Corriger le statut "yellow" d'Elasticsearch
- Décider de supprimer ou non la route dans `FreelanceController` liée au template Twig.
- Mettre en place un Makefile Docker

---

**Dernière mise à jour : 15/03/2025**
