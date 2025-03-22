# 🎴 API de Jeu de Cartes - Symfony 6.4

## 📌 Description

Cette application expose une API permettant de **tirer** et **trier** des cartes à jouer.  
Elle est destinée aux développeurs qui souhaitent intégrer ou tester une logique de gestion de cartes via une API REST.  
Le projet est construit avec **Symfony 6.4**, utilise **Docker** pour l’environnement, **Doctrine** pour la base de données et propose une documentation Swagger pour tester les endpoints.

---

## 🐳 Lancer l'application

###  Démarrage avec Docker

```bash

### 1. Construit les conteneurs (symfony , PHP, PostgreSql, PgAdmin, Nginx), lance l’environnement complet en arrière-plan (-d) et applique les instructions du docker-compose.yml

docker compose up -d --build

---
### 2. Exécuter les migrations en environnement de développement

docker exec -it symfony_app php bin/console doctrine:migrations:migrate


### 3. Exécuter les migrations en environnement de test

docker exec -it symfony_app php bin/console doctrine:migrations:migrate --env=test

---
### 4. Charger les fixtures environnement de développement

docker exec -it symfony_app php bin/console doctrine:fixtures:load --no-interaction

---
### 5. Charger les fixtures environnement de test

docker exec -it symfony_app php bin/console doctrine:fixtures:load --env=test --no-interaction

---
### 6. Lancer les tests

docker exec -it symfony_app ./vendor/bin/phpunit
```
### La documentation Swagger est disponible à l'adresse suivante :

👉 http://192.168.1.13/api/doc
