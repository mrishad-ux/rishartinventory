<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\InventoryItem;
use App\Models\Sale;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('sale_date', today())
            ->whereIn('sale_type', ['cash', 'gp'])->sum('net_amount');
        $monthSales = Sale::whereMonth('sale_date', now()->month)
            ->whereIn('sale_type', ['cash', 'gp'])->sum('net_amount');
        $unpaidExpenses = Expense::pending()->sum(DB::raw('amount - paid_amount'));
        $pendingExpenses = Expense::pending()->with('supplier')->orderBy('expense_date', 'desc')->get();
        $lowStockItems = InventoryItem::where('minimum_stock', '>', 0)
            ->with(['logs' => function($q) {
                $q->orderBy('log_date', 'desc')->limit(1);
            }])
            ->get()
            ->filter(function($item) {
                $latestLog = $item->logs->first();
                return $latestLog && $latestLog->closing < $item->minimum_stock;
            })
            ->count();
            
        $activeStaff = Staff::where('status', 'active')->count();
        $todayExpenses = Expense::whereDate('expense_date', today())->sum('amount');
        $monthExpenses = Expense::whereMonth('expense_date', now()->month)->sum('amount');
        $recentSales = Sale::orderBy('sale_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $lowStockList = InventoryItem::where('minimum_stock', '>', 0)
            ->with(['logs' => function($q) {
                $q->orderBy('log_date', 'desc')->limit(1);
            }])
            ->get()
            ->filter(function($item) {
                $latestLog = $item->logs->first();
                return $latestLog && $latestLog->closing < $item->minimum_stock;
            })
            ->values();

        return view('dashboard', compact(
            'todaySales', 'monthSales', 'unpaidExpenses',
            'lowStockItems', 'activeStaff', 'todayExpenses', 'monthExpenses',
            'recentSales', 'lowStockList', 'pendingExpenses'
        ));
    }
}
