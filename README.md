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


## 🧠 Explication de l’approche technique
### 🎯 Objectif
Fournir une API REST fiable, claire et maintenable pour la gestion de cartes à jouer (tirage et tri), en respectant les bonnes pratiques de développement logiciel.

### ⚙️ Choix technologiques
1) Symfony 6.4 : Framework robuste, modulaire et orienté best practices.

2) PHP 8.3 : Typage fort, attributs natifs, performances accrues.

3) Docker : Isolation de l’environnement, portabilité, facilité de déploiement.

4) Doctrine ORM : Gestion des entités et des migrations de base de données.

5) OpenAPI / Swagger : Documentation automatique des endpoints.

6) PHPUnit : Tests unitaires et fonctionnels.

## 🧱 Décisions architecturales
### ✅ Standardisation des réponses API
- Toutes les réponses suivent une structure uniforme :

 -  {
  "header": {
  "code": 200,
  "message": "..."
  },
  "response": [...]
  }

#### ➡️ Cela facilite la consommation de l’API côté front-end et garantit la cohérence des erreurs et succès.

### 🎨 Respect du principe Single Responsibility (SRP)
- Chaque classe/service a une responsabilité unique :

- CardController : gestion des requêtes HTTP

- CardService : logique métier (tirage, tri)

- CardValidator (si le validator est nécessaire) : validation métier

#### ➡️ Ce découpage améliore la lisibilité et la testabilité.


## 🧩 Utilisation du Design Pattern Decorator
- Appliqué pour enrichir certaines fonctionnalités (ex. ajout de logs, validations ou formats de sortie) sans modifier la logique principale.

##### ➡️ Permet d’ajouter des comportements dynamiquement tout en respectant l’Open/Closed Principle.

### 🧼 Encapsulation et découplage

 -  Encapsulent les données des requêtes, séparées des entités Doctrine.

 - Les services sont découplés des contrôleurs pour isoler la logique métier.

 - Le validateur de cartes peut être remplacé ou modifié sans impacter les autres couches.

#### ➡️ Cela favorise la maintenance, les tests unitaires et la réutilisabilité.


## 📚 Documentation automatique via OpenAPI
 - Annotations #[OA\RequestBody] et #[OA\Response] pour chaque endpoint

 - Structure des requêtes et réponses explicitée

 - Swagger UI disponible pour tester et documenter facilement l’API
