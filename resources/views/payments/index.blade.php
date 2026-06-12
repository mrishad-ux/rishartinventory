@extends('layouts.app')

@section('title', 'Payments')

@section('content')

<div style="padding:0">

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <div class="page-title">Payments</div>
            <div class="page-subtitle">Track payments made against expenses</div>
        </div>
        <a href="{{ route('payments.create') }}" class="btn-primary"">+ Record Payment</a>
    </div>

    <!-- Summary Cards -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
        <div class="card" s1" style="padding:24px;">
            <div class="stat-label">Total Payments</div>
            <div class="stat-value">{{ $totalPaymentsCount }}</div>
        </div>
        <div class="card" s4" style="padding:24px;">
            <div class="stat-label">Total Amount Paid</div>
            <div class="stat-value">₹{{ number_format($totalAmount, 2) }}</div>
        </div>
    </div>

    @if($payments->count() > 0)
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="max-height:600px; overflow-y:auto;">
                <table class="data-table w-full">
                    <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                        <tr>
                            <th class="text-left" style="text-align:left !important; padding:12px 16px;">Date</th>
                            <th class="text-left" style="text-align:left !important; padding:12px 16px;">Vendor</th>
                            <th class="text-left" style="text-align:left !important; padding:12px 16px;">Description</th>
                            <th class="text-right" style="text-align:right !important; padding:12px 16px;">Amount</th>
                            <th class="text-left" style="text-align:left !important; padding:12px 16px;">Notes</th>
                            <th class="text-center" style="text-align:center !important; padding:12px 16px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td class="text-left" style="text-align:left !important; padding:12px 16px;">
                                {{ $payment->payment_date->format('d M Y') }}
                            </td>
                            <td class="text-left" style="text-align:left !important; padding:12px 16px; font-weight:500;">
                                {{ $payment->expense->supplier->name ?? $payment->expense->vendor_name ?? '-' }}
                            </td>
                            <td class="text-left" style="text-align:left !important; padding:12px 16px;">
                                {{ $payment->expense->title ?? $payment->expense->description ?? '-' }}
                            </td>
                            <td class="text-right" style="text-align:right !important; padding:12px 16px; font-weight:600;">
                                ₹{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="text-left" style="text-align:left !important; padding:12px 16px; color:#64748b; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $payment->notes ?? '-' }}
                            </td>
                            <td class="text-center" style="text-align:center !important; padding:12px 16px;">
                                <a href="{{ route('payments.show', $payment) }}" class="btn-secondary" btn-sm" style="margin-right:8px;">View</a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this payment? This will reduce the expense paid amount.')" class="btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:20px;">
            {{ $payments->links() }}
        </div>
    @else
        <div class="card" style="padding:48px; text-align:center;">
            <p style="color:#64748b; font-size:16px;">No payments recorded yet.</p>
            <a href="{{ route('payments.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
                Record First Payment
            </a>
        </div>
    @endif
</div>

<style>
    /* Pagination styling */
    .pagination { display:flex; gap:6px; flex-wrap:wrap; justify-content:center; }
    .pagination .page-item { list-style:none; }
    .pagination .page-link {
        display:inline-flex; align-items:center; justify-content:center;
        padding:6px 12px; font-size:13px; font-weight:600; border-radius:8px;
        background:#f8fafc; color:#475569;
        border:1px solid #e2e8f0; transition:all 0.2s;
        text-decoration:none;
    }
    .pagination .page-link:hover { background:#e2e8f0; color:white; }
    .pagination .active .page-link { background:var(--nav-active-bg); color:white; border-color:var(--accent); }
    .pagination .disabled .page-link { opacity:0.3; pointer-events:none; }
</style>

@endsection