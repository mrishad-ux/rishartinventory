<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    // ─── ITEM MASTER ────────────────────────────────────────────────────────────

    public function index()
    {
        $items = InventoryItem::with(['logs' => function($q) {
            $q->orderBy('log_date', 'desc')->limit(1);
        }, 'supplier'])
            ->orderByRaw("FIELD(category, 'shawarma_marination','mayo_masala_sauces','chicken_fish','bun_bakery','other')")
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $categories = InventoryItem::$categories;

        return view('inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = InventoryItem::$categories;

        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        InventoryItem::create([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'current_stock' => 0,
            'minimum_stock' => $request->minimum_stock,
            'unit_price' => $request->unit_price ?? 0,
            'is_mayo' => $request->boolean('is_mayo'),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Item added successfully.');
    }

    public function edit(InventoryItem $inventory)
    {
        $categories = InventoryItem::$categories;

        return view('inventory.edit', compact('inventory', 'categories'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $inventory->update([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock,
            'unit_price' => $request->unit_price ?? 0,
            'is_mayo' => $request->boolean('is_mayo'),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Item updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Item deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['items' => 'required|array']);
        foreach ($request->items as $index => $id) {
            InventoryItem::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function saveGas(Request $request)
    {
        $date = $request->date ?? today()->toDateString();
        $gasChanged = $request->gas_changed;

        // Update gas_changed on ALL logs for this date
        InventoryLog::whereDate('log_date', $date)
            ->update(['gas_changed' => $gasChanged]);

        // If no logs exist yet for today, store in a temporary session
        session(["gas_changed_{$date}" => $gasChanged]);

        return response()->json(['success' => true, 'gas_changed' => $gasChanged]);
    }

    public function saveElectricity(Request $request)
    {
        $request->validate([
            'electricity_reading' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Update all logs for this date with the reading
        InventoryLog::whereDate('log_date', $request->date)
            ->update(['electricity_reading' => $request->electricity_reading]);

        // Always store in session to apply to newly created logs
        session(["electricity_reading_{$request->date}" => $request->electricity_reading]);

        // Recalculate units consumed
        $prev = InventoryLog::whereNotNull('electricity_reading')
            ->whereDate('log_date', '<', $request->date)
            ->orderBy('log_date', 'desc')
            ->value('electricity_reading');

        $units = $prev ? round($request->electricity_reading - $prev, 2) : null;

        return response()->json([
            'success' => true,
            'units_consumed' => $units,
            'prev_reading' => $prev,
        ]);
    }

    /**
     * Save oil consumption data for a given date.
     */
    public function saveOil(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'oil_l1_packets' => 'nullable|numeric|min:0',
            'oil_l2_packets' => 'nullable|numeric|min:0',
            'oil_r1_packets' => 'nullable|numeric|min:0',
            'oil_r2_packets' => 'nullable|numeric|min:0',
            'oil_mayo_packets' => 'nullable|numeric|min:0',
            'oil_sauces_packets' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'oil_l1_packets' => $request->oil_l1_packets ?? 0,
            'oil_l2_packets' => $request->oil_l2_packets ?? 0,
            'oil_r1_packets' => $request->oil_r1_packets ?? 0,
            'oil_r2_packets' => $request->oil_r2_packets ?? 0,
            'oil_mayo_packets' => $request->oil_mayo_packets ?? 0,
            'oil_sauces_packets' => $request->oil_sauces_packets ?? 0,
        ];

        // ── Stock validation ──────────────────────────────────────────────────
        $fryerTotal = ($data['oil_l1_packets'] ?? 0) + ($data['oil_l2_packets'] ?? 0) + ($data['oil_r1_packets'] ?? 0) + ($data['oil_r2_packets'] ?? 0);
        $condimentTotal = ($data['oil_mayo_packets'] ?? 0) + ($data['oil_sauces_packets'] ?? 0);
        $warnings = [];

        if ($fryerTotal > 0) {
            $palmOil = \App\Models\InventoryItem::where('id', 30)->first();
            if ($palmOil && $palmOil->current_stock < $fryerTotal) {
                $warnings[] = "⚠️ {$palmOil->name} stock is only {$palmOil->current_stock} {$palmOil->unit}, but you're recording {$fryerTotal} fryer packets total. Did you forget to log a purchase?";
            }
        }

        if ($condimentTotal > 0) {
            $sunflowerOil = \App\Models\InventoryItem::where('id', 29)->first();
            if ($sunflowerOil && $sunflowerOil->current_stock < $condimentTotal) {
                $warnings[] = "⚠️ {$sunflowerOil->name} stock is only {$sunflowerOil->current_stock} {$sunflowerOil->unit}, but you're recording {$condimentTotal} condiment packets total. Did you forget to log a purchase?";
            }
        }

        // ── Deduped single-row write ────────────────────────────────────────────
        // First, clear oil data from ALL rows for this date (prevent duplication)
        InventoryLog::whereDate('log_date', $request->date)
            ->update([
                'oil_l1_packets' => null,
                'oil_l2_packets' => null,
                'oil_r1_packets' => null,
                'oil_r2_packets' => null,
                'oil_mayo_packets' => null,
                'oil_sauces_packets' => null,
            ]);

        // Then set data on exactly ONE row (the first inventory log for this date)
        $targetLog = InventoryLog::whereDate('log_date', $request->date)
            ->orderBy('id')
            ->first();

        if ($targetLog) {
            $targetLog->update($data);
        }

        // Store in session for newly created logs
        foreach ($data as $key => $value) {
            session(["oil_{$key}_{$request->date}" => $value]);
        }

        return response()->json([
            'success' => true,
            'oil_l1_packets' => $data['oil_l1_packets'],
            'oil_l2_packets' => $data['oil_l2_packets'],
            'oil_r1_packets' => $data['oil_r1_packets'],
            'oil_r2_packets' => $data['oil_r2_packets'],
            'oil_mayo_packets' => $data['oil_mayo_packets'],
            'oil_sauces_packets' => $data['oil_sauces_packets'],
            'warnings' => $warnings,
        ]);
    }

    /**
     * Return per-day oil detail for a given month (AJAX).
     */
    public function oilMonthlyDetail(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);
        $monthStart = $request->month . '-01';
        $monthEnd = \Carbon\Carbon::parse($monthStart)->endOfMonth()->toDateString();

        $rows = InventoryLog::whereBetween('log_date', [$monthStart, $monthEnd])
            ->where(function ($q) {
                $q->whereNotNull('oil_l1_packets')
                  ->orWhereNotNull('oil_l2_packets')
                  ->orWhereNotNull('oil_r1_packets')
                  ->orWhereNotNull('oil_r2_packets')
                  ->orWhereNotNull('oil_mayo_packets')
                  ->orWhereNotNull('oil_sauces_packets');
            })
            ->selectRaw('log_date, MAX(COALESCE(oil_l1_packets,0)) as l1, MAX(COALESCE(oil_l2_packets,0)) as l2, MAX(COALESCE(oil_r1_packets,0)) as r1, MAX(COALESCE(oil_r2_packets,0)) as r2, MAX(COALESCE(oil_mayo_packets,0)) as mayo, MAX(COALESCE(oil_sauces_packets,0)) as sauces')
            ->groupBy('log_date')
            ->orderBy('log_date')
            ->get();

        $data = $rows->map(function ($row) {
            $total = ($row->l1 ?? 0) + ($row->l2 ?? 0) + ($row->r1 ?? 0) + ($row->r2 ?? 0);
            $saucesTotal = ($row->mayo ?? 0) + ($row->sauces ?? 0);
            return [
                'date' => $row->log_date->format('d M Y'),
                'l1'   => (float)($row->l1 ?? 0),
                'l2'   => (float)($row->l2 ?? 0),
                'r1'   => (float)($row->r1 ?? 0),
                'r2'   => (float)($row->r2 ?? 0),
                'mayo' => (float)($row->mayo ?? 0),
                'sauces' => (float)($row->sauces ?? 0),
                'total' => $total,
                'sauces_total' => $saucesTotal,
            ];
        });

        $grandTotal = $data->sum('total');
        $saucesGrandTotal = $data->sum('sauces_total');

        return response()->json([
            'month' => $request->month,
            'days'  => $data,
            'grand_total' => $grandTotal,
            'sauces_grand_total' => $saucesGrandTotal,
        ]);
    }

    public function getLowStock()
    {
        $lowItems = InventoryItem::where('minimum_stock', '>', 0)
            ->with(['logs' => function($q) {
                $q->orderBy('log_date', 'desc')->limit(1);
            }])
            ->get()
            ->filter(function($item) {
                $latestLog = $item->logs->first();
                return $latestLog && $latestLog->closing < $item->minimum_stock;
            })
            ->map(function($item) {
                $latestLog = $item->logs->first();
                return [
                    'name'      => $item->name,
                    'unit'      => $item->unit,
                    'closing'   => $latestLog->closing,
                    'min_stock' => $item->minimum_stock,
                    'log_date'  => $latestLog->log_date->format('d M Y'),
                ];
            })
            ->values();

        return response()->json($lowItems);
    }

    public function show(InventoryItem $inventory)
    {
        $logs = $inventory->logs()->orderBy('log_date', 'desc')->paginate(30);

        return view('inventory.show', compact('inventory', 'logs'));
    }

    // ─── DAILY LOG ──────────────────────────────────────────────────────────────

    public function dailyEntry(Request $request)
    {
        $date = $request->get('date', today()->toDateString());

        $items = InventoryItem::with(['logs' => function ($q) use ($date) {
            $q->whereDate('log_date', $date);
        }])
            ->orderByRaw("FIELD(category, 'shawarma_marination','mayo_masala_sauces','chicken_fish','bun_bakery','other')")
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        foreach ($items as $item) {
            $todayLog = $item->logs->first();
            $prevLog = InventoryLog::where('inventory_item_id', $item->id)
                ->whereDate('log_date', '<', $date)
                ->orderBy('log_date', 'desc')
                ->first();

            if (! $todayLog) {
                $item->auto_opening = $prevLog ? $prevLog->closing : 0;
                $item->opening_edited = false;
                $item->is_low_stock = false;
            } else {
                if ($prevLog && $todayLog->opening != $prevLog->closing) {
                    $item->opening_edited = true;
                } else {
                    $item->opening_edited = false;
                }
                // Check if item is low stock
                $item->is_low_stock = ($item->minimum_stock > 0 && 
                                       $todayLog->closing < $item->minimum_stock);
            }
        }

        $items = $items->groupBy('category');
        $categories = InventoryItem::$categories;

        // Get gas_changed status
        $gasChanged = InventoryLog::whereDate('log_date', $date)
            ->whereNotNull('gas_changed')
            ->value('gas_changed');

        // If not in DB, check session
        if ($gasChanged === null) {
            $gasChanged = session("gas_changed_{$date}");
        }

        // Get last gas change date up to and including the selected date
        $lastGasChange = InventoryLog::where('gas_changed', true)
            ->whereDate('log_date', '<=', $date)
            ->orderBy('log_date', 'desc')
            ->value('log_date');

        $daysSinceGasChange = $lastGasChange
            ? \Carbon\Carbon::parse($lastGasChange)->diffInDays(\Carbon\Carbon::parse($date))
            : null;

        // Today's electricity reading (for selected date)
        $electricityReading = InventoryLog::whereDate('log_date', $date)
            ->whereNotNull('electricity_reading')
            ->value('electricity_reading');

        // Previous day's reading (the most recent reading before selected date)
        $prevElectricityLog = InventoryLog::whereNotNull('electricity_reading')
            ->whereDate('log_date', '<', $date)
            ->orderBy('log_date', 'desc')
            ->first();

        $prevElectricityReading = $prevElectricityLog?->electricity_reading;
        $prevElectricityDate = $prevElectricityLog?->log_date;
        $unitsConsumed = ($electricityReading && $prevElectricityReading)
            ? round($electricityReading - $prevElectricityReading, 2)
            : null;

        // Get oil consumption data for the selected date
        $oilRow = InventoryLog::whereDate('log_date', $date)
            ->whereNotNull('oil_l1_packets')
            ->first(['oil_l1_packets', 'oil_l2_packets', 'oil_r1_packets', 'oil_r2_packets', 'oil_mayo_packets', 'oil_sauces_packets']);

        $oilL1 = $oilRow?->oil_l1_packets ?? session("oil_oil_l1_packets_{$date}");
        $oilL2 = $oilRow?->oil_l2_packets ?? session("oil_oil_l2_packets_{$date}");
        $oilR1 = $oilRow?->oil_r1_packets ?? session("oil_oil_r1_packets_{$date}");
        $oilR2 = $oilRow?->oil_r2_packets ?? session("oil_oil_r2_packets_{$date}");
        $oilMayo = $oilRow?->oil_mayo_packets ?? session("oil_oil_mayo_packets_{$date}");
        $oilSauces = $oilRow?->oil_sauces_packets ?? session("oil_oil_sauces_packets_{$date}");

        // Calculate monthly total — sum all fryer packets for the current month
        // Uses GROUP BY log_date to prevent duplicates per day
        $monthStart = \Carbon\Carbon::parse($date)->startOfMonth()->toDateString();
        $monthEnd = \Carbon\Carbon::parse($date)->endOfMonth()->toDateString();
        $oilLogs = InventoryLog::whereBetween('log_date', [$monthStart, $monthEnd])
            ->where(function ($q) {
                $q->whereNotNull('oil_l1_packets')
                  ->orWhereNotNull('oil_l2_packets')
                  ->orWhereNotNull('oil_r1_packets')
                  ->orWhereNotNull('oil_r2_packets');
            })
            ->selectRaw('log_date, MAX(COALESCE(oil_l1_packets,0)) as l1, MAX(COALESCE(oil_l2_packets,0)) as l2, MAX(COALESCE(oil_r1_packets,0)) as r1, MAX(COALESCE(oil_r2_packets,0)) as r2')
            ->groupBy('log_date')
            ->get();
        $monthlyTotal = $oilLogs->sum(fn($log) => $log->l1 + $log->l2 + $log->r1 + $log->r2);

        return view('inventory.daily', compact('items', 'categories', 'date', 'gasChanged', 'lastGasChange', 'daysSinceGasChange', 'electricityReading', 'prevElectricityReading', 'prevElectricityDate', 'unitsConsumed', 'oilL1', 'oilL2', 'oilR1', 'oilR2', 'oilMayo', 'oilSauces', 'monthlyTotal'));
    }

    public function saveLog(Request $request, InventoryItem $inventory)
    {
        \Log::info('Inventory saveLog called', [
            'item_id' => $inventory->id,
            'item_name' => $inventory->name,
            'log_date' => $request->log_date,
            'opening' => $request->opening,
            'purchased' => $request->purchased,
            'wastage' => $request->wastage,
            'closing' => $request->closing,
            'consumption' => $request->consumption,
        ]);

        $request->validate([
            'log_date' => 'required|date',
            'opening' => 'required|numeric|min:0',
            'purchased' => 'required|numeric|min:0',
            'wastage' => 'required|numeric|min:0',
            'closing' => 'required|numeric|min:0',
            'consumption' => 'required|numeric|min:0',
        ]);

        // Determine opening_source: 'manual' only if staff explicitly edited the opening
        // Check if the opening field was manually edited (passed from JS via data-opening-source)
        $openingSource = $request->get('opening_source', 'default');
        if ($openingSource !== 'manual') {
            // If not explicitly set to manual, preserve existing source or default
            $existingLog = InventoryLog::where('inventory_item_id', $inventory->id)
                ->whereDate('log_date', $request->log_date)
                ->first();
            $openingSource = $existingLog->opening_source ?? 'default';
        }

        $data = [
            'opening' => $request->opening,
            'opening_source' => $openingSource,
            'purchased' => $request->purchased,
            'wastage' => $request->wastage,
            'closing' => $request->closing,
            'consumption' => $request->consumption,
            'notes' => $request->notes,
        ];

        // Apply gas_changed from session if exists
        $gasChanged = session("gas_changed_{$request->log_date}");
        if ($gasChanged !== null) {
            $data['gas_changed'] = $gasChanged;
        }

        // Apply electricity_reading from session if exists
        $electricityReading = session("electricity_reading_{$request->log_date}");
        if ($electricityReading !== null) {
            $data['electricity_reading'] = $electricityReading;
        }

        if ($inventory->is_mayo) {
            $data['mayo_oil_qty'] = $request->mayo_oil_qty;
            $data['mayo_milk_qty'] = $request->mayo_milk_qty;
            $data['mayo_bottles'] = $request->mayo_bottles;
        }

        $log = InventoryLog::updateOrCreate(
            ['inventory_item_id' => $inventory->id, 'log_date' => $request->log_date],
            $data
        );

        // ── Stock validation ──────────────────────────────────────────────────
        $consumed = $request->consumption + $request->wastage;
        $stockWarning = null;
        if ($consumed > 0 && $inventory->current_stock < $consumed) {
            $stockWarning = "⚠️ {$inventory->name} — You're recording {$consumed} {$inventory->unit} usage, but current stock is only {$inventory->current_stock} {$inventory->unit}. Did you forget to log a purchase?";
        }

        // Update current_stock on the item to latest closing
        $inventory->update(['current_stock' => max(0, $log->closing)]);

        // ── CARRY-FORWARD FIX ──────────────────────────────────────────────────────
        // Carry today's closing → tomorrow's opening
        $nextDate = \Carbon\Carbon::parse($request->log_date)->addDay()->toDateString();

        $nextLog = InventoryLog::where('inventory_item_id', $inventory->id)
            ->whereDate('log_date', $nextDate)
            ->first();

        \Log::info('Carry-forward logic', [
            'item_id' => $inventory->id,
            'today_date' => $request->log_date,
            'today_closing' => $log->closing,
            'next_date' => $nextDate,
            'next_log_exists' => ! empty($nextLog),
            'next_log_opening_source' => $nextLog->opening_source ?? null,
        ]);

        if (! $nextLog) {
            // No entry exists for tomorrow — create one with opening = today's closing
            // Do NOT include closing - it's a generated column that auto-calculates
            InventoryLog::create([
                'inventory_item_id' => $inventory->id,
                'log_date' => $nextDate,
                'opening' => max(0, $log->closing),
                'purchased' => 0,
                'consumption' => 0,
                'wastage' => 0,
                'opening_source' => 'auto',
            ]);
            \Log::info('Created next day log', ['next_date' => $nextDate, 'opening' => max(0, $log->closing)]);
        } else {
            // Tomorrow's log exists — update ONLY opening to today's closing
            // Do NOT update closing - it's a generated column that auto-calculates
            $updateData = ['opening' => max(0, $log->closing)];

            // Only set opening_source to 'auto' if it's not manually edited
            if ($nextLog->opening_source !== 'manual') {
                $updateData['opening_source'] = 'auto';
            }

            $nextLog->update($updateData);
            \Log::info('Updated next day log', ['next_date' => $nextDate, 'update_data' => $updateData]);
        }
        // ── END CARRY-FORWARD FIX ──────────────────────────────────────────────────

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'closing' => number_format($log->closing, 2),
                'total' => number_format($log->opening + $log->purchased, 2),
                'stockWarning' => $stockWarning,
            ]);
        }

        if ($stockWarning) {
            return redirect()->route('inventory.daily', ['date' => $request->log_date])
                ->with('warning', $stockWarning);
        }

        return redirect()->route('inventory.daily', ['date' => $request->log_date])
            ->with('success', "{$inventory->name} log saved.");
    }

    // ─── HISTORY / REPORT ───────────────────────────────────────────────────────

    public function history(Request $request)
    {
        $from = $request->get('from', today()->subDays(7)->toDateString());
        $to = $request->get('to', today()->toDateString());

        $category = $request->get('category', '');
        $categories = InventoryItem::$categories;

        $query = InventoryLog::with('item')
            ->whereBetween('log_date', [$from, $to])
            ->orderBy('log_date', 'desc');

        if ($category) {
            $query->whereHas('item', fn ($q) => $q->where('category', $category));
        }

        $logs = $query->get()->groupBy('log_date');

        return view('inventory.history', compact('logs', 'from', 'to', 'category', 'categories'));
    }

    public function importForm()
    {
        return view('inventory.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $headers = array_map('trim', fgetcsv($handle));

        $validCategories = array_keys(InventoryItem::$categories);
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue;
            }

            $data = array_combine($headers, array_map('trim', $row));
            $name = $data['name'] ?? '';
            $category = $data['category'] ?? '';
            $unit = $data['unit'] ?? '';

            if (! $name) {
                $errors[] = "Row {$rowNum}: name is empty — skipped.";
                $skipped++;

                continue;
            }
            if (! in_array($category, $validCategories)) {
                $errors[] = "Row {$rowNum} ({$name}): invalid category '{$category}' — skipped.";
                $skipped++;

                continue;
            }
            if (! $unit) {
                $errors[] = "Row {$rowNum} ({$name}): unit is empty — skipped.";
                $skipped++;

                continue;
            }
            if (InventoryItem::where('name', $name)->exists()) {
                $errors[] = "Row {$rowNum} ({$name}): already exists — skipped.";
                $skipped++;

                continue;
            }

            InventoryItem::create([
                'name' => $name,
                'category' => $category,
                'unit' => $unit,
                'current_stock' => 0,
                'minimum_stock' => is_numeric($data['minimum_stock'] ?? '') ? (float) $data['minimum_stock'] : 0,
                'unit_price' => is_numeric($data['unit_price'] ?? '') ? (float) $data['unit_price'] : 0,
                'is_mayo' => in_array($data['is_mayo'] ?? '0', ['1', 'yes', 'true']) ? 1 : 0,
            ]);

            $imported++;
        }

        fclose($handle);

        return redirect()->route('inventory.index')
            ->with('success', "{$imported} items imported.".($skipped ? " {$skipped} skipped." : ''))
            ->with('import_errors', $errors);
    }
}
