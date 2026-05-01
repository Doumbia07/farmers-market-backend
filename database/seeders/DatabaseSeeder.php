<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // 1. Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_by' => null,
        ]);

        // 2. Superviseur (créé par l'admin)
        $supervisor = User::create([
            'name' => 'Superviseur Principal',
            'email' => 'super@example.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'created_by' => $admin->id,
        ]);

        // 3. Opérateur (créé par le superviseur)
        $operator = User::create([
            'name' => 'Opérateur POS',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'created_by' => $supervisor->id,
        ]);

        // 4. Catégories (2 niveaux)
        $pesticides = Category::create(['name' => 'Pesticides', 'parent_id' => null]);
        $herbicides = Category::create(['name' => 'Herbicides', 'parent_id' => $pesticides->id]);
        $insecticides = Category::create(['name' => 'Insecticides', 'parent_id' => $pesticides->id]);

        $fertilizers = Category::create(['name' => 'Engrais', 'parent_id' => null]);
        $npk = Category::create(['name' => 'NPK', 'parent_id' => $fertilizers->id]);
        $urea = Category::create(['name' => 'Urée', 'parent_id' => $fertilizers->id]);

        // 5. Produits
        Product::create([
            'name' => 'Roundup 1L',
            'description' => 'Herbicide glyphosate',
            'price_fcfa' => 5000,
            'category_id' => $herbicides->id,
        ]);
        Product::create([
            'name' => 'Confidor 200ml',
            'description' => 'Insecticide systémique',
            'price_fcfa' => 3500,
            'category_id' => $insecticides->id,
        ]);
        Product::create([
            'name' => 'Engrais NPK 15-15-15 50kg',
            'description' => 'Engrais complexe',
            'price_fcfa' => 25000,
            'category_id' => $npk->id,
        ]);
        Product::create([
            'name' => 'Urée 50kg',
            'description' => 'Engrais azoté',
            'price_fcfa' => 22000,
            'category_id' => $urea->id,
        ]);

        // 6. Agriculteur
        Farmer::create([
            'identifier' => 'FAR-001',
            'firstname' => 'Kouadio',
            'lastname' => 'Konan',
            'phone' => '0708091011',
            'credit_limit' => 100000,
        ]);

        Farmer::create([
            'identifier' => 'FAR-002',
            'firstname' => 'Aminata',
            'lastname' => 'Diallo',
            'phone' => '0711223344',
            'credit_limit' => 50000,
        ]);

        echo "✅ Seeder exécuté avec succès.\n";
    }
}
