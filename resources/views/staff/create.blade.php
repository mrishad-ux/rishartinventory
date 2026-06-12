@extends('layouts.app')

@section('title', 'Add Staff')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Add Staff</div>
            <div class="page-subtitle">Add a new team member</div>
        </div>

        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-input" style="width:100%;"
                        placeholder="e.g. Mohammed Rafi">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <input type="text" name="role" value="{{ old('role') }}"
                            class="form-input" style="width:100%;"
                            placeholder="e.g. Chef, Cashier, Cleaner">
                        @error('role') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-input" style="width:100%;"
                            placeholder="Phone number">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Salary Type *</label>
                        <select name="salary_type" class="form-select" style="width:100%;">
                            <option value="daily" {{ old('salary_type') == 'daily' ? 'selected' : '' }}>Daily Wage</option>
                            <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Monthly Salary</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Amount (₹) *</label>
                        <input type="number" step="0.01" name="salary_amount" value="{{ old('salary_amount') }}"
                            class="form-input" style="width:100%;"
                            placeholder="0.00">
                        @error('salary_amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Joining Date *</label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                        @error('joining_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" style="width:100%;">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Save Staff Member
                </button>
                <a href="{{ route('staff.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
