# OPC12

Application Symfony pour gérer les utilisateurs, les conseils du mois et les relevés météorologiques en fonction du code postal.

## Description

Ce projet est une API REST développée avec Symfony 7.4. Elle permet :

- de créer et gérer des utilisateurs ;
- de récupérer la météo du jour pour un code postal donné via l’API Weatherbit ;
- de gérer des conseils (tips) associés à des mois spécifiques ;

## Stack technique

- PHP 8.2
- Symfony 7.4
- Doctrine ORM
- Lexik JWT Authentication Bundle
- Symfony Security
- Symfony Validator / Serializer

## Prérequis

- PHP 8.2+
- Composer
- Base de données configurée
- Clés JWT générées localement

## Installation

```bash
composer install
```

Créer puis configurer votre fichier `.env.local` avec la base de données et les variables JWT :

```env
DATABASE_URL=
JWT_SECRET_KEY=
JWT_PUBLIC_KEY=
JWT_PASSPHRASE=
JWT_EXPIRATION_TIME=
WEATHER_BIT_API_KEY=
WEATHER_BIT_API_BASE_URL=https://api.weatherbit.io/v2.0
```

Générer les clés JWT :

```bash
mkdir -p config/jwt
php bin/console lexik:jwt:generate-keypair
```

Créer les tables et lancer les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Charger les fixtures si besoin :

```bash
php bin/console doctrine:fixtures:load
```

Les fixtures créent l’utilisateur `alex.morgan@example.com` avec le mot de passe `password`, une commande en attente et deux produits.

## Authentification

L’application propose deux modes de connexion indépendants : JWT pour l’API et session Symfony pour les pages web.

### Connexion à l’API

```http
POST /api/login
```

Le corps JSON contient `username` et `password`. Une connexion réussie renvoie un JWT dans `token`; des identifiants invalides renvoient `401` et un utilisateur sans le rôle `ROLE_API` reçoit `403`. Les requêtes API protégées doivent envoyer `Authorization: Bearer <token>` et l’utilisateur doit avoir le rôle `ROLE_API`.

### Connexion au site

La route `GET /login` affiche le formulaire Twig. Sa soumission authentifie l’utilisateur avec le firewall web et ouvre une session Symfony; cette session sert à accéder aux pages web protégées. Les routes non-API exigent le rôle `ROLE_USER`.

## Endpoints principaux

### Produits

- `GET /api/products` : récupérer les produits (authentification JWT et rôle `ROLE_API` requis)

### Utilisateurs

- `POST /api/user/create` : créer un utilisateur
- `PUT /api/user/{user}` : modifier un utilisateur (admin)
- `DELETE /api/user/{user}` : supprimer un utilisateur (admin)

### Météo

- `GET /api/weather-mesurement` : météo pour le code postal de l’utilisateur connecté
- `GET /api/weather-mesurement/{postalCode}` : météo pour un code postal donné

### Conseils

- `GET /api/tip/` : conseils du mois en cours
- `GET /api/tip/month/{monthNum}` : conseils pour un mois donné
- `POST /api/tip` : créer un conseil (admin)
- `PUT /api/tip/{tip}` : modifier un conseil (admin)
- `DELETE /api/tip/{tip}` : supprimer un conseil (admin)

## Sécurité

Le projet inclut une sécurisation par JWT et des restrictions selon les rôles utilisateur.

- les routes de connexion et de création de compte sont publiques ;
- les autres routes API sont protégées ;
- certaines actions demandent le rôle `ROLE_ADMIN`.

## Météo et intégration Weatherbit

La partie météo du projet est intégrée à l’API Weatherbit. Le service dédié envoie une requête pour le code postal donné et récupère la température actuelle pour le pays correspondant (France par défaut pour ce projet).

Les variables d’environnement nécessaires sont :

- `WEATHER_BIT_API_KEY`
- `WEATHER_BIT_API_BASE_URL`

Les données météo sont ensuite mises en cache et stockées dans la base de données pour éviter de refaire des appels inutiles.

## Développement

Ce projet est pensé comme une API backend légère et évolutive, avec une séparation claire entre contrôleurs, services, entités et DTO.

## Licence

Projet sous licence propriétaire.
