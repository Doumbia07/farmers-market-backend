# Farmers Market API - Backend

Plateforme de marketplace pour produits agricoles en Côte d'Ivoire.  
API REST développée avec **Laravel 13** et **PHP 8.3+**.

## Fonctionnalités

- Authentification via Laravel Sanctum (token)
- Gestion des utilisateurs avec rôles : Admin, Superviseur, Opérateur
- Catalogue de produits (catégories imbriquées, CRUD)
- Gestion des agriculteurs (identifiant, téléphone, limite de crédit)
- Transactions : paiement cash ou crédit (avec intérêt configurable)
- Remboursement FIFO (First In, First Out) avec produits agricoles
- Endpoint pour les transactions récentes
- Sécurité : middleware `role` et validation des requêtes

## Prérequis

- PHP 8.3+
- Composer
- MySQL / MariaDB
- Laravel 13

## Installation

1. Cloner le dépôt  
   ```bash
   git clone https://github.com/Doumbia07/farmers-market-backend.git
   cd farmers-market-backend
