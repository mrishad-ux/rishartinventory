@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Payroll Details</h3>
            <div class="flex gap-3">
                <a href="{{ route('payroll.edit', $payroll) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm">Edit</a>
                <a href="{{ route('payroll.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm">Back</a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Staff Member</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">{{ $payroll->staff->name ?? '-' }}</p>
                <p class="text-sm text-gray-400">{{ $payroll->staff->role ?? '' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Payment Date</p>
                    <p class="text-gray-800 mt-1">{{ $payroll->payment_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Days Worked</p>
                    <p class="text-gray-800 mt-1">{{ $payroll->days_worked ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Basic Amount</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₹{{ number_format($payroll->basic_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bonus</p>
                    <p class="text-2xl font-bold text-green-500 mt-1">+₹{{ number_format($payroll->bonus, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Deduction</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">-₹{{ number_format($payroll->deduction, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Net Pay</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">₹{{ number_format($payroll->net_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <div class="mt-2">
                        @if($payroll->status == 'paid')
                            <span class="bg-green-100 text-green-700 text-sm font-semibold px-4 py-2 rounded-full">✅ Paid</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-sm font-semibold px-4 py-2 rounded-full">❌ Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Notes</p>
                <p class="text-gray-800 mt-1">{{ $payroll->notes ?? '-' }}</p>
            </div>

        </div>
    </div>
</div>

@endsection