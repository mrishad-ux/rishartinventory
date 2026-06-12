<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryLog;
use Illuminate\Database\Seeder;

class InventoryLogSeeder extends Seeder
{
    public function run(): void
    {
        $items = InventoryItem::all();

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->subDays(1)->startOfDay();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            foreach ($items as $item) {
                $logDate = $date->toDateString();

                $prevLog = InventoryLog::where('inventory_item_id', $item->id)
                    ->whereDate('log_date', '<', $logDate)
                    ->orderBy('log_date', 'desc')
                    ->first();

                $opening = $prevLog ? $prevLog->closing : 0;

                $purchased = $this->getPurchasedValue($item->name);
                $wastage = $this->getWastageValue($item->name);
                $closing = $this->getClosingValue($item->name, $opening, $purchased, $wastage);

                $consumption = $opening + $purchased - $wastage - $closing;

                InventoryLog::updateOrCreate(
                    ['inventory_item_id' => $item->id, 'log_date' => $logDate],
                    [
                        'opening' => $opening,
                        'purchased' => $purchased,
                        'wastage' => $wastage,
                        'closing' => $closing,
                        'consumption' => $consumption,
                    ]
                );
            }
        }

        $this->updateCurrentStock($items);
    }

    private function getPurchasedValue(string $itemName): float
    {
        $name = strtoupper($itemName);

        $ranges = [
            'SHAWARMA' => [6000, 6000],
            'LB' => [4000, 5000],
            'PP' => [1500, 2000],
            'MEDTT' => [1500, 2000],
            'ZINGER' => [2000, 4000],
            'EGGLESS MAYO' => [0, 0],
            'LB SAUCE' => [500, 700],
            'MX SAUCE' => [500, 1000],
            'BURGER SPICY' => [600, 900],
            'BURGER SWEET' => [500, 1000],
            'PERI PERI SAUCE' => [0, 0],
            'PERI PERI MASALA' => [0, 400],
            'B POWDER' => [0, 200],
            'T POWDER' => [0, 100],
            'ZINGER MASALA' => [100, 300],
            'ZINGER POWDER' => [0, 200],
            'CHILLED CHKN' => [20000, 25000],
            'FROZEN CHKN' => [0, 0],
            'FROZEN FISH' => [0, 0],
            'RUMALI' => [70, 110],
            'KHUBZ' => [0, 0],
        ];

        foreach ($ranges as $key => $range) {
            if (str_contains($name, $key)) {
                return (float) rand($range[0], $range[1]);
            }
        }

        return 0;
    }

    private function getWastageValue(string $itemName): float
    {
        $name = strtoupper($itemName);

        if (str_contains($name, 'SHAWARMA')) {
            return (float) rand(80, 120);
        }

        if (str_contains($name, 'RUMALI')) {
            return (float) rand(2, 5);
        }

        return 0;
    }

    private function getClosingValue(string $itemName, float $opening, float $purchased, float $wastage): float
    {
        $name = strtoupper($itemName);
        $total = $opening + $purchased - $wastage;

        if (str_contains($name, 'SHAWARMA')) {
            return 0;
        }

        if (str_contains($name, 'FROZEN CHKN') || str_contains($name, 'FROZEN FISH') || str_contains($name, 'KHUBZ')) {
            return 0;
        }

        if (str_contains($name, 'CHILLED CHKN')) {
            return (float) rand(8000, 15000);
        }

        if (str_contains($name, 'RUMALI')) {
            return (float) rand(5, 40);
        }

        if (str_contains($name, 'LB') && str_contains($name, 'SAUCE')) {
            return (float) rand(400, 800);
        }

        if (str_contains($name, 'LB')) {
            return (float) rand(500, 1000);
        }

        if (str_contains($name, 'PP')) {
            return (float) rand(100, 700);
        }

        if (str_contains($name, 'MEDTT')) {
            return (float) rand(200, 500);
        }

        if (str_contains($name, 'ZINGER') && str_contains($name, 'MASALA')) {
            return (float) rand(70, 200);
        }

        if (str_contains($name, 'ZINGER') && str_contains($name, 'POWDER')) {
            return (float) rand(100, 250);
        }

        if (str_contains($name, 'ZINGER')) {
            return (float) rand(300, 900);
        }

        if (str_contains($name, 'EGGLESS MAYO')) {
            return (float) rand(800, 1500);
        }

        if (str_contains($name, 'MX SAUCE')) {
            return (float) rand(300, 700);
        }

        if (str_contains($name, 'BURGER SPICY')) {
            return (float) rand(300, 600);
        }

        if (str_contains($name, 'BURGER SWEET')) {
            return (float) rand(400, 800);
        }

        if (str_contains($name, 'PERI PERI SAUCE')) {
            return (float) rand(100, 300);
        }

        if (str_contains($name, 'PERI PERI MASALA')) {
            return (float) rand(100, 250);
        }

        if (str_contains($name, 'B POWDER')) {
            return (float) rand(40, 150);
        }

        if (str_contains($name, 'T POWDER')) {
            return (float) rand(50, 120);
        }

        return 0;
    }

    private function updateCurrentStock($items): void
    {
        foreach ($items as $item) {
            $latestLog = InventoryLog::where('inventory_item_id', $item->id)
                ->orderBy('log_date', 'desc')
                ->first();

            if ($latestLog) {
                $item->update(['current_stock' => $latestLog->closing]);
            }
        }
    }
}
