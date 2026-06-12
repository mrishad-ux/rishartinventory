<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()->get();

        $todayCash = Sale::whereDate('sale_date', today())
            ->where('sale_type', 'cash')->sum('net_amount');
        $todayGP = Sale::whereDate('sale_date', today())
            ->where('sale_type', 'gp')->sum('net_amount');
        $todayOnline = Sale::whereDate('sale_date', today())
            ->whereIn('sale_type', ['swiggy', 'zomato'])->sum('gross_amount');
        $todayActual = $todayCash + $todayGP;
        $creditSalesToday = Sale::whereDate('sale_date', today())
            ->where('sale_type', 'other')->sum('gross_amount');

        $monthCash = Sale::whereMonth('sale_date', now()->month)
            ->where('sale_type', 'cash')->sum('net_amount');
        $monthGP = Sale::whereMonth('sale_date', now()->month)
            ->where('sale_type', 'gp')->sum('net_amount');
        $monthOnline = Sale::whereMonth('sale_date', now()->month)
            ->whereIn('sale_type', ['swiggy', 'zomato'])->sum('gross_amount');
        $monthActual = $monthCash + $monthGP;

        $onlinePendingMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereIn('sale_type', ['swiggy', 'zomato'])
            ->where('settlement_status', 'pending')->sum('net_amount');
        $onlineCreditedMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereIn('sale_type', ['swiggy', 'zomato'])
            ->where('settlement_status', 'received')->sum('net_amount');

        $pendingSettlement = Sale::whereIn('sale_type', ['swiggy', 'zomato'])
            ->where('settlement_status', 'pending')->sum('net_amount');

        return view('sales.index', compact(
            'sales',
            'todayCash', 'todayGP', 'todayOnline', 'todayActual', 'creditSalesToday',
            'monthCash', 'monthGP', 'monthOnline', 'monthActual',
            'pendingSettlement', 'onlinePendingMonth', 'onlineCreditedMonth'
        ));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'sale_type' => 'required|in:cash,gp,swiggy,zomato',
            'platform' => 'nullable',
            'gross_amount' => 'required|numeric',
            'commission_percent' => 'nullable|numeric',
            'notes' => 'nullable',
        ]);

        $data = $request->all();

        if (in_array($request->sale_type, ['swiggy', 'zomato'])) {
            $commission = ($request->gross_amount * $request->commission_percent) / 100;
            $data['commission_amount'] = $commission;
            $data['net_amount'] = $request->gross_amount - $commission;
            $data['settlement_status'] = 'pending';
        } else {
            $data['commission_percent'] = 0;
            $data['commission_amount'] = 0;
            $data['net_amount'] = $request->gross_amount;
            $data['settlement_status'] = 'not_applicable';
        }

        Sale::create($data);

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded successfully!');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'cash_amount' => 'nullable|numeric|min:0',
            'gp_amount' => 'nullable|numeric|min:0',
            'swiggy_gross' => 'nullable|numeric|min:0',
            'zomato_gross' => 'nullable|numeric|min:0',
            'credit_entries' => 'nullable|array',
            'credit_entries.*.name' => 'nullable|string',
            'credit_entries.*.amount' => 'nullable|numeric|min:0',
        ]);

        $saleDate = $request->sale_date;
        $commissions = [
            'swiggy' => 31,
            'zomato' => 31,
        ];

        $savedCount = 0;

        // Cash Sale
        if ($request->filled('cash_amount') && $request->cash_amount > 0) {
            Sale::updateOrCreate(
                ['sale_date' => $saleDate, 'sale_type' => 'cash'],
                [
                    'platform' => null,
                    'gross_amount' => $request->cash_amount,
                    'commission_percent' => 0,
                    'commission_amount' => 0,
                    'net_amount' => $request->cash_amount,
                    'settlement_status' => 'not_applicable',
                ]
            );
            $savedCount++;
        }

        // Google Pay
        if ($request->filled('gp_amount') && $request->gp_amount > 0) {
            Sale::updateOrCreate(
                ['sale_date' => $saleDate, 'sale_type' => 'gp'],
                [
                    'platform' => null,
                    'gross_amount' => $request->gp_amount,
                    'commission_percent' => 0,
                    'commission_amount' => 0,
                    'net_amount' => $request->gp_amount,
                    'settlement_status' => 'not_applicable',
                ]
            );
            $savedCount++;
        }

        // Swiggy
        if ($request->filled('swiggy_gross') && $request->swiggy_gross > 0) {
            $commissionPercent = $commissions['swiggy'];
            $commissionAmount = ($request->swiggy_gross * $commissionPercent) / 100;
            Sale::updateOrCreate(
                ['sale_date' => $saleDate, 'sale_type' => 'swiggy'],
                [
                    'platform' => 'Swiggy',
                    'gross_amount' => $request->swiggy_gross,
                    'commission_percent' => $commissionPercent,
                    'commission_amount' => $commissionAmount,
                    'net_amount' => $request->swiggy_gross - $commissionAmount,
                    'settlement_status' => 'pending',
                ]
            );
            $savedCount++;
        }

        // Zomato
        if ($request->filled('zomato_gross') && $request->zomato_gross > 0) {
            $commissionPercent = $commissions['zomato'];
            $commissionAmount = ($request->zomato_gross * $commissionPercent) / 100;
            Sale::updateOrCreate(
                ['sale_date' => $saleDate, 'sale_type' => 'zomato'],
                [
                    'platform' => 'Zomato',
                    'gross_amount' => $request->zomato_gross,
                    'commission_percent' => $commissionPercent,
                    'commission_amount' => $commissionAmount,
                    'net_amount' => $request->zomato_gross - $commissionAmount,
                    'settlement_status' => 'pending',
                ]
            );
            $savedCount++;
        }

        // Credit entries
        if ($request->has('credit_entries')) {
            foreach ($request->credit_entries as $entry) {
                if (!empty($entry['name']) && !empty($entry['amount']) && $entry['amount'] > 0) {
                    Sale::updateOrCreate(
                        ['sale_date' => $saleDate, 'sale_type' => 'other', 'platform' => $entry['name']],
                        [
                            'gross_amount' => $entry['amount'],
                            'commission_percent' => 0,
                            'commission_amount' => 0,
                            'net_amount' => $entry['amount'],
                            'settlement_status' => 'pending',
                            'customer_name' => $entry['name'],
                            'customer_phone' => $entry['phone'] ?? null,
                            'customer_notes' => $entry['notes'] ?? null,
                        ]
                    );
                    $savedCount++;
                }
            }
        }

        if ($savedCount === 0) {
            return redirect()->route('sales.index')
                ->with('error', 'Please enter at least one sale amount.');
        }

        return redirect()->route('sales.index')
            ->with('success', "Sales recorded successfully! ({$savedCount} entries)");
    }

    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'sale_type' => 'required|in:cash,gp,swiggy,zomato,other',
            'platform' => 'nullable',
            'gross_amount' => 'required|numeric',
            'commission_percent' => 'nullable|numeric',
            'customer_name' => 'nullable',
            'customer_phone' => 'nullable',
            'customer_notes' => 'nullable',
            'notes' => 'nullable',
        ]);

        $data = $request->all();

        if (in_array($request->sale_type, ['swiggy', 'zomato'])) {
            $commission = ($request->gross_amount * $request->commission_percent) / 100;
            $data['commission_amount'] = $commission;
            $data['net_amount'] = $request->gross_amount - $commission;
            $data['customer_name'] = null;
            $data['customer_phone'] = null;
            $data['customer_notes'] = null;
        } elseif ($request->sale_type === 'other') {
            $data['commission_percent'] = 0;
            $data['commission_amount'] = 0;
            $data['net_amount'] = $request->gross_amount;
            $data['settlement_status'] = $request->settlement_status ?? 'pending';
            $data['customer_name'] = $request->customer_name;
            $data['customer_phone'] = $request->customer_phone;
            $data['customer_notes'] = $request->customer_notes;
            $data['platform'] = $request->customer_name;
        } else {
            $data['commission_percent'] = 0;
            $data['commission_amount'] = 0;
            $data['net_amount'] = $request->gross_amount;
            $data['settlement_status'] = 'not_applicable';
            $data['customer_name'] = null;
            $data['customer_phone'] = null;
            $data['customer_notes'] = null;
        }

        $sale->update($data);

        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully!');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'types' => 'required|array',
            'types.*' => 'in:cash,gp,swiggy,zomato',
        ]);

        $from = $request->date_from;
        $to = $request->date_to;
        $types = $request->types;

        $sales = Sale::whereBetween('sale_date', [$from, $to])
            ->whereIn('sale_type', $types)
            ->orderBy('sale_date')
            ->orderBy('sale_type')
            ->get();

        // Build totals keyed by sale_type
        $totals = [];
        foreach ($types as $type) {
            $filtered = $sales->where('sale_type', $type);
            $totals[$type] = [
                'gross_amount' => (float) $filtered->sum('gross_amount'),
                'net_amount' => (float) $filtered->sum('net_amount'),
                'count' => $filtered->count(),
            ];
        }

        // Build date-wise breakdown
        $dateWise = $sales->map(fn($sale) => [
            'sale_date' => $sale->sale_date->toDateString(),
            'sale_type' => $sale->sale_type,
            'gross_amount' => (float) $sale->gross_amount,
            'net_amount' => (float) $sale->net_amount,
        ])->values();

        $grandTotal = (float) collect($totals)->sum('gross_amount');

        return response()->json([
            'totals' => $totals,
            'date_wise' => $dateWise,
            'grand_total' => $grandTotal,
        ]);
    }
}
