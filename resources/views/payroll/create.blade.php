@extends('layouts.app')

@section('title', 'Record Payroll')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Record Payroll</div>
            <div class="page-subtitle">Add a salary payment</div>
        </div>

        <form action="{{ route('payroll.store') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Staff Member *</label>
                        <select name="staff_id" class="form-select" style="width:100%;">
                            <option value="">-- Select Staff --</option>
                            @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->role }})
                            </option>
                            @endforeach
                        </select>
                        @error('staff_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Date *</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                        @error('payment_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Days Worked</label>
                        <input type="number" step="0.5" name="days_worked" value="{{ old('days_worked') }}"
                            class="form-input" style="width:100%;"
                            placeholder="e.g. 26">
                        <p style="color:#64748b; font-size:11px; margin-top:4px;">For daily wage staff</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Basic Amount (₹) *</label>
                        <input type="number" step="0.01" name="basic_amount" value="{{ old('basic_amount') }}"
                            class="form-input" style="width:100%;"
                            placeholder="0.00">
                        @error('basic_amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Bonus (₹)</label>
                        <input type="number" step="0.01" name="bonus" value="{{ old('bonus', 0) }}"
                            class="form-input" style="width:100%;"
                            placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deduction (₹)</label>
                        <input type="number" step="0.01" name="deduction" value="{{ old('deduction', 0) }}"
                            class="form-input" style="width:100%;"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Status *</label>
                    <select name="status" class="form-select" style="width:100%;">
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2"
                        class="form-textarea" style="width:100%;"
                        placeholder="Any additional details">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Save Payroll
                </button>
                <a href="{{ route('payroll.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
