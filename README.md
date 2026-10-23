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

- `GET /api/product/list` : récupérer les produits (authentification JWT et rôle `ROLE_API` requis)
- `GET /product/{id}` : afficher la photo, le nom, le prix et la description longue d’un produit dans une page Twig (session web et rôle `ROLE_USER` requis)
