@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

<div style="max-width:640px; margin:0 auto;">
    <div class="glass-card" style="padding:24px;">

        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
            <h3 style="font-size:18px; font-weight:700;">Sale Details</h3>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('sales.edit', $sale) }}" class="btn-accent" style="font-size:12px; padding:8px 16px;">Edit</a>
                <a href="{{ route('sales.index') }}" class="btn-ghost" style="font-size:12px; padding:8px 16px;">Back</a>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:16px;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                <div>
                    <p style="font-size:12px; color:#64748b;">Sale Date</p>
                    <p style="margin-top:4px;">{{ $sale->sale_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b;">Sale Type</p>
                    <div style="margin-top:4px;">
                        @if($sale->sale_type == 'cash')
                            <span class="badge badge-cash">Cash</span>
                        @elseif($sale->sale_type == 'gp')
                            <span class="badge badge-gp">GP</span>
                        @elseif($sale->sale_type == 'swiggy')
                            <span class="badge badge-swiggy">Swiggy</span>
                        @elseif($sale->sale_type == 'zomato')
                            <span class="badge badge-zomato">Zomato</span>
                        @elseif($sale->sale_type == 'other')
                            <span class="badge" style="background:rgba(168,85,247,0.15); color:#a78bfa; border:1px solid rgba(168,85,247,0.3);">Credit Sale</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($sale->sale_type == 'other')
            <div style="border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                <p style="font-size:12px; color:#64748b;">Customer</p>
                <p style="margin-top:4px;">{{ $sale->customer_name ?? $sale->platform ?? '-' }}</p>
                @if($sale->customer_phone)
                <p style="font-size:12px; color:#64748b; margin-top:2px;">{{ $sale->customer_phone }}</p>
                @endif
            </div>
            @endif

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                <div>
                    <p style="font-size:12px; color:#64748b;">Gross Amount</p>
                    <p style="font-size:20px; font-weight:700; margin-top:4px;">₹{{ number_format($sale->gross_amount, 2) }}</p>
                </div>
                @if(in_array($sale->sale_type, ['swiggy', 'zomato']))
                <div>
                    <p style="font-size:12px; color:#64748b;">Commission ({{ $sale->commission_percent }}%)</p>
                    <p style="font-size:20px; font-weight:700; color:#dc2626; margin-top:4px;">-₹{{ number_format($sale->commission_amount, 2) }}</p>
                </div>
                @endif
                <div>
                    <p style="font-size:12px; color:#64748b;">Net Amount</p>
                    <p style="font-size:20px; font-weight:700; color:#16a34a; margin-top:4px;">₹{{ number_format($sale->net_amount, 2) }}</p>
                </div>
            </div>

            @if(in_array($sale->sale_type, ['swiggy', 'zomato', 'other']))
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                <div>
                    <p style="font-size:12px; color:#64748b;">Settlement Status</p>
                    <div style="margin-top:8px;">
                        @if($sale->settlement_status == 'pending')
                            <span class="badge-pending">Pending</span>
                        @elseif($sale->settlement_status == 'received')
                            <span class="badge-received">Received</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b;">Expected Settlement</p>
                    <p style="margin-top:4px;">{{ $sale->expected_settlement_date ? $sale->expected_settlement_date->format('d M Y') : '-' }}</p>
                </div>
            </div>

            @if($sale->actual_settlement_date)
            <div style="border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                <p style="font-size:12px; color:#64748b;">Actual Settlement Date</p>
                <p style="margin-top:4px;">{{ $sale->actual_settlement_date->format('d M Y') }}</p>
            </div>
            @endif
            @endif

            @if($sale->customer_notes)
            <div>
                <p style="font-size:12px; color:#64748b;">Notes</p>
                <p style="margin-top:4px;">{{ $sale->customer_notes }}</p>
            </div>
            @elseif($sale->notes)
            <div>
                <p style="font-size:12px; color:#64748b;">Notes</p>
                <p style="margin-top:4px;">{{ $sale->notes }}</p>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection
