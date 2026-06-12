@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

<div style="max-width:640px; margin:0 auto;">
    <div class="glass-card" style="padding:32px;">

        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
            <div>
                <div class="page-title">Payment Details</div>
                <div class="page-subtitle">Recorded on {{ $payment->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <a href="{{ route('payments.index') }}" class="btn-ghost">← Back</a>
        </div>

        <div style="display:flex; flex-direction:column; gap:16px;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Payment Date</p>
                    <p style="font-size:15px; font-weight:600; color:white;">{{ $payment->payment_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Amount</p>
                    <p style="font-size:22px; font-weight:700; color:var(--accent);">₹{{ number_format($payment->amount, 2) }}</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div>
                <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Expense</p>
                <p style="font-size:15px; font-weight:600; color:white;">
                    {{ $payment->expense->title ?? $payment->expense->description ?? 'N/A' }}
                </p>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Vendor</p>
                    <p style="font-size:14px; color:rgba(255,255,255,0.75);">
                        {{ $payment->expense->supplier->name ?? $payment->expense->vendor_name ?? '-' }}
                    </p>
                </div>
                <div>
                    <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Expense Amount</p>
                    <p style="font-size:14px; color:rgba(255,255,255,0.75);">
                        ₹{{ number_format($payment->expense->amount, 2) }}
                    </p>
                </div>
            </div>

            <div>
                <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Expense Paid Amount</p>
                <p style="font-size:14px; color:rgba(255,255,255,0.75);">
                    ₹{{ number_format($payment->expense->paid_amount, 2) }}
                </p>
            </div>

            @if($payment->notes)
            <div class="section-divider"></div>
            <div>
                <p style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Notes</p>
                <p style="font-size:14px; color:rgba(255,255,255,0.75);">{{ $payment->notes }}</p>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection