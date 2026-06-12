@extends('layouts.app')

@section('title', 'Daily Stock Entry')

@section('content')
<div>
    {{-- Sticky Page Title & Buttons Bar --}}
    <div class="card" style="margin-bottom:16px; padding:14px 20px">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
            <div>
                <h1 class="page-title">Daily Stock Entry</h1>
                <p class="page-subtitle" style="margin:4px 0 0">Update opening, purchased, consumption and wastage for each item</p>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                <form method="GET" action="{{ route('inventory.daily') }}" style="display:flex; align-items:center; gap:8px;">
                    <input type="date" id="log-date" name="date" value="{{ $date ?? today()->toDateString() }}"
                        style="border:1px solid #e2e8f0; border-radius:6px; padding:8px 12px; font-size:13px; background:#f8fafc; color:#1e293b;"
                        onchange="this.form.submit()">
                </form>
                <a href="{{ route('inventory.history') }}" class="btn-secondary">
                    📊 History
                </a>
                <a href="{{ route('inventory.master') }}" class="btn-secondary">
                    ⚙ Item Master
                </a>
                <button type="button" onclick="sendWhatsAppAlert()" class="btn-primary">
                    📲 Send Purchase Alert
                </button>
            </div>
        </div>
    </div>

    {{-- Sticky Date Banner --}}
    <div style="background:#f8fafc; border:1px solid #e2e8f0; 
           border-radius:10px; padding:10px 16px; margin-bottom:16px; 
           display:flex; align-items:center; gap:10px;">
        <span style="font-size:18px;">📅</span>
        <span style="font-weight:600;">
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
            @if($date === today()->toDateString())
                <span class="today-pill">TODAY</span>
            @endif
        </span>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert-success" style="margin:16px 24px 8px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Warning Message (stock reminders) --}}
    @if(session('warning'))
        <div style="background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #d97706; color:#92400e; padding:14px 18px; border-radius:10px; margin:16px 24px 8px; font-size:13px; line-height:1.5; display:flex; align-items:flex-start; gap:12px;">
            <span style="font-size:18px; flex-shrink:0;">💡</span>
            <div style="flex:1; white-space:pre-line;">{{ session('warning') }}</div>
        </div>
    @endif

    {{-- Gas Cylinder & Electricity Section --}}
    <div style="padding:0 24px 16px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
            {{-- Gas Cylinder card --}}
            <div class="card" style="padding:16px; margin-bottom:0;">
                <div style="display:flex; align-items:center; justify-content:space-between; height:100%;">
                    <div>
                        <p style="font-weight:600;">🔥 Gas Cylinder</p>
                        @if($lastGasChange)
                            <p style="font-size:11px; color:#94a3b8;">
                                Last changed: 
                                <span style="font-weight:500;">
                                    {{ \Carbon\Carbon::parse($lastGasChange)->format('d M Y') }}
                                </span>
                                &nbsp;|&nbsp;
                                <span style="{{ $daysSinceGasChange >= 4 ? 'color:#dc2626; font-weight:600;' : 'color:#16a34a;' }}">
                                    {{ $daysSinceGasChange }} day{{ $daysSinceGasChange != 1 ? 's' : '' }} ago
                                </span>
                                @if($daysSinceGasChange >= 4)
                                    <span style="color:#dc2626; font-weight:600;">⚠ Change due!</span>
                                @endif
                            </p>
                        @else
                            <p style="font-size:11px; color:#94a3b8;">No gas change recorded yet</p>
                        @endif
                    </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <span id="gas-status-text" style="font-size:13px; font-weight:500; color:#64748b;">
                        @if($gasChanged === true)
                            ✅ Changed today
                        @elseif($gasChanged === false)
                            ❌ No change
                        @else
                            Not recorded
                        @endif
                    </span>
                    <button id="gas-yes-btn" onclick="setGas(true)" class="btn-secondary btn-sm">
                        ✅ Yes
                    </button>
                    <button id="gas-no-btn" onclick="setGas(false)" class="btn-secondary btn-sm">
                        ❌ No
                    </button>
                 </div>
              </div>
              </div>

             {{-- Electricity Tracker card --}}
            <div class="card" style="padding:16px; margin-bottom:0;"
                 x-data="{ 
                     reading: '{{ $electricityReading ?? '' }}',
                     units: '{{ $unitsConsumed ?? '' }}',
                     saved: {{ $electricityReading ? 'true' : 'false' }}
                 }">
                <div style="display:flex; align-items:center; justify-content:space-between; height:100%;">
                    <div>
                        <p style="font-weight:600;">⚡ Electricity Meter</p>
                        <p style="font-size:11px; color:#94a3b8;">
                            @if($prevElectricityReading)
                                Prev reading: 
                                <span style="font-weight:500;">
                                    {{ number_format($prevElectricityReading, 0) }} units
                                </span>
                                ({{ \Carbon\Carbon::parse($prevElectricityDate)->format('d M') }})
                            @else
                                Enter today's meter reading
                            @endif
                        </p>
                    </div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        {{-- Units consumed badge --}}
                        <div x-show="units !== ''" style="display:flex; align-items:center; gap:4px;">
                            <span style="font-size:11px; color:#94a3b8;">Consumed</span>
                            <span style="font-size:18px; font-weight:700;" x-text="units + ' u'"></span>
                        </div>

                        {{-- Reading input --}}
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" 
                                   x-model="reading"
                                   placeholder="Enter reading"
                                   style="width:144px; border:1px solid #e2e8f0; border-radius:6px; padding:8px 12px; background:#f8fafc; 
                                          font-size:13px; background:#f8fafc; color:inherit;"
                                   :class="saved ? 'border-color:rgba(74,222,128,0.4)' : ''">
                            <button onclick="saveElectricity()"
                                    class="btn-primary"" style="padding:8px 12px; font-size:13px;">
                                Save
                            </button>
                        </div>

                        {{-- Saved indicator --}}
                        <span x-show="saved" style="color:#16a34a; font-size:11px; font-weight:600;">✓ saved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Oil Consumption Card --}}
    <div style="padding:0 24px 16px;">
        <div class="card" style="padding:20px; margin-bottom:16px;">
            <div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-size:18px;">🛢️</span>
                        <span style="font-weight:600;">Oil Consumption</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="text-align:right;">
                            <span style="font-size:11px; text-transform:uppercase; letter-spacing:0.5px; color:#94a3b8;">Monthly Total</span>
                            <div>
                                <span style="font-size:22px; font-weight:700; color:var(--accent); cursor:pointer;" id="oil-monthly-total" onclick="openOilDetailModal()" title="Click to see daily breakdown">{{ number_format($monthlyTotal, 1) }}</span>
                                <span style="font-size:11px; color:#94a3b8; margin-left:4px;">packets</span>
                            </div>
                        </div>
                        <div style="width:1px; height:30px; background:rgba(226,232,240,0.6);"></div>
                        <span id="oil-status-text" style="font-size:12px; font-weight:500; color:#64748b;">
                            @if($oilL1 !== null || $oilL2 !== null || $oilR1 !== null || $oilR2 !== null || $oilMayo !== null || $oilSauces !== null)
                                ✅ Saved
                            @else
                                Not recorded
                            @endif
                        </span>
                        <button id="oil-save-btn" onclick="saveOil()" class="btn-primary btn-sm">
                            Save
                        </button>
                        <button id="oil-unlock-btn" onclick="unlockOil()" style="display:none; background:none; border:1px solid #fde68a; color:var(--accent); border-radius:6px; padding:6px 10px; font-size:12px; cursor:pointer;" title="Edit oil data">
                            ✏️ Edit
                        </button>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:12px;">
                    <div>
                        <label style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; display:block; margin-bottom:6px;">L1 (packets)</label>
                        <input type="number" id="oil-l1" step="0.5" min="0"
                               value="{{ $oilL1 ?? '' }}"
                               placeholder="0"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:6px; padding:10px 12px; font-size:14px; background:#f8fafc; color:inherit; text-align:center;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; display:block; margin-bottom:6px;">L2 (packets)</label>
                        <input type="number" id="oil-l2" step="0.5" min="0"
                               value="{{ $oilL2 ?? '' }}"
                               placeholder="0"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:6px; padding:10px 12px; font-size:14px; background:#f8fafc; color:inherit; text-align:center;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; display:block; margin-bottom:6px;">R1 (packets)</label>
                        <input type="number" id="oil-r1" step="0.5" min="0"
                               value="{{ $oilR1 ?? '' }}"
                               placeholder="0"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:6px; padding:10px 12px; font-size:14px; background:#f8fafc; color:inherit; text-align:center;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; display:block; margin-bottom:6px;">R2 (packets)</label>
                        <input type="number" id="oil-r2" step="0.5" min="0"
                               value="{{ $oilR2 ?? '' }}"
                               placeholder="0"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:6px; padding:10px 12px; font-size:14px; background:#f8fafc; color:inherit; text-align:center;">
                    </div>
                </div>
                <div style="text-align:center; margin-top:4px;">
                    <span style="font-size:10px; color:#94a3b8; display:block; text-align:center; margin-top:2px;">Palm Oil stock: {{ number_format(\App\Models\InventoryItem::find(30)?->current_stock ?? 0, 2) }} Ltr</span>
                </div>
                {{-- Mayo & Sauces --}}
                <div style="margin-top:14px; padding-top:12px; border-top:1px solid #e2e8f0;">
                    <div style="text-align:center; margin-bottom:10px;">
                        <span style="font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#94a3b8;">Condiments</span>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:12px;">
                        <div>
                            <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;display:block;margin-bottom:6px;">Mayo (packets)</label>
                            <input type="number" id="oil-mayo" step="0.5" min="0" value="{{ $oilMayo ?? '' }}" placeholder="0" style="width:100%;border:1px solid #e2e8f0;border-radius:6px;padding:10px 12px;font-size:14px;background:#f8fafc;color:inherit;text-align:center;">
                            <span style="font-size:10px; color:#94a3b8; display:block; text-align:center; margin-top:4px;">Sunflower: {{ number_format(\App\Models\InventoryItem::find(29)?->current_stock ?? 0, 2) }} Ltr</span>
                        </div>
                        <div>
                            <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;display:block;margin-bottom:6px;">Sauces (packets)</label>
                            <input type="number" id="oil-sauces" step="0.5" min="0" value="{{ $oilSauces ?? '' }}" placeholder="0" style="width:100%;border:1px solid #e2e8f0;border-radius:6px;padding:10px 12px;font-size:14px;background:#f8fafc;color:inherit;text-align:center;">
                            <span style="font-size:10px; color:#94a3b8; display:block; text-align:center; margin-top:4px;">Sunflower: {{ number_format(\App\Models\InventoryItem::find(29)?->current_stock ?? 0, 2) }} Ltr</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div style="padding:0 24px 24px;">

@foreach($categories as $catKey => $catLabel)
    @if(isset($items[$catKey]) && $items[$catKey]->count())
    <div style="margin-bottom:24px;">
        {{-- Category Header --}}
        <h2 style="color:var(--accent); font-family:'Syne',sans-serif; 
           font-size:11px; font-weight:700; letter-spacing:1px; 
           text-transform:uppercase; margin:20px 0 8px; 
           display:flex; align-items:center; gap:6px; cursor:pointer;"
           onclick="toggleCategory('category-table-{{ $catKey }}')"
           title="Click to expand"
           id="toggle-header-{{ $catKey }}">
            @if($catKey === 'shawarma_marination') 🥙
            @elseif($catKey === 'mayo_masala_sauces') 🧴
            @elseif($catKey === 'chicken_fish') 🍗
            @else 🥖 @endif
            {{ $catLabel }}
            <span id="chevron-{{ $catKey }}" style="font-size:10px; margin-left:auto; transition:transform 0.2s; display:inline-block;">▶</span>
        </h2>

        <div id="category-table-{{ $catKey }}" style="transition: opacity 0.25s ease, max-height 0.3s ease; overflow: hidden; max-height: 0px; opacity: 0;">
        <div class="card" style="padding:0; overflow:hidden; margin-bottom:16px;">
            {{-- Table Header --}}
            <div style="display:grid; grid-template-columns:repeat(12, 1fr); gap:8px; padding:8px 16px; border-bottom:1px solid #e2e8f0; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                <div style="grid-column:span 3;">Item</div>
                <div style="grid-column:span 1; text-align:center;">Unit</div>
                <div style="grid-column:span 1; text-align:center;">Opening</div>
                <div style="grid-column:span 1; text-align:center;">Purchased</div>
                <div style="grid-column:span 1; text-align:center;">Total</div>
                <div style="grid-column:span 1; text-align:center;">Wastage</div>
                <div style="grid-column:span 1; text-align:center;">Closing</div>
                <div style="grid-column:span 1; text-align:center;">Consumed</div>
                <div style="grid-column:span 2; text-align:center;">Action</div>
            </div>

            @foreach($items[$catKey] as $item)
            @php
                $log = $item->logs->first();
                $opening     = $log->opening     ?? 0;
                $purchased   = $log->purchased   ?? 0;
                $consumption = $log->consumption ?? 0;
                $wastage     = $log->wastage     ?? 0;
                $closingRaw  = $opening + $purchased - $consumption - $wastage;
                // Only show closing value if consumption OR wastage has been entered
                $closing = ($consumption > 0 || $wastage > 0) ? $closingRaw : 0;
                
                $openingSource = $log->opening_source ?? 'default';
                $isSavedToday = $log !== null;
                if ($openingSource === 'manual') {
                    $openingClass = 'bg-amber-100 border-amber-400';
                    $openingTitle = 'Opening manually edited by staff';
                } elseif ($openingSource === 'auto') {
                    $openingClass = 'bg-blue-100 border-blue-400';
                    $openingTitle = 'Opening auto-updated from previous day closing';
                } else {
                    $openingClass = 'bg-gray-100 border-gray-200';
                    $openingTitle = 'Auto-filled from previous day closing';
                }
                $openingReadonly = $openingSource === 'manual' ? '' : 'readonly';
                $purchasedStyle = $isSavedToday ? 'border:2px solid #16a34a; background-color:#f0fdf4;' : '';
                $closingStyle = $isSavedToday ? 'border:2px solid #16a34a; background-color:#f0fdf4;' : '';
            @endphp

            <div style="display:grid; grid-template-columns:repeat(12, 1fr); gap:8px; padding:12px 16px; border-bottom:1px solid #e2e8f0;" id="row-{{ $item->id }}" data-item-name="{{ $item->name }}">

                {{-- Item name --}}
                <div style="grid-column:span 3;">
                    <span style="font-weight:500;">{{ $item->name }}</span>
                    @if($log) <span style="color:#16a34a; font-size:11px; font-weight:600; margin-left:4px;">✓ saved</span> @endif
                </div>

                {{-- Unit --}}
                <div style="grid-column:span 1; text-align:center; font-size:13px; color:#64748b;">{{ $item->unit }}</div>

                {{-- Input form --}}
                <form id="form-{{ $item->id }}"
                      action="{{ route('inventory.saveLog', $item) }}"
                      method="POST"
                      class="contents">
                    @csrf
                    <input type="hidden" name="log_date" value="{{ $date }}">
                    <input type="hidden" name="opening_source" id="opening_source-{{ $item->id }}" value="{{ $openingSource }}">

                    {{-- Opening --}}
                    <div style="grid-column:span 1; display:flex; align-items:center; gap:2px;">
                        <input type="number" name="opening" step="0.01" min="0"
                               id="opening-{{ $item->id }}"
                               value="{{ $log ? $log->opening : ($item->auto_opening ?? 0) }}"
                               class="stock-input"
                               style="width:100%; text-align:center;"
                               {{ $openingReadonly }}
                               data-opening-source="{{ $openingSource }}"
                               {{ $openingSource === 'manual' ? 'data-manually-edited=true' : '' }}
                               title="{{ $openingTitle }}"
                               oninput="calcRow({{ $item->id }})">
                        <button type="button" id="unlock-btn-{{ $item->id }}" onclick="toggleOpeningLock({{ $item->id }})"
                                style="color:#94a3b8; font-size:11px; padding:0 2px;"
                                title="Edit opening">
                            ✏
                        </button>
                    </div>

                    {{-- Purchased --}}
                    <div style="grid-column:span 1;">
                        <input type="number" name="purchased" step="0.01" min="0"
                               id="purchased-{{ $item->id }}"
                               value="{{ $purchased }}"
                               class="stock-input"
                               style="width:100%; text-align:center; {{ $purchasedStyle }}"
                               oninput="calcRow({{ $item->id }})">
                    </div>

                    {{-- Total (readonly) --}}
                    <div style="grid-column:span 1;">
                        <input type="text" readonly
                               id="total-{{ $item->id }}"
                               value="{{ $opening + $purchased }}"
                               style="width:100%; text-align:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:4px; font-size:13px; color:#64748b; cursor:not-allowed;">
                    </div>

                    {{-- Wastage --}}
                    <div style="grid-column:span 1;">
                        <input type="number" name="wastage" step="0.01" min="0"
                               value="{{ $wastage }}"
                               class="stock-input"
                               style="width:100%; text-align:center;"
                               oninput="calcRow({{ $item->id }})">
                    </div>

                    {{-- Closing (user input) --}}
                    <div style="grid-column:span 1;">
                        <input type="number" name="closing" step="0.01" min="0"
                               id="closing-{{ $item->id }}"
                               value="{{ $closing }}"
                               class="stock-input"
                               style="width:100%; text-align:center; {{ $closingStyle }}"
                               oninput="calcRow({{ $item->id }})">
                    </div>

                    {{-- Consumed (readonly, auto-calculated) --}}
                    <input type="hidden" name="consumption" id="consumption-{{ $item->id }}" value="{{ $consumption }}">
                    <div style="grid-column:span 1;">
                        <input type="text" readonly
                               id="consumed-{{ $item->id }}"
                               value="{{ $consumption }}"
                               style="width:100%; text-align:center; background:#f1f5f9; color:#64748b; cursor:not-allowed; border:1px solid #e2e8f0; border-radius:4px; padding:4px; font-size:13px;">
                    </div>

                    {{-- Mayo fields (hidden unless is_mayo) --}}
                    @if($item->is_mayo)
                        <input type="hidden" name="mayo_oil_qty"  value="{{ $log->mayo_oil_qty ?? 0 }}">
                        <input type="hidden" name="mayo_milk_qty" value="{{ $log->mayo_milk_qty ?? 0 }}">
                        <input type="hidden" name="mayo_bottles"  value="{{ $log->mayo_bottles ?? 0 }}">
                    @endif

                    {{-- Save button --}}
                    <div style="grid-column:span 2; display:flex; gap:4px;">
                        <button type="button"
                                onclick="saveRow({{ $item->id }})"
                                class="btn-primary btn-sm" style="flex:1;">
                            Save
                        </button>
                        @if($item->is_mayo)
                        <button type="button"
                                onclick="openMayoModal({{ $item->id }}, '{{ $item->name }}', {{ $log->mayo_oil_qty ?? 0 }}, {{ $log->mayo_milk_qty ?? 0 }}, {{ $log->mayo_bottles ?? 0 }})"
                                style="background:rgba(249,115,22,0.15); color:#f97316; font-size:11px; font-weight:600; padding:4px 8px; border-radius:4px;">
                            🥛
                        </button>
                        @endif
                    </div>
                </form>
            </div>
            @endforeach
        </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Mayo Modal --}}
<div id="mayo-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="card" style="padding:24px; width:320px;">
        <h3 style="font-weight:700; margin-bottom:16px;" id="mayo-modal-title">Mayo Ingredients</h3>
        <div style="display:flex; flex-direction:column; gap:12px;">
            <div>
                <label style="font-size:13px; color:#64748b;">Oil (bottles/qty)</label>
                <input type="number" id="mayo-oil" step="0.5" min="0"
                       class="stock-input" style="width:100%; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:13px; color:#64748b;">Milk (bottles/qty)</label>
                <input type="number" id="mayo-milk" step="0.5" min="0"
                       class="stock-input" style="width:100%; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:13px; color:#64748b;">Bottles (output)</label>
                <input type="number" id="mayo-bottles" step="0.5" min="0"
                       class="stock-input" style="width:100%; margin-top:4px;">
            </div>
        </div>
        <div style="display:flex; gap:12px; margin-top:20px;">
            <button onclick="saveMayo()" class="btn-primary"" style="flex:1;">
                Save
            </button>
            <button onclick="closeMayoModal()" class="btn-secondary"" style="flex:1;">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
let currentMayoItemId = null;

function toggleCategory(id) {
    var el = document.getElementById(id);
    var catKey = id.replace('category-table-', '');
    var chevron = document.getElementById('chevron-' + catKey);
    var header = document.getElementById('toggle-header-' + catKey);

    if (el.style.maxHeight === '0px' || el.style.maxHeight === '0') {
        // Expand
        el.style.maxHeight = '5000px';
        el.style.opacity = '1';
        if (chevron) chevron.textContent = '\u25bc';
        if (header) header.title = 'Click to collapse';
    } else {
        // Collapse
        el.style.maxHeight = '0px';
        el.style.opacity = '0';
        if (chevron) chevron.textContent = '\u25b6';
        if (header) header.title = 'Click to expand';
    }
}

function toggleOpeningLock(itemId) {
    const input = document.querySelector('#form-' + itemId + ' [name=opening]');
    const unlockBtn = document.getElementById('unlock-btn-' + itemId);
    const openingSourceInput = document.getElementById('opening_source-' + itemId);

    if (input.readOnly) {
        // Unlock for editing - use dark background with light text
        input.readOnly = false;
        input.style.backgroundColor = 'rgba(30, 30, 30, 0.95)';
        input.style.border = '2px solid #facc15';
        input.style.color = '#ffffff';
        input.dataset.manuallyEdited = 'true';
        input.dataset.openingSource  = 'manual';
        openingSourceInput.value = 'manual';
        unlockBtn.textContent = '🔒';
        unlockBtn.classList.add('text-red-500');
        unlockBtn.classList.remove('text-gray-400');
        input.focus();
    } else {
        // Re-lock - remove inline styles to return to CSS classes
        input.readOnly = true;
        input.style.backgroundColor = '';
        input.style.border = '';
        input.style.color = '';
        const source = input.dataset.openingSource;
        if (source === 'manual') {
            input.style.backgroundColor = 'rgba(251, 191, 36, 0.2)';
            input.style.border = '2px solid #facc15';
            input.title = 'Opening manually edited by staff';
        } else if (source === 'auto') {
            input.style.backgroundColor = 'rgba(59, 130, 246, 0.2)';
            input.style.border = '2px solid #3b82f6';
            input.title = 'Opening auto-updated from previous day closing';
        } else {
            input.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
            input.style.border = '1px solid rgba(255, 255, 255, 0.1)';
            input.title = 'Auto-filled from previous day closing';
        }
        unlockBtn.textContent = '✏';
        unlockBtn.classList.remove('text-red-500');
        unlockBtn.classList.add('text-gray-400');
    }
}

function calcRow(itemId) {
    const form = document.getElementById('form-' + itemId);
    const opening   = parseFloat(form.querySelector('[name=opening]').value)   || 0;
    const purchased = parseFloat(form.querySelector('[name=purchased]').value) || 0;
    const wastage   = parseFloat(form.querySelector('[name=wastage]').value)   || 0;
    let closing     = parseFloat(form.querySelector('[name=closing]').value)   || 0;

    const total = opening + purchased;
    document.getElementById('total-' + itemId).value = total.toFixed(2);
    
    const maxClosing = total - wastage;
    const consumption = total - wastage - closing;
    
    const consumedInput = document.getElementById('consumed-' + itemId);
    const consumptionHidden = document.getElementById('consumption-' + itemId);
    
    if (consumption < 0) {
        consumedInput.value = consumption.toFixed(2);
        consumedInput.classList.add('text-red-600', 'font-bold');
        consumptionHidden.value = consumption.toFixed(2);
        
        const saveBtn = form.querySelector('button[onclick*="saveRow"]');
        saveBtn.disabled = true;
        saveBtn.title = "Negative consumption not allowed. Please adjust Opening, Purchased, or Closing.";
        saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        consumedInput.value = consumption.toFixed(2);
        consumedInput.classList.remove('text-red-600', 'font-bold');
        consumptionHidden.value = consumption.toFixed(2);
        
        const saveBtn = form.querySelector('button[onclick*="saveRow"]');
        saveBtn.disabled = false;
        saveBtn.title = "";
        saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function saveRow(itemId) {
    const form = document.getElementById('form-' + itemId);
    const btn = form.querySelector('button[onclick]');
    const currentRow = document.getElementById('row-' + itemId);
    const openingInput = form.querySelector('[name=opening]');
    const isManuallyEdited = openingInput.dataset.manuallyEdited === 'true';
    
    const consumption = parseFloat(document.getElementById('consumption-' + itemId).value) || 0;
    if (consumption < 0) {
        alert('Negative consumption not allowed. Please adjust Opening, Purchased, or Closing values.');
        return;
    }
    
    console.log('Saving row', {
        itemId: itemId,
        opening: form.querySelector('[name=opening]').value,
        purchased: form.querySelector('[name=purchased]').value,
        wastage: form.querySelector('[name=wastage]').value,
        closing: form.querySelector('[name=closing]').value,
        consumption: consumption,
        log_date: form.querySelector('[name=log_date]').value,
        opening_source: form.querySelector('[name=opening_source]').value
    });
    
    btn.textContent = '...';
    btn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                   'Accept': 'application/json' },
        body: new FormData(form)
    })
    .then(response => {
        const status = response.status;
        
        // Success: 2xx status or redirect (302 for Laravel redirect)
        if ((status >= 200 && status < 300) || response.redirected) {
            // Check for stock warnings in JSON response
            response.json().then(data => {
                if (data.stockWarning) {
                    showStockWarning(data.stockWarning);
                }
            }).catch(() => {}); // Ignore if not JSON
            
            btn.textContent = '✓ Saved';
            btn.className = btn.className.replace('bg-yellow-400 hover:bg-yellow-500', 'bg-green-400');
            
            // Add green highlight to Purchased input
            const purchasedInput = document.getElementById('purchased-' + itemId);
            purchasedInput.style.border = '2px solid #16a34a';
            purchasedInput.style.backgroundColor = '#f0fdf4';
            
            // Add green highlight to Closing input
            const closingInput = document.getElementById('closing-' + itemId);
            closingInput.style.border = '2px solid #16a34a';
            closingInput.style.backgroundColor = '#f0fdf4';
            
            openingInput.readOnly = true;
            openingInput.classList.remove('bg-white', 'bg-gray-100', 'bg-amber-100', 'bg-blue-100',
                                          'border-yellow-400', 'border-gray-200', 'border-amber-400', 'border-blue-400');
            const savedSource = openingInput.dataset.openingSource;
            if (savedSource === 'manual') {
                openingInput.classList.add('bg-amber-100', 'border-amber-400');
                openingInput.title = 'Opening manually edited by staff';
            } else if (savedSource === 'auto') {
                openingInput.classList.add('bg-blue-100', 'border-blue-400');
                openingInput.title = 'Opening auto-updated from previous day closing';
            } else {
                openingInput.classList.add('bg-gray-100', 'border-gray-200');
                openingInput.title = 'Auto-filled from previous day closing';
            }
            
            const unlockBtn = document.getElementById('unlock-btn-' + itemId);
            unlockBtn.textContent = '✏';
            unlockBtn.classList.remove('text-red-500');
            unlockBtn.classList.add('text-gray-400');
            
            let savedBadge = currentRow.querySelector('.saved-badge');
            if (!savedBadge) {
                const nameCell = currentRow.querySelector('.col-span-3');
                const badge = document.createElement('span');
                badge.className = 'saved-badge ml-1 text-xs text-green-500';
                badge.textContent = '✓ saved';
                nameCell.appendChild(badge);
            }
            
            setTimeout(() => {
                btn.textContent = 'Save';
                btn.className = btn.className.replace('bg-green-400', 'bg-yellow-400 hover:bg-yellow-500');
                btn.disabled = false;
            }, 2000);
            
            const nextRow = currentRow.nextElementSibling;
            if (nextRow) {
                const nextForm = nextRow.querySelector('form');
                if (nextForm) {
                    const nextOpeningInput = nextForm.querySelector('[name="opening"]');
                    if (nextOpeningInput) {
                        nextOpeningInput.focus();
                    }
                }
            }
        } else if (status === 422) {
            // Validation error - parse error message
            return response.json().then(errData => {
                const msg = errData.message || errData.errors ? JSON.stringify(errData.errors) : 'Validation failed';
                alert('Validation Error: ' + msg);
                btn.textContent = 'Save';
                btn.disabled = false;
            }).catch(() => {
                alert('Validation failed. Please check your inputs.');
                btn.textContent = 'Save';
                btn.disabled = false;
            });
        } else {
            // Other error
            return response.text().then(text => {
                console.error('Save error:', status, text);
                alert('Error saving: ' + status);
                btn.textContent = 'Save';
                btn.disabled = false;
            });
        }
    })
    .catch((error) => {
        console.error('Save error:', error);
        btn.textContent = 'Save';
        btn.disabled = false;
    });
}

function openMayoModal(itemId, name, oil, milk, bottles) {
    currentMayoItemId = itemId;
    document.getElementById('mayo-modal-title').textContent = name + ' — Ingredients';
    document.getElementById('mayo-oil').value = oil;
    document.getElementById('mayo-milk').value = milk;
    document.getElementById('mayo-bottles').value = bottles;
    document.getElementById('mayo-modal').classList.remove('hidden');
}

function saveMayo() {
    if (!currentMayoItemId) return;
    const form = document.getElementById('form-' + currentMayoItemId);
    form.querySelector('[name=mayo_oil_qty]').value  = document.getElementById('mayo-oil').value;
    form.querySelector('[name=mayo_milk_qty]').value = document.getElementById('mayo-milk').value;
    form.querySelector('[name=mayo_bottles]').value  = document.getElementById('mayo-bottles').value;
    closeMayoModal();
}

function closeMayoModal() {
    document.getElementById('mayo-modal').classList.add('hidden');
    currentMayoItemId = null;
}

function saveElectricity() {
    const date = document.getElementById('log-date').value;
    const readingInput = document.querySelector('[x-model="reading"]');
    const reading = readingInput.value;

    if (!reading) {
        alert('Please enter a meter reading');
        return;
    }

    fetch('{{ route("inventory.saveElectricity") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ electricity_reading: reading, date: date })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update Alpine data via direct DOM manipulation
            const alpineEl = readingInput.closest('[x-data]');
            if (alpineEl && alpineEl._x_dataStack) {
                alpineEl._x_dataStack[0].saved = true;
                if (data.units_consumed !== null) {
                    alpineEl._x_dataStack[0].units = data.units_consumed;
                }
            }
            readingInput.classList.add('bg-green-50', 'border-green-300');
        }
    });
}

function setGas(changed) {
    const date = document.querySelector('input[name="date"]').value;
    fetch('{{ route("inventory.saveGas") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ gas_changed: changed, date: date })
    }).then(() => {
        const statusText = document.getElementById('gas-status-text');
        const yesBtn = document.getElementById('gas-yes-btn');
        const noBtn = document.getElementById('gas-no-btn');
        
        // Reset classes
        yesBtn.className = 'btn-secondary btn-sm';
        noBtn.className = 'btn-secondary btn-sm';

        if (changed) {
            statusText.textContent = '✅ Changed today';
            statusText.className = 'text-sm font-medium text-green-600';
            yesBtn.classList.add('bg-green-100', 'text-green-700');
            yesBtn.classList.remove('bg-gray-100', 'text-gray-600');
        } else {
            statusText.textContent = '❌ No change';
            statusText.className = 'text-sm font-medium text-red-500';
            noBtn.classList.add('bg-red-100', 'text-red-600');
            noBtn.classList.remove('bg-gray-100', 'text-gray-600');
        }
    });
}

function saveOil() {
    const date = document.getElementById('log-date').value;
    const l1 = document.getElementById('oil-l1').value || 0;
    const l2 = document.getElementById('oil-l2').value || 0;
    const r1 = document.getElementById('oil-r1').value || 0;
    const r2 = document.getElementById('oil-r2').value || 0;
    const mayo = document.getElementById('oil-mayo').value || 0;
    const sauces = document.getElementById('oil-sauces').value || 0;

    const saveBtn = document.getElementById('oil-save-btn');
    saveBtn.textContent = '...';
    saveBtn.disabled = true;

    // Lock all inputs immediately (prevent double-save)
    const inputs = ['oil-l1', 'oil-l2', 'oil-r1', 'oil-r2', 'oil-mayo', 'oil-sauces'];
    inputs.forEach(id => document.getElementById(id).disabled = true);

    fetch('{{ route("inventory.saveOil") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            date: date,
            oil_l1_packets: l1,
            oil_l2_packets: l2,
            oil_r1_packets: r1,
            oil_r2_packets: r2,
            oil_mayo_packets: mayo,
            oil_sauces_packets: sauces
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('oil-status-text').textContent = '✅ Saved — locked';
            document.getElementById('oil-status-text').style.color = '#16a34a';
            // Green highlight on all inputs
            inputs.forEach(id => {
                const el = document.getElementById(id);
                el.style.border = '2px solid #16a34a';
                el.style.backgroundColor = '#f0fdf4';
            });
            // Show warnings if any
            if (data.warnings && data.warnings.length > 0) {
                showStockWarning(data.warnings.join('\n\n'));
            }
            // Refresh monthly total by fetching from the server
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTotal = doc.getElementById('oil-monthly-total');
                    if (newTotal) {
                        document.getElementById('oil-monthly-total').textContent = newTotal.textContent;
                    }
                });
        }
        // Keep inputs locked + hide save btn, show Edit btn
        saveBtn.style.display = 'none';
        document.getElementById('oil-unlock-btn').style.display = '';
    })
    .catch(() => {
        alert('Error saving oil data');
        // Re-enable on error
        inputs.forEach(id => document.getElementById(id).disabled = false);
        saveBtn.disabled = false;
        saveBtn.style.display = '';
    });
}

function unlockOil() {
    const inputs = ['oil-l1', 'oil-l2', 'oil-r1', 'oil-r2', 'oil-mayo', 'oil-sauces'];
    inputs.forEach(id => {
        const el = document.getElementById(id);
        el.disabled = false;
        el.style.border = '1px solid rgba(226,232,240,0.6)';
        el.style.backgroundColor = '#f8fafc';
    });
    document.getElementById('oil-save-btn').style.display = '';
    document.getElementById('oil-unlock-btn').style.display = 'none';
    document.getElementById('oil-status-text').textContent = '✏️ Editing';
    document.getElementById('oil-status-text').style.color = 'var(--accent)';
}

function showStockWarning(message) {
    // Remove existing warning banners
    document.querySelectorAll('.stock-warning-banner').forEach(el => el.remove());
    
    const banner = document.createElement('div');
    banner.className = 'stock-warning-banner';
    banner.style.cssText = `
        background: rgba(251,191,36,0.12);
        border: 1px solid rgba(251,191,36,0.3);
        border-left: 4px solid #d97706;
        color: #92400e;
        padding: 14px 18px;
        border-radius: 10px;
        margin: 0 24px 16px;
        font-size: 13px;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: fadeIn 0.3s ease;
    `;
    banner.innerHTML = `
        <span style="font-size:18px; flex-shrink:0;">💡</span>
        <div style="flex:1; white-space:pre-line;">${message}</div>
        <button onclick="this.parentElement.remove()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:16px; flex-shrink:0; padding:0;">✕</button>
    `;
    
    // Insert after the date banner
    const dateBanner = document.querySelector('[style*="border-radius:10px; padding:10px 16px; margin-bottom:16px;"]');
    if (dateBanner && dateBanner.parentElement) {
        dateBanner.parentElement.insertBefore(banner, dateBanner.nextSibling);
    } else {
        document.querySelector('.glass-card:first-child')?.after(banner);
    }
    
    // Auto-dismiss after 8 seconds
    setTimeout(() => { banner.style.opacity = '0'; banner.style.transition = 'opacity 0.5s'; setTimeout(() => banner.remove(), 500); }, 8000);
}

function sendWhatsAppAlert() {
    fetch('{{ route("inventory.lowStock") }}')
    .then(r => r.json())
    .then(items => {
        if (items.length === 0) {
            alert('✅ All items are sufficiently stocked.');
            return;
        }

        const today = new Date().toLocaleDateString('en-GB', 
            {day:'2-digit', month:'short', year:'numeric'});

        let msg = `🛒 *Lord Of Wraps — Purchase Required*\n`;
        msg += `📅 ${today}\n\n`;
        msg += `*Items to Purchase:*\n`;
        items.forEach((item, i) => {
            msg += `${i+1}. ${item.name} — Stock: ${item.closing} ${item.unit} `;
            msg += `(Min: ${item.min_stock} ${item.unit})\n`;
        });
        msg += `\nPlease arrange at the earliest. 🙏`;

        const encoded = encodeURIComponent(msg);
        window.open('https://wa.me/?text=' + encoded, '_blank');
    });
}

// Initialize gas button state based on PHP variable
document.addEventListener('DOMContentLoaded', function() {
    @if($gasChanged === true)
        document.getElementById('gas-status-text').textContent = '✅ Changed today';
        document.getElementById('gas-status-text').className = 'text-sm font-medium text-green-600';
        document.getElementById('gas-yes-btn').classList.add('bg-green-100', 'text-green-700');
        document.getElementById('gas-yes-btn').classList.remove('bg-gray-100', 'text-gray-600');
    @elseif($gasChanged === false)
        document.getElementById('gas-status-text').textContent = '❌ No change';
        document.getElementById('gas-status-text').className = 'text-sm font-medium text-red-500';
        document.getElementById('gas-no-btn').classList.add('bg-red-100', 'text-red-600');
        document.getElementById('gas-no-btn').classList.remove('bg-gray-100', 'text-gray-600');
    @endif

    // Lock oil inputs if data already saved for this date
    @if($oilL1 !== null || $oilL2 !== null || $oilR1 !== null || $oilR2 !== null || $oilMayo !== null || $oilSauces !== null)
        ['oil-l1', 'oil-l2', 'oil-r1', 'oil-r2', 'oil-mayo', 'oil-sauces'].forEach(id => {
            document.getElementById(id).disabled = true;
        });
        document.getElementById('oil-save-btn').style.display = 'none';
        document.getElementById('oil-unlock-btn').style.display = '';
        document.getElementById('oil-status-text').textContent = '✅ Saved — locked';
        document.getElementById('oil-status-text').style.color = '#16a34a';
    @endif
});
</script>

{{-- Oil Monthly Detail Modal --}}
<div id="oil-detail-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; width:90%; max-width:680px; max-height:80vh; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.5);">
        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #e2e8f0;">
            <div>
                <span style="font-size:16px;">🛢️</span>
                <span style="font-weight:600; margin-left:8px;">Oil Breakdown — </span>
                <span id="oil-detail-month-label" style="color:var(--accent); font-weight:600;"></span>
            </div>
            <button onclick="closeOilDetailModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:20px; padding:4px;">✕</button>
        </div>
        {{-- Table --}}
        <div style="overflow-y:auto; max-height:calc(80vh - 120px);">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#64748b;">
                        <th style="padding:10px 12px; text-align:left;">Date</th>
                        <th style="padding:10px 12px; text-align:center;">L1</th>
                        <th style="padding:10px 12px; text-align:center;">L2</th>
                        <th style="padding:10px 12px; text-align:center;">R1</th>
                        <th style="padding:10px 12px; text-align:center;">R2</th>
                        <th style="padding:10px 12px; text-align:center;">Fryer</th>
                        <th style="padding:10px 12px; text-align:center;">Mayo</th>
                        <th style="padding:10px 12px; text-align:center;">Sauces</th>
                        <th style="padding:10px 12px; text-align:center;">Conds.</th>
                        <th style="padding:10px 12px; text-align:center; color:var(--accent);">Cumul.</th>
                    </tr>
                </thead>
                <tbody id="oil-detail-tbody"></tbody>
            </table>
        </div>
        {{-- Footer --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-top:1px solid #e2e8f0;">
            <div style="display:flex; gap:24px;">
                <span style="font-size:13px; color:#64748b;">Fryer Total</span>
                <span id="oil-detail-modal-total" style="font-size:20px; font-weight:700; color:var(--accent);"></span>
            </div>
            <div style="display:flex; gap:24px;">
                <span style="font-size:13px; color:#64748b;">Condiments Total</span>
                <span id="oil-detail-modal-condiments" style="font-size:20px; font-weight:700; color:#a78bfa;"></span>
            </div>
        </div>
    </div>
</div>

<script>
function openOilDetailModal() {
    const month = document.getElementById('log-date').value.substring(0, 7);
    fetch(`{{ route('inventory.oilMonthlyDetail') }}?month=${month}`)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('oil-detail-tbody');
            tbody.innerHTML = '';
            let runningTotal = 0;
            data.days.forEach(d => {
                runningTotal += d.total;
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e2e8f0';
                row.innerHTML = `
                    <td style="padding:8px 12px; font-size:13px;">${d.date}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.l1}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.l2}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.r1}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.r2}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px; font-weight:600;">${d.total}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.mayo}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px;">${d.sauces}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px; font-weight:600;">${d.sauces_total}</td>
                    <td style="padding:8px 12px; text-align:center; font-size:13px; color:var(--accent);">${runningTotal}</td>
                `;
                tbody.appendChild(row);
            });
            document.getElementById('oil-detail-modal-total').textContent = data.grand_total;
            document.getElementById('oil-detail-modal-condiments').textContent = data.sauces_grand_total ?? 0;
            document.getElementById('oil-detail-month-label').textContent = data.month;
            document.getElementById('oil-detail-modal').style.display = 'flex';
        })
        .catch(() => alert('Error loading oil details'));
}

function closeOilDetailModal() {
    document.getElementById('oil-detail-modal').style.display = 'none';
}

// Close modal on backdrop click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('oil-detail-modal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
@endsection
