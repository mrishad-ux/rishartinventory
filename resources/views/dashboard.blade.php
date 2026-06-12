@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div style="padding: 0">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Lord Of Wraps — Operations Overview</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px;">

        <div class="card accent-blue">
            <div class="stat-label">Today's Sales</div>
            <div class="stat-value">₹{{ number_format($todaySales, 2) }}</div>
            <div class="stat-sub">Expenses today: ₹{{ number_format($todayExpenses, 2) }}</div>
        </div>

        <div class="card accent-red">
            <div class="stat-label">This Month Sales</div>
            <div class="stat-value">₹{{ number_format($monthSales, 2) }}</div>
            <div class="stat-sub">Expenses: ₹{{ number_format($monthExpenses, 2) }}</div>
        </div>

        <div class="card accent-amber" onclick="openPendingExpensesModal()" style="cursor:pointer;" title="Click to view pending bills">
            <div class="stat-label">Unpaid Expenses</div>
            <div class="stat-value">₹{{ number_format($unpaidExpenses, 2) }}</div>
            <div class="stat-sub">{{ $pendingExpenses->count() }} bills pending</div>
        </div>

    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px;">

        <div class="card accent-green">
            <div class="stat-label">Active Staff</div>
            <div class="stat-value">{{ $activeStaff }}</div>
            <div class="stat-sub">Currently working</div>
        </div>

        @php
            $lowStockCount = is_int($lowStockItems) ? $lowStockItems : $lowStockItems->count();
            $lowStockValueStyle = $lowStockCount > 0 ? 'color:#dc2626;' : '';
        @endphp

        <div class="card accent-red" style="cursor:pointer;" onclick="openLowStockModal()" title="Click to see low stock items">
            <div class="stat-label">Low Stock Alerts</div>
            <div class="stat-value" style="{{ $lowStockValueStyle }}">
                {{ $lowStockCount }}
            </div>
            <div class="stat-sub">Items need restocking</div>
        </div>

    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <div class="card">
            <div class="card-title">Recent Sales</div>
            @if($recentSales->count() > 0)
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Date</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr>
                        @php
                        $typeLabels = [
                            'cash' => 'Cash Sale', 'gp' => 'GPay/UPI',
                            'swiggy' => 'Swiggy', 'zomato' => 'Zomato'
                        ];
                        @endphp
                        <td>{{ $typeLabels[$sale->sale_type] ?? ucfirst($sale->sale_type) }}</td>
                        <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        <td style="text-align:right; font-weight:600">
                            ₹{{ number_format($sale->net_amount, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color:#94a3b8; font-size:14px;">No sales recorded yet.</p>
            @endif
            <a href="/sales" style="color:#6366f1; font-size:13px; margin-top:12px; display:inline-block;">View all sales →</a>
        </div>

        <div class="card">
            <div class="card-title">Low Stock Items</div>
            @if($lowStockList->count() > 0)
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Current</th>
                        <th class="text-right">Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockList as $item)
                    <tr>
                        <td style="font-weight:500">{{ $item->name }}</td>
                        <td>{{ number_format($item->logs->first()?->closing ?? 0, 2) }} {{ $item->unit }}</td>
                        <td style="text-align:right; color:#94a3b8">
                            {{ number_format($item->minimum_stock, 2) }} {{ $item->unit }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color:#94a3b8; font-size:14px;">✅ All stock levels are healthy.</p>
            @endif
            <a href="/inventory" style="color:#6366f1; font-size:13px; margin-top:12px; display:inline-block;">View inventory →</a>
        </div>

    </div>
</div>

{{-- Pending Expenses Modal --}}
<div id="pending-expenses-modal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closePendingModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h2>📋 Pending Bills</h2>
            <button class="modal-close" onclick="closePendingModal()">✕</button>
        </div>
        <div style="overflow-y:auto; max-height:60vh;">
            @if($pendingExpenses->count() > 0)
            <table class="data-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Bill</th>
                        <th>Supplier</th>
                        <th class="text-center">Date</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Due</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingExpenses as $expense)
                    <tr>
                        <td style="font-weight:500">{{ $expense->title }}</td>
                        <td style="color:#64748b">{{ $expense->supplier?->name ?? '—' }}</td>
                        <td style="text-align:center; color:#64748b">{{ $expense->expense_date->format('d M') }}</td>
                        <td style="text-align:right">₹{{ number_format($expense->amount, 0) }}</td>
                        <td style="text-align:right; color:#16a34a;">₹{{ number_format($expense->paid_amount ?? 0, 0) }}</td>
                        <td style="text-align:right; font-weight:700; color:#dc2626;">₹{{ number_format($expense->amount - ($expense->paid_amount ?? 0), 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align:center; padding:40px 20px; color:#94a3b8;">✅ All bills are paid!</p>
            @endif
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-top:1px solid #e2e8f0;">
            <span style="font-size:13px; color:#64748b;">Total Outstanding</span>
            <span style="font-size:20px; font-weight:700; color:#6366f1;">₹{{ number_format($unpaidExpenses, 0) }}</span>
        </div>
    </div>
</div>

{{-- Low Stock Modal --}}
<div id="low-stock-modal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closeLowStockModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h2>📦 Low Stock Items</h2>
            <button class="modal-close" onclick="closeLowStockModal()">✕</button>
        </div>
        <div style="overflow-y:auto; max-height:60vh;">
            @if($lowStockList->count() > 0)
            <table class="data-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Category</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Min</th>
                        <th class="text-right">Short</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockList as $item)
                    @php
                        $latestLog = $item->logs->first();
                        $closing = $latestLog ? $latestLog->closing : 0;
                        $short = max(0, $item->minimum_stock - $closing);
                    @endphp
                    <tr>
                        <td style="font-weight:500">{{ $item->name }}</td>
                        <td style="text-align:center; color:#64748b;">{{ str_replace('_', ' ', ucfirst($item->category)) }}</td>
                        <td style="text-align:right">{{ $closing }} {{ $item->unit }}</td>
                        <td style="text-align:right">{{ $item->minimum_stock }} {{ $item->unit }}</td>
                        <td style="text-align:right; font-weight:700; color:#dc2626;">{{ $short }} {{ $item->unit }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align:center; padding:40px 20px; color:#94a3b8;">✅ All items are sufficiently stocked!</p>
            @endif
        </div>
    </div>
</div>

<script>
function openPendingExpensesModal() {
    document.getElementById('pending-expenses-modal').style.display = 'flex';
}
function closePendingModal() {
    document.getElementById('pending-expenses-modal').style.display = 'none';
}
function openLowStockModal() {
    document.getElementById('low-stock-modal').style.display = 'flex';
}
function closeLowStockModal() {
    document.getElementById('low-stock-modal').style.display = 'none';
}
</script>

@endsection
