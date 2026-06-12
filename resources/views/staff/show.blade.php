@extends('layouts.app')

@section('title', 'Staff Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Staff Details</h3>
            <div class="flex gap-3">
                <a href="{{ route('staff.edit', $staff) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm">Edit</a>
                <a href="{{ route('staff.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm">Back</a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">

            <div class="border-b pb-4">
                <p class="text-sm text-gray-500">Full Name</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">{{ $staff->name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <p class="text-gray-800 mt-1">{{ $staff->role }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-gray-800 mt-1">{{ $staff->phone ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Salary Type</p>
                    <div class="mt-2">
                        @if($staff->salary_type == 'daily')
                            <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1 rounded-full">Daily Wage</span>
                        @else
                            <span class="bg-purple-100 text-purple-700 text-sm font-semibold px-4 py-1 rounded-full">Monthly Salary</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Salary Amount</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₹{{ number_format($staff->salary_amount, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm text-gray-500">Joining Date</p>
                    <p class="text-gray-800 mt-1">{{ $staff->joining_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <div class="mt-2">
                        @if($staff->status == 'active')
                            <span class="bg-green-100 text-green-700 text-sm font-semibold px-4 py-1 rounded-full">✅ Active</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-sm font-semibold px-4 py-1 rounded-full">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($payrolls->count() > 0)
            <div>
                <p class="text-sm text-gray-500 mb-3">Recent Payroll History</p>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Payment Date</th>
                            <th class="px-4 py-3 text-right">Net Amount</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payrolls as $payroll)
                        <tr>
                            <td class="px-4 py-3 text-gray-600">{{ $payroll->payment_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">₹{{ number_format($payroll->net_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($payroll->status == 'paid')
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Paid</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection