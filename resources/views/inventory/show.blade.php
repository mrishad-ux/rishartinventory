@extends('layouts.app')
@section('title', $inventory->name . ' History')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('inventory.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $inventory->name }}</h1>
            <p class="text-sm text-gray-400">{{ InventoryItem::$categories[$inventory->category] ?? $inventory->category }} · {{ $inventory->unit }}</p>
        </div>
    </div>
    <a href="{{ route('inventory.daily') }}"
       class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg transition text-sm">
        + Add Today's Entry
    </a>
</div>

{{-- Current stock card --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Current Stock</p>
        <p class="text-2xl font-bold {{ $inventory->isLowStock() ? 'text-red-600' : 'text-gray-800' }} mt-1">
            {{ number_format($inventory->current_stock, 2) }} <span class="text-sm text-gray-400">{{ $inventory->unit }}</span>
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Min Stock</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">
            {{ number_format($inventory->minimum_stock, 2) }} <span class="text-sm text-gray-400">{{ $inventory->unit }}</span>
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Log Entries</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $logs->total() }}</p>
    </div>
</div>

{{-- Log table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-xs font-semibold text-gray-500 uppercase">
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-center">Opening</th>
                <th class="px-4 py-3 text-center">Purchased</th>
                <th class="px-4 py-3 text-center">Total</th>
                <th class="px-4 py-3 text-center">Consumed</th>
                <th class="px-4 py-3 text-center">Wastage</th>
                <th class="px-4 py-3 text-center font-bold text-gray-700">Closing</th>
                @if($inventory->is_mayo)
                <th class="px-4 py-3 text-center">Oil / Milk / Btl</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-800">
                    {{ $log->log_date->format('d M Y') }}
                    @if($log->log_date->isToday())
                        <span class="ml-1 text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded-full">Today</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center text-gray-600">{{ number_format($log->opening, 2) }}</td>
                <td class="px-4 py-3 text-center text-gray-600">{{ number_format($log->purchased, 2) }}</td>
                <td class="px-4 py-3 text-center text-gray-700 font-medium">{{ number_format($log->opening + $log->purchased, 2) }}</td>
                <td class="px-4 py-3 text-center text-gray-600">{{ number_format($log->consumption, 2) }}</td>
                <td class="px-4 py-3 text-center text-orange-500">{{ number_format($log->wastage, 2) }}</td>
                <td class="px-4 py-3 text-center font-bold {{ $log->closing <= 0 ? 'text-red-600' : 'text-green-700' }}">
                    {{ number_format($log->closing, 2) }}
                </td>
                @if($inventory->is_mayo)
                <td class="px-4 py-3 text-center text-xs text-gray-500">
                    {{ $log->mayo_oil_qty ?? '—' }} / {{ $log->mayo_milk_qty ?? '—' }} / {{ $log->mayo_bottles ?? '—' }}
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">No entries yet for this item.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $logs->links() }}</div>

@php use App\Models\InventoryItem; @endphp
@endsection
