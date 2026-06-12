@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Expense Details</h3>
            <div class="flex gap-3">
                <a href="{{ route('expenses.edit', $expense) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm">Edit</a>
                <a href="{{ route('expenses.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm">Back</a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Title</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">{{ $expense->title }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Category</p>
                    <p class="text-gray-800 mt-1">{{ $expense->category }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₹{{ number_format($expense->amount, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Expense Date</p>
                    <p class="text-gray-800 mt-1">{{ $expense->expense_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <div class="mt-2">
                        @php $ps = $expense->payment_status; @endphp
                        @if($ps == 'paid')
                            <span class="bg-green-100 text-green-700 text-sm font-semibold px-4 py-1 rounded-full">✅ Paid</span>
                        @elseif($ps == 'partial')
                            <span class="bg-yellow-100 text-yellow-700 text-sm font-semibold px-4 py-1 rounded-full">⏳ Partial</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-sm font-semibold px-4 py-1 rounded-full">❌ Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Supplier</p>
                <p class="text-gray-800 mt-1">{{ $expense->supplier->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Notes</p>
                <p class="text-gray-800 mt-1">{{ $expense->notes ?? '-' }}</p>
            </div>

        </div>
    </div>
</div>

@endsection