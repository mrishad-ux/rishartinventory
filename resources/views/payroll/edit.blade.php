@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Edit Payroll</div>
            <div class="page-subtitle">Update salary record</div>
        </div>

        <form action="{{ route('payroll.update', $payroll) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Staff Member *</label>
                        <select name="staff_id" class="form-select" style="width:100%;">
                            <option value="">-- Select Staff --</option>
                            @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ $payroll->staff_id == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->role }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Date *</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', $payroll->payment_date->format('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Days Worked</label>
                        <input type="number" step="0.5" name="days_worked" value="{{ old('days_worked', $payroll->days_worked) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Basic Amount (₹) *</label>
                        <input type="number" step="0.01" name="basic_amount" value="{{ old('basic_amount', $payroll->basic_amount) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Bonus (₹)</label>
                        <input type="number" step="0.01" name="bonus" value="{{ old('bonus', $payroll->bonus) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deduction (₹)</label>
                        <input type="number" step="0.01" name="deduction" value="{{ old('deduction', $payroll->deduction) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Status *</label>
                    <select name="status" class="form-select" style="width:100%;">
                        <option value="paid" {{ $payroll->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ $payroll->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2"
                        class="form-textarea" style="width:100%;">{{ old('notes', $payroll->notes) }}</textarea>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Update Payroll
                </button>
                <a href="{{ route('payroll.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
