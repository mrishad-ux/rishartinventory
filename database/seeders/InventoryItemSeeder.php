<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Shawarma Marination
            ['name' => 'Shawarma Chicken', 'category' => 'shawarma_marination', 'unit' => 'pcs', 'minimum_stock' => 500],
            ['name' => 'Shawarma LB', 'category' => 'shawarma_marination', 'unit' => 'pcs', 'minimum_stock' => 300],
            ['name' => 'Shawarma PP', 'category' => 'shawarma_marination', 'unit' => 'pcs', 'minimum_stock' => 100],
            ['name' => 'Shawarma MED TT', 'category' => 'shawarma_marination', 'unit' => 'pcs', 'minimum_stock' => 100],
            ['name' => 'Zinger', 'category' => 'shawarma_marination', 'unit' => 'pcs', 'minimum_stock' => 200],

            // Mayo, Masala & Sauces
            ['name' => 'Eggless Mayo', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 10],
            ['name' => 'LB Sauce', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 5],
            ['name' => 'MX Sauce', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 5],
            ['name' => 'Burger Spicy', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 3],
            ['name' => 'Burger Sweet', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 3],
            ['name' => 'Peri Peri Sauce', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 2],
            ['name' => 'Peri Peri Masala', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 2],
            ['name' => 'B Powder', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 2],
            ['name' => 'T Powder', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 1],
            ['name' => 'Zinger Masala', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 3],
            ['name' => 'Zinger Powder', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'minimum_stock' => 2],

            // Chicken & Fish
            ['name' => 'Chilled Chicken', 'category' => 'chicken_fish', 'unit' => 'kg', 'minimum_stock' => 50],
            ['name' => 'Frozen Chicken', 'category' => 'chicken_fish', 'unit' => 'kg', 'minimum_stock' => 20],
            ['name' => 'Fish', 'category' => 'chicken_fish', 'unit' => 'kg', 'minimum_stock' => 10],

            // Bun, Bakery & Grocery
            ['name' => 'Rumali', 'category' => 'bun_bakery', 'unit' => 'pcs', 'minimum_stock' => 50],
            ['name' => 'Khubz', 'category' => 'bun_bakery', 'unit' => 'pcs', 'minimum_stock' => 20],

            // Other
            ['name' => 'Sunflower Oil', 'category' => 'other', 'unit' => 'L', 'minimum_stock' => 10],
            ['name' => 'Palm Oil', 'category' => 'other', 'unit' => 'L', 'minimum_stock' => 10],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
