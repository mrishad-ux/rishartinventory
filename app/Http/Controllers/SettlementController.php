<?php

namespace App\Http\Controllers;

use App\Models\PlatformSettlement;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettlementController extends Controller
{
    public function __construct()
    {
        $this->cleanupDuplicateSettlements();
    }

    /**
     * One-time cleanup: keep earliest record for each platform+period combination, delete rest.
     */
    protected function cleanupDuplicateSettlements()
    {
        $duplicates = DB::table('platform_settlements')
            ->selectRaw('MIN(id) as keep_id, platform, period_from, period_to')
            ->groupBy('platform', 'period_from', 'period_to')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isNotEmpty()) {
            $idsToKeep = $duplicates->pluck('keep_id')->toArray();
            DB::table('platform_settlements')
                ->whereNotIn('id', $idsToKeep)
                ->delete();
        }
    }

    /**
     * Display a listing of settlements grouped by platform.
     */
    public function index()
    {
        $swiggySettlements = PlatformSettlement::where('platform', 'swiggy')
            ->orderBy('period_from', 'desc')
            ->paginate(10);

        $zomatoSettlements = PlatformSettlement::where('platform', 'zomato')
            ->orderBy('period_from', 'desc')
            ->paginate(10);

        $creditSales = Sale::where('sale_type', 'other')
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('settlements.index', compact('swiggySettlements', 'zomatoSettlements', 'creditSales'));
    }

    /**
     * Auto-generate pending settlements from Sales data.
     */
    public function generate()
    {
        // Process Swiggy sales (group by Sunday to Saturday weeks)
        $this->generateSettlementsForPlatform('swiggy', 'sunday');

        // Process Zomato sales (group by Monday to Sunday weeks)
        $this->generateSettlementsForPlatform('zomato', 'monday');

        return redirect()->route('settlements.index')
            ->with('success', 'Settlements generated successfully!');
    }

    /**
     * Helper method to generate settlements for a specific platform.
     */
    protected function generateSettlementsForPlatform($platform, $weekStartDay)
    {
        // Get all sales for this platform
        $sales = Sale::where('sale_type', $platform)
            ->where('settlement_status', 'pending')
            ->orderBy('sale_date')
            ->get();

        if ($sales->isEmpty()) {
            return;
        }

        // Group sales by week
        $weeks = [];
        foreach ($sales as $sale) {
            $date = $sale->sale_date;

            if ($weekStartDay === 'sunday') {
                // Sunday to Saturday week
                $weekStart = $date->copy()->startOfWeek(Carbon::SUNDAY);
                $weekEnd = $date->copy()->endOfWeek(Carbon::SATURDAY);
            } else {
                // Monday to Sunday week
                $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
                $weekEnd = $date->copy()->endOfWeek(Carbon::SUNDAY);
            }

            $weekKey = $weekStart->toDateString();

            if (! isset($weeks[$weekKey])) {
                $weeks[$weekKey] = [
                    'platform' => $platform,
                    'period_from' => $weekStart,
                    'period_to' => $weekEnd,
                    'gross_amount' => 0,
                    'sales' => [],
                ];
            }

            $weeks[$weekKey]['gross_amount'] += $sale->gross_amount;
            $weeks[$weekKey]['sales'][] = $sale->id;
        }

        // Create settlements for each week using firstOrCreate
        foreach ($weeks as $week) {
            // Calculate expected credit date (next Wednesday after period ends)
            $expectedCreditDate = $this->getNextWednesday($week['period_to']);

            // Calculate estimated amounts
            $grossAmount = $week['gross_amount'];
            $estimatedCommission = $grossAmount * 0.31;
            $estimatedNet = $grossAmount * 0.69;

            // Use firstOrCreate to prevent duplicates
            PlatformSettlement::firstOrCreate(
                [
                    'platform' => $platform,
                    'period_from' => $week['period_from'],
                    'period_to' => $week['period_to'],
                ],
                [
                    'expected_credit_date' => $expectedCreditDate,
                    'gross_amount' => $grossAmount,
                    'estimated_commission' => $estimatedCommission,
                    'estimated_net' => $estimatedNet,
                    'status' => 'pending',
                ]
            );
        }
    }

    /**
     * Get the next Wednesday after a given date.
     */
    protected function getNextWednesday($date)
    {
        $date = Carbon::parse($date);
        while ($date->dayOfWeek !== Carbon::WEDNESDAY) {
            $date->addDay();
        }

        return $date;
    }

    /**
     * Mark a settlement as received.
     */
    public function markReceived(Request $request, PlatformSettlement $settlement)
    {
        $request->validate([
            'actual_amount_received' => 'required|numeric|min:0',
            'actual_credit_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Calculate actual commission (difference between gross and received)
        $actualCommission = $settlement->gross_amount - $request->actual_amount_received;

        $settlement->update([
            'actual_amount_received' => $request->actual_amount_received,
            'actual_credit_date' => $request->actual_credit_date,
            'actual_commission' => $actualCommission,
            'status' => 'received',
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Settlement marked as received successfully!');
    }

    public function update(Request $request, PlatformSettlement $settlement)
    {
        $request->validate([
            'actual_amount_received' => 'nullable|numeric',
            'actual_credit_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,received,disputed',
        ]);

        $data = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->actual_amount_received) {
            $data['actual_amount_received'] = $request->actual_amount_received;
            $data['actual_commission'] = $settlement->gross_amount - $request->actual_amount_received;
        }

        if ($request->actual_credit_date) {
            $data['actual_credit_date'] = $request->actual_credit_date;
        }

        $settlement->update($data);

        return redirect()->back()->with('success', 'Settlement updated successfully.');
    }

    /**
     * Mark a credit sale as received.
     */
    public function markCreditReceived(Request $request, Sale $creditSale)
    {
        if ($creditSale->sale_type !== 'other') {
            return redirect()->back()->with('error', 'Invalid credit sale.');
        }

        $creditSale->update([
            'settlement_status' => 'received',
            'actual_settlement_date' => $request->received_date ?? today()->toDateString(),
        ]);

        return redirect()->back()
            ->with('success', 'Credit sale marked as received!');
    }
}
