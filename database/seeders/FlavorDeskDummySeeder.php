<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FlavorDeskDummySeeder extends Seeder
{
    /**
     * Lord of Wraps — FlavorDesk Dummy Data Seeder
     * Covers: inventory_items, inventory_logs (15 days), sales, expenses
     * Run: php artisan db:seed --class=FlavorDeskDummySeeder
     */
    public function run(): void
    {
        // ----------------------------------------------------------------
        // SAFETY: truncate in correct FK order
        // ----------------------------------------------------------------
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inventory_logs')->truncate();
        DB::table('inventory_items')->truncate();
        DB::table('sales')->truncate();
        DB::table('expenses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Tables truncated.');

        // ----------------------------------------------------------------
        // 1. INVENTORY ITEMS
        // ----------------------------------------------------------------
        $now = now();

        $items = [
            // --- Marination ---
            ['name' => 'Shawarma',      'category' => 'shawarma_marination',         'unit' => 'gms',  'sort_order' => 1],
            ['name' => 'Lebanese',      'category' => 'shawarma_marination',         'unit' => 'gms',  'sort_order' => 2],
            ['name' => 'Peri Peri',     'category' => 'shawarma_marination',         'unit' => 'gms',  'sort_order' => 3],
            ['name' => 'Medeterranean', 'category' => 'shawarma_marination',         'unit' => 'gms',  'sort_order' => 4],
            ['name' => 'Zinger',        'category' => 'shawarma_marination',         'unit' => 'pcs',  'sort_order' => 5],

            // --- Mayo, Masala & Sauces ---
            ['name' => 'B Powder',        'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 10],
            ['name' => 'Burger Spicy',    'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 11],
            ['name' => 'Burger Sweet',    'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 12],
            ['name' => 'Eggless Mayo',    'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 13],
            ['name' => 'LB Sauce',        'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 14],
            ['name' => 'MX Sauce',        'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 15],
            ['name' => 'Peri Peri Masala','category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 16],
            ['name' => 'Peri Peri Sauce', 'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 17],
            ['name' => 'T Powder',        'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 18],
            ['name' => 'Zinger Masala',   'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 19],
            ['name' => 'Zinger Powder',   'category' => 'mayo_masala_sauces', 'unit' => 'kg', 'sort_order' => 20],

            // --- Chicken & Fish ---
            ['name' => 'Chilled Chicken', 'category' => 'chicken_fish', 'unit' => 'kg', 'sort_order' => 30],
            ['name' => 'Fish',            'category' => 'chicken_fish', 'unit' => 'kg', 'sort_order' => 31],
            ['name' => 'Frozen Chicken',  'category' => 'chicken_fish', 'unit' => 'kg', 'sort_order' => 32],

            // --- Bun, Bakery & Grocery ---
            ['name' => 'Khubz',  'category' => 'bun_bakery', 'unit' => 'pcs', 'sort_order' => 40],
            ['name' => 'Rumali', 'category' => 'bun_bakery', 'unit' => 'pcs', 'sort_order' => 41],

            // --- Other ---
            ['name' => 'Palm Oil',      'category' => 'other', 'unit' => 'L', 'sort_order' => 50],
            ['name' => 'Sunflower Oil', 'category' => 'other', 'unit' => 'L', 'sort_order' => 51],
        ];

        foreach ($items as &$item) {
            $item['current_stock']  = 0;
            $item['minimum_stock']  = 0;
            $item['unit_price']     = 0;
            $item['is_mayo']        = false;
            $item['supplier_id']    = null;
            $item['created_at']     = $now;
            $item['updated_at']     = $now;
        }
        unset($item);

        DB::table('inventory_items')->insert($items);
        $this->command->info('Inventory items inserted: ' . count($items));

        // ----------------------------------------------------------------
        // 2. INVENTORY LOGS — 15 days, carry-forward respected
        // ----------------------------------------------------------------

        // Realistic opening stock per item (Day 1 seed values)
        // Unit: gms items are in grams, kg items in kg, pcs in pieces, L in litres
        $openingStock = [
            'Shawarma'       => 500,
            'Lebanese'       => 300,
            'Peri Peri'      => 200,
            'Medeterranean'  => 150,
            'Zinger'         => 100,   // pcs

            'B Powder'        => 2.5,
            'Burger Spicy'    => 1.5,
            'Burger Sweet'    => 1.5,
            'Eggless Mayo'    => 3.0,
            'LB Sauce'        => 2.0,
            'MX Sauce'        => 2.0,
            'Peri Peri Masala'=> 1.0,
            'Peri Peri Sauce' => 1.5,
            'T Powder'        => 1.0,
            'Zinger Masala'   => 1.0,
            'Zinger Powder'   => 1.0,

            'Chilled Chicken' => 20,
            'Fish'            => 5,
            'Frozen Chicken'  => 15,

            'Khubz'  => 200,  // pcs
            'Rumali' => 150,  // pcs

            'Palm Oil'      => 10,  // L
            'Sunflower Oil' => 8,   // L
        ];

        // Daily purchase amounts (typical restock, 0 = not restocked that day)
        // Varies by day to simulate realistic patterns
        $purchasePattern = [
            'Shawarma'        => [0,13000,0,0,12000,0,0,14000,0,0,12500,0,0,13500,0],
            'Lebanese'        => [0,5500,0,0,5000,0,0,6000,0,0,5500,0,0,5000,0],
            'Peri Peri'       => [0,2900,0,0,3000,0,0,2800,0,0,3000,0,0,2900,0],
            'Medeterranean'   => [0,1800,0,0,2000,0,0,1800,0,0,1900,0,0,2000,0],
            'Zinger'          => [0,0,0,500,0,0,500,0,0,500,0,0,500,0,0],

            'B Powder'        => [0,0,1,0,0,1,0,0,1,0,0,1,0,0,1],
            'Burger Spicy'    => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'Burger Sweet'    => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'Eggless Mayo'    => [0,2,0,0,2,0,0,2,0,0,2,0,0,2,0],
            'LB Sauce'        => [0,0,1,0,0,1,0,0,1,0,0,1,0,0,1],
            'MX Sauce'        => [0,0,1,0,0,1,0,0,1,0,0,1,0,0,1],
            'Peri Peri Masala'=> [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'Peri Peri Sauce' => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'T Powder'        => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'Zinger Masala'   => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],
            'Zinger Powder'   => [0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5,0,0,0.5],

            'Chilled Chicken' => [0,15,0,15,0,15,0,15,0,15,0,15,0,15,0],
            'Fish'            => [0,0,5,0,0,5,0,0,5,0,0,5,0,0,5],
            'Frozen Chicken'  => [0,10,0,0,12,0,0,10,0,0,12,0,0,10,0],

            'Khubz'  => [100,100,100,100,100,100,100,100,100,100,100,100,100,100,100],
            'Rumali' => [50,50,50,50,50,50,50,50,50,50,50,50,50,50,50],

            'Palm Oil'      => [0,0,5,0,0,5,0,0,5,0,0,5,0,0,5],
            'Sunflower Oil' => [0,0,4,0,0,4,0,0,4,0,0,4,0,0,4],
        ];

        // Daily consumption (realistic based on a busy shawarma QSR)
        $consumptionPattern = [
            'Shawarma'        => [400,450,380,420,500,460,480,440,420,470,490,410,430,460,450],
            'Lebanese'        => [200,220,180,210,250,230,240,220,200,230,250,200,210,230,220],
            'Peri Peri'       => [80,90,75,85,100,95,90,85,80,90,95,80,85,90,88],
            'Medeterranean'   => [60,70,55,65,80,75,70,65,60,70,75,60,65,70,68],
            'Zinger'          => [30,35,28,32,40,38,35,32,30,35,38,30,32,35,33],

            'B Powder'        => [0.08,0.09,0.08,0.09,0.10,0.09,0.09,0.08,0.08,0.09,0.10,0.08,0.09,0.09,0.09],
            'Burger Spicy'    => [0.05,0.06,0.05,0.05,0.07,0.06,0.06,0.05,0.05,0.06,0.07,0.05,0.06,0.06,0.05],
            'Burger Sweet'    => [0.05,0.06,0.05,0.05,0.07,0.06,0.06,0.05,0.05,0.06,0.07,0.05,0.06,0.06,0.05],
            'Eggless Mayo'    => [0.15,0.18,0.14,0.16,0.20,0.18,0.17,0.16,0.15,0.18,0.20,0.15,0.16,0.18,0.17],
            'LB Sauce'        => [0.08,0.09,0.08,0.09,0.10,0.09,0.09,0.08,0.08,0.09,0.10,0.08,0.09,0.09,0.09],
            'MX Sauce'        => [0.08,0.09,0.08,0.09,0.10,0.09,0.09,0.08,0.08,0.09,0.10,0.08,0.09,0.09,0.09],
            'Peri Peri Masala'=> [0.04,0.05,0.04,0.04,0.05,0.05,0.05,0.04,0.04,0.05,0.05,0.04,0.04,0.05,0.05],
            'Peri Peri Sauce' => [0.05,0.06,0.05,0.05,0.07,0.06,0.06,0.05,0.05,0.06,0.07,0.05,0.06,0.06,0.05],
            'T Powder'        => [0.04,0.05,0.04,0.04,0.05,0.05,0.05,0.04,0.04,0.05,0.05,0.04,0.04,0.05,0.05],
            'Zinger Masala'   => [0.04,0.05,0.04,0.04,0.05,0.05,0.05,0.04,0.04,0.05,0.05,0.04,0.04,0.05,0.05],
            'Zinger Powder'   => [0.04,0.05,0.04,0.04,0.05,0.05,0.05,0.04,0.04,0.05,0.05,0.04,0.04,0.05,0.05],

            'Chilled Chicken' => [4,5,4,5,6,5,5,4,4,5,6,4,5,5,5],
            'Fish'            => [1,1.5,1,1.5,2,1.5,1.5,1,1,1.5,2,1,1.5,1.5,1.5],
            'Frozen Chicken'  => [3,4,3,4,5,4,4,3,3,4,5,3,4,4,4],

            'Khubz'  => [70,80,65,75,90,85,80,75,70,80,90,70,75,80,78],
            'Rumali' => [40,45,38,42,50,48,45,42,40,45,50,40,42,45,43],

            'Palm Oil'      => [0.5,0.6,0.5,0.6,0.7,0.6,0.6,0.5,0.5,0.6,0.7,0.5,0.6,0.6,0.6],
            'Sunflower Oil' => [0.4,0.5,0.4,0.5,0.6,0.5,0.5,0.4,0.4,0.5,0.6,0.4,0.5,0.5,0.5],
        ];

        // Wastage — occasional, small amounts
        $wastagePattern = [
            'Shawarma'        => [0,0,20,0,0,0,30,0,0,0,20,0,0,0,0],
            'Lebanese'        => [0,0,10,0,0,0,15,0,0,0,10,0,0,0,0],
            'Peri Peri'       => [0,0,5,0,0,0,8,0,0,0,5,0,0,0,0],
            'Medeterranean'   => [0,0,5,0,0,0,5,0,0,0,5,0,0,0,0],
            'Zinger'          => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],

            'B Powder'        => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Burger Spicy'    => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Burger Sweet'    => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Eggless Mayo'    => [0,0,0.1,0,0,0,0.1,0,0,0,0.1,0,0,0,0],
            'LB Sauce'        => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'MX Sauce'        => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Peri Peri Masala'=> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Peri Peri Sauce' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'T Powder'        => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Zinger Masala'   => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Zinger Powder'   => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],

            'Chilled Chicken' => [0,0,0.5,0,0,0,0.5,0,0,0,0.5,0,0,0,0],
            'Fish'            => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Frozen Chicken'  => [0,0,0,0,0,0,0.5,0,0,0,0,0,0,0,0],

            'Khubz'  => [0,0,5,0,0,0,5,0,0,0,5,0,0,0,0],
            'Rumali' => [0,0,3,0,0,0,3,0,0,0,3,0,0,0,0],

            'Palm Oil'      => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            'Sunflower Oil' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        ];

        // Fetch inserted items keyed by name
        $dbItems = DB::table('inventory_items')->get()->keyBy('name');

        $startDate = Carbon::now()->subDays(14)->startOfDay(); // 15 days ending today
        $logs = [];

        foreach ($dbItems as $name => $dbItem) {
            $runningStock = $openingStock[$name] ?? 0;

            for ($day = 0; $day < 15; $day++) {
                $logDate    = $startDate->copy()->addDays($day)->toDateString();
                $purchased  = round($purchasePattern[$name][$day] ?? 0, 2);
                $consumption= round($consumptionPattern[$name][$day] ?? 0, 2);
                $wastage    = round($wastagePattern[$name][$day] ?? 0, 2);
                $opening    = round($runningStock, 2);

                // closing = opening + purchased - consumption - wastage
                // (mirrors the storedAs formula in the migration)
                $closing = round($opening + $purchased - $consumption - $wastage, 2);
                // Clamp to 0 — no negative stock
                $closing = max(0, $closing);

                $openingSource = ($day === 0) ? 'manual' : 'auto';

                $logs[] = [
                    'inventory_item_id' => $dbItem->id,
                    'log_date'          => $logDate,
                    'opening'           => $opening,
                    'opening_source'    => $openingSource,
                    'purchased'         => $purchased,
                    'consumption'       => $consumption,
                    'wastage'           => $wastage,
                    // 'total' and 'closing' are storedAs computed columns — DO NOT INSERT
                    'gas_changed'       => false,
                    'electricity_reading'=> null,
                    'notes'             => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                // Next day's opening = today's closing
                $runningStock = $closing;
            }
        }

        // Insert in chunks to avoid packet size issues
        foreach (array_chunk($logs, 100) as $chunk) {
            DB::table('inventory_logs')->insert($chunk);
        }
        $this->command->info('Inventory logs inserted: ' . count($logs));

        // ----------------------------------------------------------------
        // 3. SALES — 15 days, 3 entries per day (cash, GP, online)
        // ----------------------------------------------------------------
        $salesLogs = [];
        $platforms  = ['Zomato', 'Swiggy'];
        $gpCommission = 1.5;   // % for GP / card
        $onlineCommission = 18; // % for aggregators

        for ($day = 0; $day < 15; $day++) {
            $saleDate = $startDate->copy()->addDays($day)->toDateString();

            // Cash
            $cashGross = round(rand(2800, 4500) + rand(0, 99) / 100, 2);
            $salesLogs[] = [
                'sale_date'                => $saleDate,
                'sale_type'                => 'cash',
                'platform'                 => null,
                'gross_amount'             => $cashGross,
                'commission_percent'       => 0,
                'commission_amount'        => 0,
                'net_amount'               => $cashGross,
                'settlement_status'        => 'not_applicable',
                'expected_settlement_date' => null,
                'actual_settlement_date'   => null,
                'notes'                    => null,
                'created_at'               => $now,
                'updated_at'               => $now,
            ];

            // GP (card/UPI)
            $gpGross = round(rand(800, 2000) + rand(0, 99) / 100, 2);
            $gpComAmt = round($gpGross * $gpCommission / 100, 2);
            $salesLogs[] = [
                'sale_date'                => $saleDate,
                'sale_type'                => 'gp',
                'platform'                 => 'GPay / Card',
                'gross_amount'             => $gpGross,
                'commission_percent'       => $gpCommission,
                'commission_amount'        => $gpComAmt,
                'net_amount'               => round($gpGross - $gpComAmt, 2),
                'settlement_status'        => ($day < 13) ? 'received' : 'pending',
                'expected_settlement_date' => Carbon::parse($saleDate)->addDays(2)->toDateString(),
                'actual_settlement_date'   => ($day < 13)
                    ? Carbon::parse($saleDate)->addDays(2)->toDateString()
                    : null,
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Online aggregator (Zomato / Swiggy alternating)
            $platform    = $platforms[$day % 2];
            $onlineGross = round(rand(1200, 3000) + rand(0, 99) / 100, 2);
            $onlineComAmt= round($onlineGross * $onlineCommission / 100, 2);
            $salesLogs[] = [
                'sale_date'                => $saleDate,
                'sale_type'                => 'online',
                'platform'                 => $platform,
                'gross_amount'             => $onlineGross,
                'commission_percent'       => $onlineCommission,
                'commission_amount'        => $onlineComAmt,
                'net_amount'               => round($onlineGross - $onlineComAmt, 2),
                'settlement_status'        => ($day < 8) ? 'received' : 'pending',
                'expected_settlement_date' => Carbon::parse($saleDate)->addDays(7)->toDateString(),
                'actual_settlement_date'   => ($day < 8)
                    ? Carbon::parse($saleDate)->addDays(7)->toDateString()
                    : null,
                'notes'      => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('sales')->insert($salesLogs);
        $this->command->info('Sales inserted: ' . count($salesLogs));

        // ----------------------------------------------------------------
        // 4. EXPENSES — 15 days, realistic QSR categories
        // ----------------------------------------------------------------
        $expenseTemplates = [
            // [title, category, amount_min, amount_max, payment_type, status]
            ['Chicken Purchase',    'raw_material',  3500, 5500, 'cash',   'paid'],
            ['Marination Masala',   'raw_material',  800,  1500, 'cash',   'paid'],
            ['Packaging Material',  'packaging',     400,  800,  'cash',   'paid'],
            ['Staff Meal',          'staff',         200,  400,  'cash',   'paid'],
            ['Gas Cylinder',        'utilities',     900,  1100, 'cash',   'paid'],
            ['Cleaning Supplies',   'maintenance',   150,  350,  'cash',   'paid'],
            ['Bread / Khubz Order', 'raw_material',  600,  1000, 'cash',   'paid'],
            ['Electricity Bill',    'utilities',     1800, 2500, 'credit', 'unpaid'],
            ['Zomato Commission',   'platform_fee',  800,  1800, 'credit', 'unpaid'],
            ['Swiggy Commission',   'platform_fee',  600,  1400, 'credit', 'unpaid'],
        ];

        $expenseLogs = [];

        for ($day = 0; $day < 15; $day++) {
            $expDate = $startDate->copy()->addDays($day)->toDateString();

            // 2–4 expenses per day, picked randomly from templates
            $count = rand(2, 4);
            $picked = array_rand($expenseTemplates, $count);
            if (!is_array($picked)) $picked = [$picked];

            foreach ($picked as $idx) {
                [$title, $category, $min, $max, $payType, $status] = $expenseTemplates[$idx];
                $amount = round(rand($min * 100, $max * 100) / 100, 2);

                $expenseLogs[] = [
                    'title'        => $title,
                    'category'     => $category,
                    'amount'       => $amount,
                    'expense_date' => $expDate,
                    'payment_type' => $payType,
                    'status'       => $status,
                    'due_date'     => ($status === 'unpaid')
                        ? Carbon::parse($expDate)->addDays(7)->toDateString()
                        : null,
                    'supplier_id'  => null,
                    'notes'        => null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        DB::table('expenses')->insert($expenseLogs);
        $this->command->info('Expenses inserted: ' . count($expenseLogs));

        $this->command->info('');
        $this->command->info('FlavorDeskDummySeeder complete.');
        $this->command->info('Next: php artisan db:seed --class=FlavorDeskDummySeeder');
    }
}
