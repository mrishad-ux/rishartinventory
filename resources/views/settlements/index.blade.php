@extends('layouts.app')

@section('title', 'Platform Settlements')

@section('content')
<div style="padding:0">
    <div style="position:sticky; top:0; z-index:20; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px); padding:24px 24px 16px; border-bottom:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <div>
                <div class="page-title">Settlements</div>
                <div class="page-subtitle">Platform payment tracking</div>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                <form action="{{ route('settlements.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary"">
                        Generate Settlements
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div style="padding:24px; display:flex; flex-direction:column; gap:32px;">

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div style="font-size:18px; font-weight:700; display:flex; align-items:center; gap:8px;">
                    <span style="width:12px; height:12px; border-radius:50%; background:#f97316;"></span>
                    Swiggy Settlements
                </div>
                <span style="font-size:14px; color:#64748b;">
                    {{ $swiggySettlements->total() }} total
                </span>
            </div>

            @if($swiggySettlements->isEmpty())
                <div class="card" style="padding:32px; text-align:center;">
                    <p style="color:#64748b;">No Swiggy settlements found.</p>
                    <p style="color:#64748b; font-size:13px; margin-top:4px;">Click "Generate Settlements" to create from sales data.</p>
                </div>
            @else
                <div class="card" style="padding:0; overflow:hidden;">
                    <div style="max-height:480px; overflow-y:auto;">
                        <table class="data-table w-full">
                            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                                <tr>
                                    <th class="px-6 py-4 text-left">Period</th>
                                    <th class="px-6 py-4 text-right">Gross</th>
                                    <th class="px-6 py-4 text-right">Est. Commission</th>
                                    <th class="px-6 py-4 text-right">Est. Net</th>
                                    <th class="px-6 py-4 text-center">Expected</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($swiggySettlements as $settlement)
                                <tr style="border-bottom:1px solid #f8fafc;">
                                    <td class="px-6 py-4">
                                        <div style="font-weight:500;">
                                            {{ $settlement->period_from->format('d M') }} - {{ $settlement->period_to->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        ₹{{ number_format($settlement->gross_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right" style="color:#dc2626;">
                                        -₹{{ number_format($settlement->estimated_commission, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold" style="color:#16a34a;">
                                        ₹{{ number_format($settlement->estimated_net, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center" style="color:#64748b;">
                                        {{ $settlement->expected_credit_date->format('d M') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($settlement->status === 'pending')
                                            <span class="badge badge-pending"">Pending</span>
                                        @elseif($settlement->status === 'received')
                                            <span class="badge badge-received"">Received</span>
                                        @else
                                            <span class="badge badge-disputed"">Disputed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($settlement->status === 'received')
                                            <div style="font-size:11px; margin-bottom:4px;">
                                                @php
                                                    $variance = $settlement->actual_amount_received - $settlement->estimated_net;
                                                @endphp
                                                @if($variance > 0)
                                                    <span style="color:#16a34a;">+₹{{ number_format($variance, 0) }}</span>
                                                @elseif($variance < 0)
                                                    <span style="color:#dc2626;">-₹{{ number_format(abs($variance), 0) }}</span>
                                                @else
                                                    <span style="color:#64748b;">✓ exact</span>
                                                @endif
                                            </div>
                                        @endif
                                        <div style="display:flex; justify-content:center; gap:8px;">
                                            @if($settlement->status === 'pending')
                                                <button onclick="openMarkReceivedModal({{ $settlement->id }}, '{{ $settlement->platform }}', {{ $settlement->gross_amount }})"
                                                        class="btn-secondary" btn-sm">
                                                    Mark Received
                                                </button>
                                            @endif
                                             <button onclick="openEditModal({{ $settlement->id }}, '{{ $settlement->platform }}', '{{ $settlement->period_from->format("d M Y") }}', '{{ $settlement->period_to->format("d M Y") }}', {{ $settlement->actual_amount_received ?? 'null' }}, '{{ $settlement->actual_credit_date ? $settlement->actual_credit_date->format("Y-m-d") : '' }}', {{ json_encode($settlement->notes) }}, '{{ $settlement->status }}')"
                                                     class="btn-secondary" btn-sm">
                                                 Edit
                                             </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div style="margin-top:16px;">
                    {{ $swiggySettlements->links() }}
                </div>
            @endif
        </div>

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div style="font-size:18px; font-weight:700; display:flex; align-items:center; gap:8px;">
                    <span style="width:12px; height:12px; border-radius:50%; background:#dc2626;"></span>
                    Zomato Settlements
                </div>
                <span style="font-size:14px; color:#64748b;">
                    {{ $zomatoSettlements->total() }} total
                </span>
            </div>

            @if($zomatoSettlements->isEmpty())
                <div class="card" style="padding:32px; text-align:center;">
                    <p style="color:#64748b;">No Zomato settlements found.</p>
                    <p style="color:#64748b; font-size:13px; margin-top:4px;">Click "Generate Settlements" to create from sales data.</p>
                </div>
            @else
                <div class="card" style="padding:0; overflow:hidden;">
                    <div style="max-height:480px; overflow-y:auto;">
                        <table class="data-table w-full">
                            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                                <tr>
                                    <th class="px-6 py-4 text-left">Period</th>
                                    <th class="px-6 py-4 text-right">Gross</th>
                                    <th class="px-6 py-4 text-right">Est. Commission</th>
                                    <th class="px-6 py-4 text-right">Est. Net</th>
                                    <th class="px-6 py-4 text-center">Expected</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zomatoSettlements as $settlement)
                                <tr style="border-bottom:1px solid #f8fafc;">
                                    <td class="px-6 py-4">
                                        <div style="font-weight:500;">
                                            {{ $settlement->period_from->format('d M') }} - {{ $settlement->period_to->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        ₹{{ number_format($settlement->gross_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right" style="color:#dc2626;">
                                        -₹{{ number_format($settlement->estimated_commission, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold" style="color:#16a34a;">
                                        ₹{{ number_format($settlement->estimated_net, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center" style="color:#64748b;">
                                        {{ $settlement->expected_credit_date->format('d M') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($settlement->status === 'pending')
                                            <span class="badge badge-pending"">Pending</span>
                                        @elseif($settlement->status === 'received')
                                            <span class="badge badge-received"">Received</span>
                                        @else
                                            <span class="badge badge-disputed"">Disputed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($settlement->status === 'received')
                                            <div style="font-size:11px; margin-bottom:4px;">
                                                @php
                                                    $variance = $settlement->actual_amount_received - $settlement->estimated_net;
                                                @endphp
                                                @if($variance > 0)
                                                    <span style="color:#16a34a;">+₹{{ number_format($variance, 0) }}</span>
                                                @elseif($variance < 0)
                                                    <span style="color:#dc2626;">-₹{{ number_format(abs($variance), 0) }}</span>
                                                @else
                                                    <span style="color:#64748b;">✓ exact</span>
                                                @endif
                                            </div>
                                        @endif
                                        <div style="display:flex; justify-content:center; gap:8px;">
                                            @if($settlement->status === 'pending')
                                                <button onclick="openMarkReceivedModal({{ $settlement->id }}, '{{ $settlement->platform }}', {{ $settlement->gross_amount }})"
                                                        class="btn-secondary" btn-sm">
                                                    Mark Received
                                                </button>
                                            @endif
                                             <button onclick="openEditModal({{ $settlement->id }}, '{{ $settlement->platform }}', '{{ $settlement->period_from->format("d M Y") }}', '{{ $settlement->period_to->format("d M Y") }}', {{ $settlement->actual_amount_received ?? 'null' }}, '{{ $settlement->actual_credit_date ? $settlement->actual_credit_date->format("Y-m-d") : '' }}', {{ json_encode($settlement->notes) }}, '{{ $settlement->status }}')"
                                                     class="btn-secondary" btn-sm">
                                                 Edit
                                             </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div style="margin-top:16px;">
                    {{ $zomatoSettlements->links() }}
                </div>
            @endif
        </div>

        <!-- Credit Sales Section -->
        <div style="margin-top:24px;">
            <div class="card" style="padding:0; overflow:hidden;">
                <div style="padding:20px; border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="width:10px; height:10px; border-radius:50%; background:#f59e0b;"></span>
                        <h2 style="font-size:18px; font-weight:700; margin:0;">Credit Sales</h2>
                    </div>
                </div>
                
                @if($creditSales->count() > 0)
                <div style="max-height:400px; overflow-y:auto;">
                    <table class="data-table w-full">
                        <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                            <tr>
                                <th style="text-align:left !important; padding:12px 16px;">Name</th>
                                <th style="text-align:left !important; padding:12px 16px;">Phone</th>
                                <th style="text-align:left !important; padding:12px 16px;">Date</th>
                                <th style="text-align:right !important; padding:12px 16px;">Amount</th>
                                <th style="text-align:left !important; padding:12px 16px;">Notes</th>
                                <th style="text-align:center !important; padding:12px 16px;">Status</th>
                                <th style="text-align:center !important; padding:12px 16px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($creditSales as $credit)
                            <tr style="border-bottom:1px solid #f8fafc;">
                                <td style="text-align:left !important; padding:12px 16px; font-weight:500;">
                                    {{ $credit->customer_name ?? $credit->platform }}
                                </td>
                                <td style="text-align:left !important; padding:12px 16px;">
                                    {{ $credit->customer_phone ?? '-' }}
                                </td>
                                <td style="text-align:left !important; padding:12px 16px;">
                                    {{ $credit->sale_date->format('d M Y') }}
                                </td>
                                <td style="text-align:right !important; padding:12px 16px; font-weight:600;">
                                    ₹{{ number_format($credit->gross_amount, 2) }}
                                </td>
                                <td style="text-align:left !important; padding:12px 16px; color:#64748b; font-size:12px;">
                                    {{ $credit->customer_notes ?? '-' }}
                                </td>
                                <td style="text-align:center !important; padding:12px 16px;">
                                    @if($credit->settlement_status === 'received')
                                        <span class="badge badge-received"">Received</span>
                                    @else
                                        <span class="badge badge-pending"">Pending</span>
                                    @endif
                                </td>
                                <td style="text-align:center !important; padding:12px 16px;">
                                    @if($credit->settlement_status === 'pending')
                                        <form action="{{ route('creditSales.markReceived', $credit) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="received_date" value="{{ today()->toDateString() }}">
                                            <button type="submit" class="btn-secondary" btn-sm">Mark Received</button>
                                        </form>
                                    @else
                                        <span style="color:#64748b; font-size:11px;">
                                            {{ $credit->actual_settlement_date ? \Carbon\Carbon::parse($credit->actual_settlement_date)->format('d M Y') : '' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="padding:32px; text-align:center; color:#64748b;">
                    No credit sales recorded yet.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="markReceivedModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); align-items:center; justify-content:center; z-index:50;">
    <div class="card" style="padding:24px; width:380px;">
        <h3 style="font-weight:700; margin-bottom:16px;" id="modalTitle">Mark Settlement as Received</h3>
        <form id="markReceivedForm" method="POST">
            @csrf
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="form-label">Amount Received (₹)</label>
                    <input type="number" step="0.01" name="actual_amount_received" id="amountReceived"
                           class="form-input" style="width:100%;"
                           required>
                    <p style="font-size:11px; color:#64748b; margin-top:4px;">Gross: ₹<span id="modalGross">0.00</span></p>
                </div>
                <div>
                    <label class="form-label">Credit Date</label>
                    <input type="date" name="actual_credit_date" 
                           class="form-input" style="width:100%;"
                           required>
                </div>
                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2" 
                              class="form-textarea" style="width:100%;"
                              placeholder="Any discrepancies..."></textarea>
                </div>
            </div>
            <div style="display:flex; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-primary"" style="flex:1; background:#16a34a;">
                    Mark Received
                </button>
                <button type="button" onclick="closeMarkReceivedModal()" 
                        class="btn-secondary"" style="flex:1;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editSettlementModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); align-items:center; justify-content:center; z-index:50;">
    <div class="card" style="padding:24px; width:380px;">
        <h3 style="font-weight:700; margin-bottom:16px;" id="editModalTitle">Edit Settlement</h3>
        <form id="editSettlementForm" method="POST">
            @csrf
            @method('PUT')
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="form-label">Actual Amount Received (₹)</label>
                    <input type="number" step="0.01" name="actual_amount_received" id="editAmountReceived"
                           class="form-input" style="width:100%;">
                </div>
                <div>
                    <label class="form-label">Actual Credit Date</label>
                    <input type="date" name="actual_credit_date" id="editCreditDate"
                           class="form-input" style="width:100%;">
                </div>
                <div>
                    <label class="form-label">Deduction Breakdown (Notes)</label>
                    <textarea name="notes" id="editNotes" rows="2" 
                              class="form-textarea" style="width:100%;"
                              placeholder="Marketing ₹200, Click charges ₹150..."></textarea>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-select" style="width:100%;">
                        <option value="pending">Pending</option>
                        <option value="received">Received</option>
                        <option value="disputed">Disputed</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-primary"" style="flex:1; background:#3b82f6;">
                    Update Settlement
                </button>
                <button type="button" onclick="closeEditModal()" 
                        class="btn-secondary"" style="flex:1;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openMarkReceivedModal(settlementId, platform, grossAmount) {
        const form = document.getElementById('markReceivedForm');
        form.action = `/settlements/${settlementId}/mark-received`;
        
        document.getElementById('modalTitle').textContent = `Mark ${platform.charAt(0).toUpperCase() + platform.slice(1)} Settlement as Received`;
        document.getElementById('modalGross').textContent = grossAmount.toFixed(2);
        
        document.querySelector('input[name="actual_credit_date"]').value = new Date().toISOString().split('T')[0];
        
        document.getElementById('markReceivedModal').style.display = 'flex';
    }

    function closeMarkReceivedModal() {
        document.getElementById('markReceivedModal').style.display = 'none';
    }

    function openEditModal(id, platform, periodFrom, periodTo, actualAmount, actualDate, notes, status) {
        const form = document.getElementById('editSettlementForm');
        form.action = `/settlements/${id}`;
        
        document.getElementById('editModalTitle').textContent = `Edit Settlement — ${platform.charAt(0).toUpperCase() + platform.slice(1)} ${periodFrom} to ${periodTo}`;
        
        document.getElementById('editAmountReceived').value = actualAmount !== null ? actualAmount : '';
        document.getElementById('editCreditDate').value = actualDate || new Date().toISOString().split('T')[0];
        document.getElementById('editNotes').value = notes || '';
        document.getElementById('editStatus').value = status;
        
        document.getElementById('editSettlementModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editSettlementModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('markReceivedModal').addEventListener('click', function(e) {
        if (e.target === this) closeMarkReceivedModal();
    });
    document.getElementById('editSettlementModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endsection
