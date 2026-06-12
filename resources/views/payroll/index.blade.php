@extends('layouts.app')

@section('title', 'Payroll')

@section('content')

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
    <div class="card" style="padding:24px;">
        <div class="stat-label">This Month's Payroll</div>
        <div class="stat-value" style="color:#3b82f6;">₹{{ number_format($monthTotal, 2) }}</div>
    </div>
    <div class="card" style="padding:24px;">
        <div class="stat-label">Total Unpaid Salaries</div>
        <div class="stat-value" style="color:#dc2626;">₹{{ number_format($totalUnpaid, 2) }}</div>
    </div>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div class="page-title">Payroll</div>
    <a href="{{ route('payroll.create') }}" class="btn-primary"">
        + Record Payroll
    </a>
</div>

<div class="page-subtitle" style="margin-bottom:24px;">Staff salary management</div>

@if($payrolls->count() > 0)
<div class="card" style="padding:0; overflow:hidden;">
    <div style="max-height:480px; overflow-y:auto;">
        <table class="data-table w-full">
            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                <tr>
                    <th style="text-align:left !important; padding:12px 16px;">Staff Name</th>
                    <th style="text-align:left !important; padding:12px 16px;">Payment Date</th>
                    <th style="text-align:right !important; padding:12px 16px;">Days</th>
                    <th style="text-align:right !important; padding:12px 16px;">Basic</th>
                    <th style="text-align:right !important; padding:12px 16px;">Bonus</th>
                    <th style="text-align:right !important; padding:12px 16px;">Deduction</th>
                    <th style="text-align:right !important; padding:12px 16px;">Net Pay</th>
                    <th style="text-align:center !important; padding:12px 16px;">Status</th>
                    <th style="text-align:center !important; padding:12px 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrolls as $payroll)
                <tr>
                    <td style="text-align:left !important; padding:12px 16px;">{{ $payroll->staff->name ?? '-' }}</td>
                    <td style="text-align:left !important; padding:12px 16px;">{{ $payroll->payment_date->format('d M Y') }}</td>
                    <td style="text-align:right !important; padding:12px 16px;">{{ $payroll->days_worked ?? '-' }}</td>
                    <td style="text-align:right !important; padding:12px 16px;">₹{{ number_format($payroll->basic_amount, 2) }}</td>
                    <td style="text-align:right !important; padding:12px 16px; color:#16a34a;">{{ $payroll->bonus > 0 ? '+₹'.number_format($payroll->bonus, 2) : '-' }}</td>
                    <td style="text-align:right !important; padding:12px 16px; color:#dc2626;">{{ $payroll->deduction > 0 ? '-₹'.number_format($payroll->deduction, 2) : '-' }}</td>
                    <td style="text-align:right !important; padding:12px 16px; font-weight:bold;">₹{{ number_format($payroll->net_amount, 2) }}</td>
                    <td style="text-align:center !important; padding:12px 16px;">
                        @if($payroll->status == 'paid')
                            <span class="badge badge-paid"">Paid</span>
                        @else
                            <span class="badge badge-unpaid"">Unpaid</span>
                        @endif
                    </td>
                    <td style="text-align:center !important; padding:12px 16px;">
                        <a href="{{ route('payroll.show', $payroll) }}" style="color:var(--accent); margin-right:8px;">View</a>
                        <a href="{{ route('payroll.edit', $payroll) }}" style="color:#facc15; margin-right:8px;">Edit</a>
                        <form action="{{ route('payroll.destroy', $payroll) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this payroll record?')" style="color:#dc2626; background:none; border:none; cursor:pointer;">Delete</button>
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
    <p style="color:#64748b; font-size:18px;">No payroll records yet.</p>
    <a href="{{ route('payroll.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
        Add First Payroll
    </a>
</div>
@endif

@endsection
