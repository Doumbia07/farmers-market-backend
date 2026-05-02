# Farmers Market API

[![Laravel Version](https://releaserun.com/badge/laravel/)](https://releaserun.com/eol/laravel/)
[![Railway Deployed](https://img.shields.io/badge/Railway-Deployed-0B0D0E?logo=railway)](https://farmers-market-backend-production-9194.up.railway.app)
[![Sanctum Auth](https://img.shields.io/badge/Auth-Sanctum-4B32C3?logo=laravel)](https://laravel.com/docs/sanctum)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)


## 📌 À propos du projet

Cette API RESTful alimente la plateforme **Farmers Market POS**, un système de point de vente dédié aux marchés agricoles en Côte d'Ivoire. Elle permet aux agriculteurs d'acheter des produits (pesticides, engrais, semences) et de gérer des crédits remboursables en nature (cacao, etc.).

**Fonctionnalités clés :**
- Gestion des utilisateurs avec trois rôles : **Admin**, **Superviseur** et **Opérateur**.
- Gestion complète des **agriculteurs** (création, recherche par ID/téléphone, limite de crédit).
- Catalogue de **catégories et produits** avec structure arborescente.
- Transactions en **espèces** ou à **crédit** (avec calcul automatique des intérêts).
- Blocage des transactions qui dépassent la limite de crédit.
- Remboursement des dettes en **produits agricoles** avec conversion configurable et **règle FIFO** (First In, First Out).
- Traçabilité complète des transactions, des dettes et des remboursements.

---

## 🌍 Environnements et accès

| Environnement | URL / Accès | Utilisation |
|---------------|-------------|--------------|
| **Production** | [`https://farmers-market-backend-production-9194.up.railway.app`](https://farmers-market-backend-production-9194.up.railway.app) | API déployée sur Railway, accessible publiquement. |
| **Comptes de test** | **Admin** : `admin@example.com` / `password` <br> **Superviseur** : `super@example.com` / `password` <br> **Opérateur** : `operator@example.com` / `password` | Tous les rôles sont préconfigurés dans le seeder. |
| **Documentation** | Collection Postman intégrée | Fichier  [`Farmers Market API.postman_collection.json`](./docs/Farmers%20Market%20API.postman_collection.json) à importer. |

> **Note :** Le compte **Admin** peut créer des Superviseurs. Le compte **Superviseur** peut créer des Opérateurs et gérer le catalogue. L'**Opérateur** ne peut qu'effectuer des transactions et des remboursements via l'application mobile.

---

## 📦 Prérequis techniques

- PHP 8.4 ou supérieur
- Composer v2
- MySQL 8.0+ ou PostgreSQL 15+
- Git

---

## 🚀 Installation locale

```bash
# 1. Cloner le dépôt
git clone https://github.com/Doumbia07/farmers-market-backend.git
cd farmers-market-backend

# 2. Installer les dépendances PHP
composer install --ignore-platform-req=php

# 3. Configurer l'environnement (créer .env depuis .env.example)
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données (mettre à jour .env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmers_market
DB_USERNAME=root
DB_PASSWORD=

# 5. Lancer les migrations et le seeder (créé les comptes par défaut)
php artisan migrate --seed

# 6. Démarrer le serveur de développement
php artisan serve
