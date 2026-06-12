@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Supplier Details</h3>
            <div class="flex gap-3">
                <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm">
                    Edit
                </a>
                <a href="{{ route('suppliers.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm">
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Supplier Name</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">{{ $supplier->name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-gray-800 mt-1">{{ $supplier->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-800 mt-1">{{ $supplier->email ?? '-' }}</p>
                </div>
            </div>

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Product Supplied</p>
                <p class="text-gray-800 mt-1">{{ $supplier->product_supplied ?? '-' }}</p>
            </div>

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Address</p>
                <p class="text-gray-800 mt-1">{{ $supplier->address ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Outstanding Balance</p>
                <p class="text-2xl font-bold mt-1 {{ $supplier->outstanding_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    ₹{{ number_format($supplier->outstanding_balance, 2) }}
                </p>
                @if($supplier->outstanding_balance > 0)
                <p class="text-red-400 text-xs mt-1">Amount owed to this supplier</p>
                @else
                <p class="text-green-400 text-xs mt-1">No outstanding amount</p>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection