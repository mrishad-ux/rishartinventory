@extends('layouts.app')
@section('title', 'Inventory History')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <div class="page-title">Inventory History</div>
        <div class="page-subtitle">Daily stock log</div>
    </div>
    <a href="{{ route('inventory.daily') }}" class="btn-primary"">
        📋 Daily Entry
    </a>
</div>

<form method="GET" action="{{ route('inventory.history') }}"
      class="card" style="padding:16px; margin-bottom:24px; display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">
    <div>
        <label class="form-label">From</label>
        <input type="date" name="from" value="{{ $from }}" class="form-input" style="width:150px;">
    </div>
    <div>
        <label class="form-label">To</label>
        <input type="date" name="to" value="{{ $to }}" class="form-input" style="width:150px;">
    </div>
    <div>
        <label class="form-label">Category</label>
        <select name="category" class="form-select" style="width:180px;">
            <option value="">All Categories</option>
            @foreach($categories as $key => $label)
                <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-primary"">
        Filter
    </button>
</form>

@forelse($logs as $date => $dayLogs)
<div style="margin-bottom:24px;">
    <div style="color:#64748b; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
        📅 {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
        @if(\Carbon\Carbon::parse($date)->isToday())
            <span style="margin-left:8px; font-size:10px; background:rgba(250,204,21,0.2); color:#facc15; padding:2px 8px; border-radius:999px;">Today</span>
        @endif
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div style="max-height:480px; overflow-y:auto;">
            <table class="data-table w-full">
                <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                    <tr>
                        <th class="px-4 py-3 text-left">Item</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-center">Unit</th>
                        <th class="px-4 py-3 text-center">Opening</th>
                        <th class="px-4 py-3 text-center">Purchased</th>
                        <th class="px-4 py-3 text-center">Consumed</th>
                        <th class="px-4 py-3 text-center">Wastage</th>
                        <th class="px-4 py-3 text-center">Closing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dayLogs as $log)
                    <tr style="border-bottom:1px solid #f8fafc;">
                        <td class="px-4 py-3">{{ $log->item->name }}</td>
                        <td class="px-4 py-3" style="font-size:12px; color:#64748b;">
                            {{ App\Models\InventoryItem::$categories[$log->item->category] ?? $log->item->category }}
                        </td>
                        <td class="px-4 py-3 text-center" style="color:#64748b;">{{ $log->item->unit }}</td>
                        <td class="px-4 py-3 text-center">{{ number_format($log->opening, 2) }}</td>
                        <td class="px-4 py-3 text-center">{{ number_format($log->purchased, 2) }}</td>
                        <td class="px-4 py-3 text-center">{{ number_format($log->consumption, 2) }}</td>
                        <td class="px-4 py-3 text-center" style="color:#f97316;">{{ number_format($log->wastage, 2) }}</td>
                        @php $closingColor = $log->closing <= 0 ? '#dc2626' : '#16a34a'; @endphp
                        <td class="px-4 py-3 text-center font-bold" style="color: {{ $closingColor }};">
                            {{ number_format($log->closing, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="card" style="padding:64px; text-align:center;">
    <div style="font-size:48px; margin-bottom:12px;">📊</div>
    <p style="font-size:18px; color:#64748b;">No logs found for this date range</p>
</div>
@endforelse
@endsection
