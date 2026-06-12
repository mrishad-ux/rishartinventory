@extends('layouts.app')

@section('title', 'Sales')

@section('content')

<div style="padding:0">
    <!-- Today's Summary -->
    <div x-data="{ open: false }" style="margin-bottom:16px;">
        <div class="card-title" style="margin-bottom:12px">
            <div @click="open = !open" style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                <span>TODAY'S SALES</span>
                <span style="font-weight:400; font-size:13px; color:#475569;">
                    ₹{{ number_format($todayCash + $todayGP + $todayOnline + $creditSalesToday, 2) }}
                </span>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px; height:16px; margin-left:auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        <div x-show="open" x-transition.duration.300ms class="grid grid-cols-3 gap-4" style="margin-top:14px;">
            <div class="card" s1" style="padding:20px; margin-bottom:0">
                <div class="stat-label">CASH</div>
                <div class="stat-value">₹{{ number_format($todayCash, 2) }}</div>
            </div>
            <div class="card" s3" style="padding:20px; margin-bottom:0">
                <div class="stat-label">GOOGLE PAY</div>
                <div class="stat-value">₹{{ number_format($todayGP, 2) }}</div>
            </div>
            <div class="card" s4" style="padding:20px; margin-bottom:0">
                <div class="stat-label">ONLINE (GROSS)</div>
                <div class="stat-value">₹{{ number_format($todayOnline, 2) }}</div>
            </div>
            <div class="card" style="padding:20px; margin-bottom:0; border-color:var(--accent);">
                <div class="stat-label">ACTUAL IN HAND</div>
                <div class="stat-value">₹{{ number_format($todayActual, 2) }}</div>
            </div>
            <div class="card" style="padding:20px; margin-bottom:0; border-color:#a78bfa;">
                <div class="stat-label">CREDIT SALES</div>
                <div class="stat-value">₹{{ number_format($creditSalesToday, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Month Summary -->
    <div x-data="{ open: false }" style="margin-bottom:24px;">
        <div class="card-title" style="margin-bottom:12px">
            <div @click="open = !open" style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                <span>THIS MONTH</span>
                <span style="font-weight:400; font-size:13px; color:#475569;">
                    ₹{{ number_format($monthCash + $monthGP + $monthOnline + $creditSalesToday, 2) }}
                </span>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px; height:16px; margin-left:auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        <div x-show="open" x-transition.duration.300ms class="grid grid-cols-3 gap-4" style="margin-top:14px;">
            <div class="card" s1" style="padding:20px; margin-bottom:0">
                <div class="stat-label">CASH</div>
                <div class="stat-value">₹{{ number_format($monthCash, 2) }}</div>
            </div>
            <div class="card" s3" style="padding:20px; margin-bottom:0">
                <div class="stat-label">GOOGLE PAY</div>
                <div class="stat-value">₹{{ number_format($monthGP, 2) }}</div>
            </div>
            <div class="card" s4" style="padding:20px; margin-bottom:0">
                <div class="stat-label">ONLINE (GROSS)</div>
                <div class="stat-value">₹{{ number_format($monthOnline, 2) }}</div>
            </div>
            <div class="card" style="padding:20px; margin-bottom:0; border-color:var(--accent);">
                <div class="stat-label">ACTUAL RECEIVED</div>
                <div class="stat-value">₹{{ number_format($monthActual, 2) }}</div>
            </div>
            <div class="card" style="padding:20px; margin-bottom:0; border-color:#f59e0b;">
                <div class="stat-label">ONLINE PENDING</div>
                <div class="stat-value">₹{{ number_format($onlinePendingMonth, 2) }}</div>
            </div>
            <div class="card" style="padding:20px; margin-bottom:0; border-color:#10b981;">
                <div class="stat-label">ONLINE CREDITED</div>
                <div class="stat-value">₹{{ number_format($onlineCreditedMonth, 2) }}</div>
            </div>
        </div>
    </div>

{{-- Sales Filter --}}
<div class="card" style="padding:20px; margin-bottom:20px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
        <div class="card-title" style="margin-bottom:0;">📊 CUSTOM DATE RANGE</div>
    </div>
    <div style="display:flex; gap:16px; align-items:end; flex-wrap:wrap;">
        <div style="flex:1; min-width:160px;">
            <label class="form-label">From</label>
            <input type="date" id="filter-date-from" class="form-input" value="{{ now()->startOfMonth()->toDateString() }}">
        </div>
        <div style="flex:1; min-width:160px;">
            <label class="form-label">To</label>
            <input type="date" id="filter-date-to" class="form-input" value="{{ now()->toDateString() }}">
        </div>
        <div style="display:flex; gap:12px; align-items:center;">
            <label class="checkbox-label" style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px;">
                <input type="checkbox" id="filter-cash" checked> 💵 Cash
            </label>
            <label class="checkbox-label" style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px;">
                <input type="checkbox" id="filter-gp" checked> 📱 GP
            </label>
            <label class="checkbox-label" style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px;">
                <input type="checkbox" id="filter-swiggy" checked> 🟢 Swiggy
            </label>
            <label class="checkbox-label" style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px;">
                <input type="checkbox" id="filter-zomato" checked> 🔴 Zomato
            </label>
        </div>
        <button onclick="applyFilter()" class="btn-primary"" style="padding:10px 24px;">🔍 Search</button>
    </div>

    {{-- Filter Results --}}
    <div id="filter-results" style="display:none; margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0;">
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:16px;" id="filter-totals-container">
            <!-- Populated by JS -->
        </div>
        {{-- Grand Total --}}
        <div id="filter-grand-total" style="display:none; background:linear-gradient(135deg, rgba(245,158,11,0.08), #f8fafc); border:1px solid rgba(245,158,11,0.2); border-radius:12px; padding:14px 20px; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:18px;">📊</span>
                <span style="font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#475569;">Grand Total (Filtered)</span>
            </div>
            <span id="filter-grand-total-value" style="font-size:22px; font-weight:800; color:var(--accent);">₹0</span>
        </div>
    </div>
</div>

{{-- Date-wise Detail Modal --}}
<div id="filter-detail-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; width:90%; max-width:600px; max-height:80vh; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.5);">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #e2e8f0;">
            <div>
                <span style="font-weight:600;" id="detail-modal-title">Details</span>
            </div>
            <button onclick="closeFilterDetailModal()" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:20px; padding:4px;">✕</button>
        </div>
        <div style="overflow-y:auto; max-height:calc(80vh - 120px);">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b;">
                        <th style="padding:10px 12px; text-align:left;">Date</th>
                        <th style="padding:10px 12px; text-align:right;">Gross</th>
                        <th style="padding:10px 12px; text-align:right;">Net</th>
                    </tr>
                </thead>
                <tbody id="detail-modal-tbody"></tbody>
            </table>
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-top:1px solid #e2e8f0;">
            <span style="font-size:13px; color:#64748b;">Total</span>
            <span id="detail-modal-total" style="font-size:20px; font-weight:700; color:var(--accent);">₹0</span>
        </div>
    </div>
</div>

    @if($pendingSettlement > 0)
    <div class="alert-warning" style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
        ⏳ Pending Online Settlement: ₹{{ number_format($pendingSettlement, 2) }} — expected from Swiggy/Zomato
    </div>
    @endif

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h1 class="page-title">Sales</h1>
            <p class="page-subtitle" style="font-family:'Syne',sans-serif; font-size:13px; color:#64748b; margin:4px 0 0;">Daily sales records</p>
        </div>
        <button onclick="openBulkSaleModal()" class="btn-primary"">+ Record Sales</button>
    </div>

    @if($sales->count() > 0)
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="max-height:480px; overflow-y:auto;">
            <table class="data-table w-full">
                <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                <tr>
                    <th class="text-left" style="text-align:left !important; padding:12px 16px;">Date</th>
                    <th class="text-left" style="text-align:left !important; padding:12px 16px;">Type</th>
                    <th class="text-left" style="text-align:left !important; padding:12px 16px;">Platform</th>
                    <th class="text-right" style="text-align:right !important; padding:12px 16px;">Gross</th>
                    <th class="text-right" style="text-align:right !important; padding:12px 16px;">Commission</th>
                    <th class="text-right" style="text-align:right !important; padding:12px 16px;">Net</th>
                    <th class="text-center" style="text-align:center !important; padding:12px 16px;">Settlement</th>
                    <th class="text-center" style="text-align:center !important; padding:12px 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td class="text-left" style="text-align:left !important; padding:12px 16px;">{{ $sale->sale_date->format('d M Y') }}</td>
                    <td class="text-left" style="text-align:left !important; padding:12px 16px;">
                        @if($sale->sale_type == 'cash')
                            <span class="badge badge-cash">CASH</span>
                        @elseif($sale->sale_type == 'gp')
                            <span class="badge badge-gp">GP</span>
                        @elseif($sale->sale_type == 'swiggy')
                            <span class="badge badge-swiggy">SWIGGY</span>
                        @elseif($sale->sale_type == 'zomato')
                            <span class="badge badge-zomato">ZOMATO</span>
                        @elseif($sale->sale_type == 'other')
                            <span class="badge" style="background:rgba(168,85,247,0.15); color:#a78bfa; border:1px solid rgba(168,85,247,0.3);">OTHER</span>
                        @endif
                    </td>
                    <td class="text-left" style="text-align:left !important; padding:12px 16px;">{{ $sale->platform ?? '-' }}</td>
                    <td class="text-right" style="text-align:right !important; padding:12px 16px;">₹{{ number_format($sale->gross_amount, 2) }}</td>
                    <td class="text-right" style="text-align:right !important; padding:12px 16px;">
                        {{ $sale->commission_percent > 0 ? $sale->commission_percent.'%' : '-' }}
                    </td>
                    <td class="text-right" style="text-align:right !important; padding:12px 16px; font-weight:600;">₹{{ number_format($sale->net_amount, 2) }}</td>
                    <td class="text-center" style="text-align:center !important; padding:12px 16px;">
                        @if($sale->settlement_status == 'not_applicable')
                            <span style="color:#94a3b8; font-size:11px;">—</span>
                        @elseif($sale->settlement_status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @else
                            <span class="badge badge-success">Received</span>
                        @endif
                    </td>
                    <td class="text-center" style="text-align:center !important; padding:12px 16px;">
                        <a href="{{ route('sales.show', $sale) }}" class="btn-secondary" btn-sm" style="margin-right:8px;">View</a>
                        <a href="{{ route('sales.edit', $sale) }}" class="btn-secondary" btn-sm" style="margin-right:8px;">Edit</a>
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this sale?')" class="btn-danger btn-sm">Delete</button>
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
        <p style="color:#64748b; font-size:16px;">No sales recorded yet.</p>
        <a href="{{ route('sales.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
            Record First Sale
        </a>
    </div>
    @endif
</div>

<!-- Bulk Sale Modal -->
<div id="bulkSaleModal" class="hidden" style="position:fixed; inset:0; background:rgba(0,0,0,0.7); align-items:center; justify-content:center; z-index:50;">
    <div class="card" style="padding:24px; width:500px; max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="font-weight:700; font-size:18px; margin:0;">Record Sales</h3>
            <button onclick="closeBulkSaleModal()" style="background:none; border:none; color:#64748b; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        
        <form id="bulkSaleForm" method="POST" action="{{ route('sales.bulkStore') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="sale_date" id="bulkSaleDate" class="form-input" value="{{ today()->toDateString() }}" required>
            </div>

            <div style="border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:16px;">
                <div class="card-title" style="margin-bottom:12px;">STANDARD CHANNELS</div>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Cash Sale (₹)</label>
                        <input type="number" step="0.01" min="0" name="cash_amount" class="form-input sale-input" data-type="cash" placeholder="0.00">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Google Pay (₹)</label>
                        <input type="number" step="0.01" min="0" name="gp_amount" class="form-input sale-input" data-type="gp" placeholder="0.00">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Swiggy Gross (₹)</label>
                        <input type="number" step="0.01" min="0" name="swiggy_gross" class="form-input sale-input" data-type="swiggy" placeholder="0.00">
                        <p style="font-size:10px; color:#64748b; margin-top:4px;">31% commission auto-calculated</p>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Zomato Gross (₹)</label>
                        <input type="number" step="0.01" min="0" name="zomato_gross" class="form-input sale-input" data-type="zomato" placeholder="0.00">
                        <p style="font-size:10px; color:#64748b; margin-top:4px;">31% commission auto-calculated</p>
                    </div>
                </div>
            </div>

            <div style="border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <div class="card-title" style="margin-bottom:0;">CREDIT SALES</div>
                    <button type="button" onclick="addCreditEntry()" class="btn-secondary" btn-sm">+ Add Entry</button>
                </div>
                
                <div id="creditEntriesContainer">
                    <!-- Dynamic credit entries will be added here -->
                </div>
            </div>

            <div style="background:#f8fafc; border:1px solid var(--accent); border-radius:10px; padding:16px; margin-bottom:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-weight:600; color:#475569;">TOTAL</span>
                    <span id="bulkTotalAmount" style="font-size:24px; font-weight:700; color:var(--accent);">₹0.00</span>
                </div>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary"" style="flex:1;">Save All Sales</button>
                <button type="button" onclick="closeBulkSaleModal()" class="btn-secondary"" style="flex:1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
let otherEntryCount = 0;

function openBulkSaleModal() {
    document.getElementById('bulkSaleModal').classList.remove('hidden');
    document.getElementById('bulkSaleDate').value = new Date().toISOString().split('T')[0];
    calculateBulkTotal();
}

function closeBulkSaleModal() {
    document.getElementById('bulkSaleModal').classList.add('hidden');
    document.getElementById('bulkSaleForm').reset();
    document.getElementById('creditEntriesContainer').innerHTML = '';
    creditEntryCount = 0;
    calculateBulkTotal();
}

let creditEntryCount = 0;

function addCreditEntry() {
    creditEntryCount++;
    const container = document.getElementById('creditEntriesContainer');
    const div = document.createElement('div');
    div.style.cssText = 'display:grid; grid-template-columns:2fr 1.5fr 1.5fr 2fr auto; gap:8px; align-items:center; margin-bottom:12px; padding:12px; background:#f8fafc; border-radius:8px;';
    div.innerHTML = `
        <div>
            <input type="text" name="credit_entries[${creditEntryCount}][name]" class="form-input" placeholder="Name *" required style="width:100%;">
        </div>
        <div>
            <input type="text" name="credit_entries[${creditEntryCount}][phone]" class="form-input" placeholder="Phone" style="width:100%;">
        </div>
        <div>
            <input type="number" step="0.01" min="0" name="credit_entries[${creditEntryCount}][amount]" class="form-input credit-amount" placeholder="Amount *" required style="width:100%;">
        </div>
        <div>
            <input type="text" name="credit_entries[${creditEntryCount}][notes]" class="form-input" placeholder="e.g. Birthday party" style="width:100%;">
        </div>
        <div>
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateBulkTotal();" style="background:none; border:none; color:#dc2626; font-size:18px; cursor:pointer; padding:4px;">&times;</button>
        </div>
    `;
    container.appendChild(div);
}

function calculateBulkTotal() {
    let total = 0;
    
    // Standard channels (cash, gp, swiggy, zomato)
    document.querySelectorAll('#bulkSaleForm .sale-input').forEach(input => {
        const val = parseFloat(input.value) || 0;
        total += val;
    });
    
    // Credit entries - only count each credit amount once
    document.querySelectorAll('#creditEntriesContainer .credit-amount').forEach(input => {
        const val = parseFloat(input.value) || 0;
        total += val;
    });
    
    document.getElementById('bulkTotalAmount').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

// Use event delegation for all inputs in the form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulkSaleForm');
    
    // Delegate input events for standard inputs
    document.querySelectorAll('.sale-input').forEach(input => {
        input.addEventListener('input', calculateBulkTotal);
    });
    
    // Use event delegation for credit entries container
    const creditContainer = document.getElementById('creditEntriesContainer');
    if (creditContainer) {
        creditContainer.addEventListener('input', function(e) {
            if (e.target.matches('.credit-amount')) {
                calculateBulkTotal();
            }
        });
    }
});

// Close modal on backdrop click
document.getElementById('bulkSaleModal').addEventListener('click', function(e) {
    if (e.target === this) closeBulkSaleModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeBulkSaleModal();
});
</script>

<script>
function applyFilter() {
    const from = document.getElementById('filter-date-from').value;
    const to = document.getElementById('filter-date-to').value;
    if (!from || !to) { alert('Please select date range'); return; }
    
    const types = [];
    if (document.getElementById('filter-cash').checked) types.push('cash');
    if (document.getElementById('filter-gp').checked) types.push('gp');
    if (document.getElementById('filter-swiggy').checked) types.push('swiggy');
    if (document.getElementById('filter-zomato').checked) types.push('zomato');
    
    if (types.length === 0) { alert('Please select at least one type'); return; }
    
    fetch(`{{ route('sales.filter') }}?date_from=${from}&date_to=${to}&types[]=${types.join('&types[]=')}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('filter-totals-container');
            container.innerHTML = '';
            
            const labels = { cash: '💵 Cash', gp: '📱 GP', swiggy: '🟢 Swiggy', zomato: '🔴 Zomato' };
            
            types.forEach(type => {
                const t = data.totals[type] || { gross_amount: 0, net_amount: 0, count: 0 };
                const card = document.createElement('div');
                card.style.cssText = 'background:#f8fafc; border-radius:10px; padding:14px; border:1px solid #e2e8f0; cursor:pointer; transition:all 0.2s;';
                card.onmouseover = () => { card.style.borderColor = 'var(--accent)'; };
                card.onmouseout = () => { card.style.borderColor = '#e2e8f0'; };
                card.onclick = () => openFilterDetail(type, labels[type], data.date_wise);
                card.innerHTML = `
                    <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; margin-bottom:8px;">${labels[type]}</div>
                    <div style="font-size:20px; font-weight:700;">₹${Number(t.gross_amount).toLocaleString('en-IN')}</div>
                    <div style="font-size:11px; color:#64748b; margin-top:4px;">
                        ${type === 'swiggy' || type === 'zomato' ? `Net: ₹${Number(t.net_amount).toLocaleString('en-IN')} (${t.count} days)` : `${t.count} days`}
                    </div>
                `;
                container.appendChild(card);
            });
            
            document.getElementById('filter-results').style.display = 'block';
            // Show grand total
            document.getElementById('filter-grand-total').style.display = 'flex';
            document.getElementById('filter-grand-total-value').textContent = '₹' + Number(data.grand_total).toLocaleString('en-IN');
        })
        .catch(() => alert('Error fetching filter data'));
}

function openFilterDetail(type, title, dateWise) {
    const rows = dateWise.filter(d => d.sale_type === type);
    const tbody = document.getElementById('detail-modal-tbody');
    tbody.innerHTML = '';
    let total = 0;
    
    rows.forEach(d => {
        total += parseFloat(d.gross_amount);
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid #f8fafc';
        tr.innerHTML = `
            <td style="padding:10px 12px; font-size:13px;">${d.sale_date}</td>
            <td style="padding:10px 12px; font-size:13px; text-align:right;">₹${Number(d.gross_amount).toLocaleString('en-IN')}</td>
            <td style="padding:10px 12px; font-size:13px; text-align:right; color:#475569;">₹${Number(d.net_amount).toLocaleString('en-IN')}</td>
        `;
        tbody.appendChild(tr);
    });
    
    document.getElementById('detail-modal-title').textContent = title + ' — Date-wise';
    document.getElementById('detail-modal-total').textContent = '₹' + total.toLocaleString('en-IN');
    document.getElementById('filter-detail-modal').style.display = 'flex';
}

function closeFilterDetailModal() {
    document.getElementById('filter-detail-modal').style.display = 'none';
}

// Close on backdrop click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('filter-detail-modal');
    if (e.target === modal) modal.style.display = 'none';
});
</script>

@endsection