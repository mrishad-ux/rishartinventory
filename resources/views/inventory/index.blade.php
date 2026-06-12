@extends('layouts.app')

@section('title', 'Inventory Items')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <div class="page-title">Item Master</div>
        <div class="page-subtitle">Manage inventory items</div>
    </div>
    <div style="display:flex; gap:12px;">
        <a href="{{ route('inventory.daily') }}" class="btn-primary"">
            📋 Daily Entry
        </a>
        <a href="{{ route('inventory.import') }}" style="background:#3b82f6; color:white; padding:10px 16px; border-radius:8px; font-weight:600;">
            ⬆ Import CSV
        </a>
        <button type="button" id="reorder-btn" onclick="toggleDragMode()"
                style="background:#a855f7; color:white; padding:10px 16px; border-radius:8px; font-weight:600;">
            ⠿ Reorder Items
        </button>
        <a href="{{ route('inventory.create') }}" class="btn-primary"">
            + Add Item
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('import_errors'))
    <div style="background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.3); color:#eab308; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">
        @foreach(session('import_errors') as $err)
            <p>⚠ {{ $err }}</p>
        @endforeach
    </div>
@endif

@foreach($categories as $catKey => $catLabel)
    @if(isset($items[$catKey]) && $items[$catKey]->count())
    <div style="margin-bottom:32px;">
        <div style="color:#64748b; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">
            @if($catKey === 'shawarma_marination') 🥙
            @elseif($catKey === 'mayo_masala_sauces') 🧴
            @elseif($catKey === 'chicken_fish') 🍗
            @else 🥖 @endif
            {{ $catLabel }}
        </div>

        <div class="card" style="padding:0; overflow:hidden;">
            <table class="w-full table-fixed text-sm" style="width:100%;">
                <colgroup>
                    <col style="width:192px;">
                    <col style="width:80px;">
                    <col style="width:128px;">
                    <col style="width:144px;">
                    <col style="width:192px;">
                </colgroup>
                <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                    <tr>
                        <th class="text-left px-3 py-3">Item</th>
                        <th class="text-left px-3 py-3">Unit</th>
                        <th class="text-right px-3 py-3">Min Stock</th>
                        <th class="text-center px-3 py-3">Last Updated</th>
                        <th class="text-right px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-{{ $catKey }}" style="border-top:1px solid #f8fafc;">
                    @foreach($items[$catKey] as $item)
                    @php
                        $latestLog = $item->logs->first();
                    @endphp
                    <tr data-id="{{ $item->id }}" style="border-bottom:1px solid #f8fafc;">
                        <td class="px-3 py-3 relative">
                            <span class="drag-handle" style="position:absolute; left:4px; top:50%; transform:translateY(-50%); color:#cbd5e1; cursor:grab;">⠿</span>
                            <span class="item-name" style="margin-left:16px;">{{ $item->name }}</span>@if($item->is_mayo)<span style="margin-left:4px; font-size:10px; background:rgba(250,204,21,0.2); color:#facc15; padding:2px 6px; border-radius:999px;">Mayo</span>@endif
                        </td>
                        <td class="px-3 py-3">{{ $item->unit }}</td>
                        <td class="px-3 py-3 text-right">
                            {{ number_format($item->minimum_stock, 2) }}
                        </td>
                        <td class="px-3 py-3 text-center" style="color:#64748b; font-size:13px;">
                            {{ $latestLog ? $latestLog->log_date->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                <a href="{{ route('inventory.show', $item) }}" style="color:var(--accent); font-size:12px; font-weight:500;">History</a>
                                <a href="{{ route('inventory.edit', $item) }}" style="color:#facc15; font-size:12px; font-weight:500;">Edit</a>
                                <form action="{{ route('inventory.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $item->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color:#dc2626; font-size:12px; font-weight:500; background:none; border:none; cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach

@php $hasItems = $items->flatten()->isNotEmpty(); @endphp
@if(!$hasItems)
    <div class="card" style="padding:64px; text-align:center;">
        <div style="font-size:48px; margin-bottom:12px;">📦</div>
        <p style="font-size:18px; color:#64748b;">No items yet</p>
        <div style="display:flex; justify-content:center; gap:16px; margin-top:12px;">
            <a href="{{ route('inventory.import') }}" style="color:var(--accent);">⬆ Import CSV</a>
            <a href="{{ route('inventory.create') }}" style="color:#facc15;">+ Add manually →</a>
        </div>
    </div>
@endif

<style>
.drag-handle { display: none; }
table.drag-mode .drag-handle { display: inline-block; }
table.drag-mode tr { cursor: default; }
table.drag-mode tr:hover { background-color: rgba(250,204,21,0.1); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let sortableInstances = [];
let dragModeActive = false;

function toggleDragMode() {
    dragModeActive = !dragModeActive;
    const btn = document.getElementById('reorder-btn');
    const tables = document.querySelectorAll('table');
    
    if (dragModeActive) {
        btn.textContent = '✓ Done Reordering';
        btn.style.background = '#16a34a';
        tables.forEach(t => t.classList.add('drag-mode'));
        
        document.querySelectorAll('.item-name').forEach(el => {
            el.style.paddingLeft = '16px';
        });
        
        document.querySelectorAll('[id^="sortable-"]').forEach(tbody => {
            const instance = Sortable.create(tbody, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function() { saveOrder(tbody); }
            });
            sortableInstances.push(instance);
        });
    } else {
        btn.textContent = '⠿ Reorder Items';
        btn.style.background = '#a855f7';
        tables.forEach(t => t.classList.remove('drag-mode'));
        
        document.querySelectorAll('.item-name').forEach(el => {
            el.style.paddingLeft = '0';
        });
        
        sortableInstances.forEach(s => s.destroy());
        sortableInstances = [];
    }
}

function saveOrder(tbody) {
    const ids = Array.from(tbody.querySelectorAll('tr[data-id]'))
                     .map(tr => tr.dataset.id);
    fetch('{{ route("inventory.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ items: ids })
    });
}
</script>
@endsection
