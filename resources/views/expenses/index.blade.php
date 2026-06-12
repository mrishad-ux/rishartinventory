@extends('layouts.app')

@section('title', 'Expenses')

@section('content')

<div style="padding:0">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <div class="page-title">Expenses</div>
            <div class="page-subtitle">Track all outgoing payments</div>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn-primary"">+ Add Expense</a>
    </div>

    <!-- Summary Cards -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
        <div class="card" s1" style="padding:24px;">
            <div class="stat-label">Total Paid Expenses</div>
            <div class="stat-value">₹{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="card" s3" style="padding:24px;">
            <div class="stat-label">Total Unpaid Expenses</div>
            <div class="stat-value">₹{{ number_format($totalUnpaid, 2) }}</div>
        </div>
    </div>

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">All Expenses</h3>
    <a href="{{ route('expenses.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg">
        + Add Expense
    </a>
</div>

@if($expenses->count() > 0)
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="max-height:480px; overflow-y:auto;">
            <table class="data-table w-full">
                <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                    <tr>
                        <th class="text-left" style="text-align:left !important; padding:12px 16px;">Title</th>
                        <th class="text-left" style="text-align:left !important; padding:12px 16px;">Category</th>
                        <th class="text-left" style="text-align:left !important; padding:12px 16px;">Date</th>
                        <th class="text-left" style="text-align:left !important; padding:12px 16px;">Supplier</th>
                        <th class="text-right" style="text-align:right !important; padding:12px 16px;">Amount</th>
                        <th class="text-center" style="text-align:center !important; padding:12px 16px;">Payment Status</th>
                        <th class="text-center" style="text-align:center !important; padding:12px 16px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td class="text-left" style="text-align:left !important; padding:12px 16px; font-weight:500;">{{ $expense->title }}</td>
                        <td class="text-left" style="text-align:left !important; padding:12px 16px;">
                            @php
                                $catLabels = [
                                    'raw_materials'       => 'Raw Materials',
                                    'masala'              => 'Masala',
                                    'salary'              => 'Salary',
                                    'packing_materials'   => 'Packing Materials',
                                    'cleaning_materials'  => 'Cleaning Materials',
                                    'petty_cash'          => 'Petty Cash',
                                    'utilities'           => 'Utilities',
                                    'maintenance_repairs' => 'Maintenance and Repairs',
                                    'marketing'           => 'Marketing',
                                    'others'              => 'Others',
                                ];
                                $catLabel = $catLabels[$expense->category] ?? ucfirst(str_replace('_', ' ', $expense->category));
                            @endphp
                            <span class="badge badge-pending">{{ $catLabel }}</span>
                        </td>
                        <td class="text-left" style="text-align:left !important; padding:12px 16px;">{{ $expense->expense_date->format('d M Y') }}</td>
                        <td class="text-left" style="text-align:left !important; padding:12px 16px;">{{ $expense->supplier->name ?? '-' }}</td>
                        <td class="text-right" style="text-align:right !important; padding:12px 16px; font-weight:600;">₹{{ number_format($expense->amount, 2) }}</td>
                        <td class="text-center" style="text-align:center !important; padding:12px 16px;">
                            @php $ps = $expense->payment_status; @endphp
                            @if($ps == 'paid')
                                <span class="badge badge-paid">✅ Paid</span>
                            @elseif($ps == 'partial')
                                <span class="badge badge-pending">⏳ Partial<br><small>₹{{ number_format($expense->pending_amount, 2) }} pending</small></span>
                            @else
                                <span class="badge badge-unpaid">❌ Pending<br><small>₹{{ number_format($expense->amount, 2) }} pending</small></span>
                            @endif
                        </td>
                        <td class="text-center" style="text-align:center !important; padding:12px 16px;">
                            <a href="{{ route('expenses.show', $expense) }}" class="btn-secondary" btn-sm" style="margin-right:8px;">View</a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn-secondary" btn-sm" style="margin-right:8px;">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this expense?')" class="btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card" style="padding:48px; text-align:center;">
        <p style="color:#64748b; font-size:16px;">No expenses recorded yet.</p>
        <a href="{{ route('expenses.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
            Record First Expense
        </a>
    </div>
    @endif
</div>

@endsection