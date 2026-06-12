@extends('layouts.app')

@section('title', 'Staff')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div class="page-title">Staff</div>
    <a href="{{ route('staff.create') }}" class="btn-primary"">
        + Add Staff
    </a>
</div>

<div class="page-subtitle" style="margin-bottom:24px;">Manage your team</div>

<!-- Active Staff -->
@if($activeStaff->count() > 0)
<div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; margin-bottom:12px;">Active Staff ({{ $activeStaff->count() }})</div>
<div class="card" style="padding:0; overflow:hidden; margin-bottom:32px;">
    <div style="max-height:480px; overflow-y:auto;">
        <table class="data-table w-full">
            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                <tr>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Phone</th>
                    <th class="px-6 py-4 text-left">Salary Type</th>
                    <th class="px-6 py-4 text-right">Salary Amount</th>
                    <th class="px-6 py-4 text-left">Joining Date</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeStaff as $member)
                <tr>
                    <td class="px-6 py-4">{{ $member->name }}</td>
                    <td class="px-6 py-4">{{ $member->role }}</td>
                    <td class="px-6 py-4">{{ $member->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($member->salary_type == 'daily')
                            <span class="badge badge-active"">Daily</span>
                        @else
                            <span class="badge badge-active"">Monthly</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($member->salary_amount, 2) }}</td>
                    <td class="px-6 py-4">{{ $member->joining_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('staff.show', $member) }}" style="color:var(--accent); margin-right:8px;">View</a>
                        <a href="{{ route('staff.edit', $member) }}" style="color:#facc15; margin-right:8px;">Edit</a>
                        <form action="{{ route('staff.destroy', $member) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Remove this staff member?')" style="color:#dc2626; background:none; border:none; cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Inactive Staff -->
@if($inactiveStaff->count() > 0)
<div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; margin-bottom:12px;">Inactive Staff ({{ $inactiveStaff->count() }})</div>
<div class="card" style="padding:0; overflow:hidden;">
    <div style="max-height:480px; overflow-y:auto;">
        <table class="data-table w-full">
            <thead style="position:sticky; top:0; z-index:10; background:rgba(15,14,13,0.95); backdrop-filter:blur(10px);">
                <tr>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Phone</th>
                    <th class="px-6 py-4 text-right">Salary Amount</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inactiveStaff as $member)
                <tr style="opacity:0.6;">
                    <td class="px-6 py-4">{{ $member->name }}</td>
                    <td class="px-6 py-4">{{ $member->role }}</td>
                    <td class="px-6 py-4">{{ $member->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($member->salary_amount, 2) }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('staff.edit', $member) }}" style="color:#facc15; margin-right:8px;">Edit</a>
                        <form action="{{ route('staff.destroy', $member) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Remove this staff member?')" style="color:#dc2626; background:none; border:none; cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($activeStaff->count() == 0 && $inactiveStaff->count() == 0)
<div class="card" style="padding:48px; text-align:center;">
    <p style="color:#64748b; font-size:18px;">No staff members added yet.</p>
    <a href="{{ route('staff.create') }}" class="btn-primary"" style="margin-top:16px; display:inline-block;">
        Add First Staff Member
    </a>
</div>
@endif

@endsection
