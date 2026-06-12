@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div class="page-title">Suppliers</div>
    <a href="{{ route('suppliers.create') }}" class="btn-primary"">
        + Add Supplier
    </a>
</div>

<div class="page-subtitle" style="margin-bottom:24px;">Manage your suppliers</div>

@if($suppliers->count() > 0)
<div class="card" style="padding:0; overflow:hidden;">
    <div style="max-height:480px; overflow-y:auto;">
        <table class="data-table w-full">
            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                <tr>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Phone</th>
                    <th class="px-6 py-4 text-left">Product Supplied</th>
                    <th class="px-6 py-4 text-right">Outstanding (₹)</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td class="px-6 py-4">{{ $supplier->name }}</td>
                    <td class="px-6 py-4">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $supplier->product_supplied ?? '-' }}</td>
                    <td class="px-6 py-4 text-right font-semibold {{ $supplier->outstanding_balance > 0 ? 'text-red-400' : 'text-green-400' }}">
                        ₹{{ number_format($supplier->outstanding_balance, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('suppliers.show', $supplier) }}" style="color:var(--accent); margin-right:12px;">View</a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" style="color:#facc15; margin-right:12px;">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this supplier?')" style="color:#dc2626; background:none; border:none; cursor:pointer;">Delete</button>
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
    <p style="color:#64748b; font-size:18px;">No suppliers added yet.</p>
    <a href="{{ route('suppliers.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
        Add Your First Supplier
    </a>
</div>
@endif

@endsection
