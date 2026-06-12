@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Add Supplier</div>
            <div class="page-subtitle">Add a new supplier</div>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div class="form-group">
                    <label class="form-label">Supplier Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-input" style="width:100%;"
                        placeholder="e.g. Ahmed Chicken Supplier">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-input" style="width:100%;"
                            placeholder="Phone number">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-input" style="width:100%;"
                            placeholder="Email address">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Supplied</label>
                    <input type="text" name="product_supplied" value="{{ old('product_supplied') }}"
                        class="form-input" style="width:100%;"
                        placeholder="e.g. Chicken, French Fries, Pita Bread">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2"
                        class="form-textarea" style="width:100%;"
                        placeholder="Supplier address">{{ old('address') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Opening Outstanding Balance (₹)</label>
                    <input type="number" step="0.01" name="outstanding_balance" value="{{ old('outstanding_balance', 0) }}"
                        class="form-input" style="width:100%;"
                        placeholder="0.00">
                    <p style="color:#64748b; font-size:11px; margin-top:4px;">Enter any existing amount you already owe this supplier</p>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Save Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
