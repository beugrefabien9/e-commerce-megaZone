<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create categories
        $categories = [
            ['name' => 'Électronique', 'description' => 'Téléphones, ordinateurs et gadgets électroniques'],
            ['name' => 'Vêtements', 'description' => 'Mode et accessoires'],
            ['name' => 'Maison & Jardin', 'description' => 'Articles pour la maison et le jardin'],
            ['name' => 'Sports & Loisirs', 'description' => 'Équipements sportifs et loisirs'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create subcategories
        $subCategories = [
            ['name' => 'Smartphones', 'category_id' => 1, 'description' => 'Téléphones intelligents'],
            ['name' => 'Ordinateurs portables', 'category_id' => 1, 'description' => 'PC portables et ultrabooks'],
            ['name' => 'T-shirts', 'category_id' => 2, 'description' => 'T-shirts et polos'],
            ['name' => 'Chaussures', 'category_id' => 2, 'description' => 'Chaussures de sport et habillées'],
            ['name' => 'Meubles', 'category_id' => 3, 'description' => 'Mobilier pour la maison'],
            ['name' => 'Décoration', 'category_id' => 3, 'description' => 'Objets décoratifs'],
            ['name' => 'Fitness', 'category_id' => 4, 'description' => 'Équipements de fitness'],
            ['name' => 'Sports d\'extérieur', 'category_id' => 4, 'description' => 'Équipements pour sports extérieurs'],
        ];

        foreach ($subCategories as $subCategoryData) {
            SubCategory::create($subCategoryData);
        }

        // Create products
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Le dernier iPhone avec des fonctionnalités avancées et un design élégant.',
                'price' => 779994,
                'sale_price' => 714994,
                'stock_quantity' => 50,
                'sku' => 'IPH15P-128',
                'category_id' => 1,
                'sub_category_id' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'MacBook Air M3',
                'description' => 'Ordinateur portable ultra-fin avec la puce M3 pour des performances exceptionnelles.',
                'price' => 974994,
                'stock_quantity' => 30,
                'sku' => 'MBA-M3-13',
                'category_id' => 1,
                'sub_category_id' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'T-shirt Cotton Bio',
                'description' => 'T-shirt confortable en coton biologique, parfait pour un look casual.',
                'price' => 19494,
                'sale_price' => 16244,
                'stock_quantity' => 100,
                'sku' => 'TSH-BIO-WHT',
                'category_id' => 2,
                'sub_category_id' => 3,
            ],
            [
                'name' => 'Nike Air Max',
                'description' => 'Chaussures de sport iconiques avec amorti Air Max pour le confort ultime.',
                'price' => 97494,
                'stock_quantity' => 75,
                'sku' => 'NAM-270-BLK',
                'category_id' => 2,
                'sub_category_id' => 4,
                'is_featured' => true,
            ],
            [
                'name' => 'Canapé 3 places',
                'description' => 'Canapé confortable en tissu, idéal pour le salon.',
                'price' => 899.99,
                'stock_quantity' => 10,
                'sku' => 'CAN-3P-GRY',
                'category_id' => 3,
                'sub_category_id' => 5,
            ],
            [
                'name' => 'Lampe de bureau LED',
                'description' => 'Lampe de bureau moderne avec éclairage LED réglable.',
                'price' => 79.99,
                'sale_price' => 69.99,
                'stock_quantity' => 40,
                'sku' => 'LAMP-LED-BLK',
                'category_id' => 3,
                'sub_category_id' => 6,
            ],
            [
                'name' => 'Haltères ajustables',
                'description' => 'Set d\'haltères ajustables de 5 à 50 kg pour vos séances de musculation.',
                'price' => 199.99,
                'stock_quantity' => 25,
                'sku' => 'HALT-AJUST-50',
                'category_id' => 4,
                'sub_category_id' => 7,
            ],
            [
                'name' => 'Vélo de route carbone',
                'description' => 'Vélo de route léger en carbone avec groupe Shimano 105.',
                'price' => 2499.99,
                'stock_quantity' => 8,
                'sku' => 'VELO-RTE-CRB',
                'category_id' => 4,
                'sub_category_id' => 8,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
