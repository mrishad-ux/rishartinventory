@extends('layouts.app')

@section('title', 'Edit Sale')

@section('content')

<div style="max-width:640px; margin:0 auto;">
    <div class="card" style="padding:24px;">
        <h3 style="font-size:18px; font-weight:700; margin-bottom:20px;">Edit Sale</h3>

        <form action="{{ route('sales.update', $sale) }}" method="POST" x-data="{ 
            saleType: '{{ $sale->sale_type }}', 
            isOnline: function() { return this.saleType === 'swiggy' || this.saleType === 'zomato'; },
            isCredit: function() { return this.saleType === 'other'; }
        }">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                <div class="form-group">
                    <label class="form-label">Sale Date *</label>
                    <input type="date" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}"
                        class="form-input" style="width:100%;" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Sale Type *</label>
                    <select name="sale_type" x-model="saleType" class="form-select" style="width:100%;">
                        <option value="cash" {{ $sale->sale_type == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gp" {{ $sale->sale_type == 'gp' ? 'selected' : '' }}>Google Pay</option>
                        <option value="swiggy" {{ $sale->sale_type == 'swiggy' ? 'selected' : '' }}>Swiggy</option>
                        <option value="zomato" {{ $sale->sale_type == 'zomato' ? 'selected' : '' }}>Zomato</option>
                        <option value="other" {{ $sale->sale_type == 'other' ? 'selected' : '' }}>Credit Sale</option>
                    </select>
                </div>
            </div>

            <div x-show="isOnline()" x-transition class="form-group">
                <label class="form-label">Platform</label>
                <select name="platform" class="form-select" style="width:100%;">
                    <option value="">-- Select Platform --</option>
                    <option value="Swiggy" {{ $sale->platform == 'Swiggy' ? 'selected' : '' }}>Swiggy</option>
                    <option value="Zomato" {{ $sale->platform == 'Zomato' ? 'selected' : '' }}>Zomato</option>
                </select>
            </div>

            <div x-show="isCredit()" x-transition>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $sale->customer_name) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Phone</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $sale->customer_phone) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span x-show="!isOnline() && !isCredit()">Total Sale Amount (₹) *</span>
                    <span x-show="isOnline()">Gross Order Value (₹) *</span>
                    <span x-show="isCredit()">Credit Amount (₹) *</span>
                </label>
                <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount', $sale->gross_amount) }}"
                    class="form-input" style="width:100%;" required>
            </div>

            <div x-show="isOnline()" x-transition class="form-group">
                <label class="form-label">Platform Commission (%)</label>
                <input type="number" step="0.01" name="commission_percent" value="{{ old('commission_percent', $sale->commission_percent) }}"
                    class="form-input" style="width:100%;">
            </div>

            <div x-show="isOnline() || isCredit()" x-transition class="form-group">
                <label class="form-label">Settlement Status</label>
                <select name="settlement_status" class="form-select" style="width:100%;">
                    <option value="pending" {{ $sale->settlement_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="received" {{ $sale->settlement_status == 'received' ? 'selected' : '' }}>Received</option>
                </select>
            </div>

            <div x-show="isOnline()">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Expected Settlement Date</label>
                        <input type="date" name="expected_settlement_date" value="{{ old('expected_settlement_date', $sale->expected_settlement_date?->format('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Actual Settlement Date</label>
                        <input type="date" name="actual_settlement_date" value="{{ old('actual_settlement_date', $sale->actual_settlement_date?->format('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span x-show="isCredit()">Notes</span>
                    <span x-show="!isCredit()">Notes</span>
                </label>
                <textarea name="notes" rows="2" class="form-textarea" style="width:100%;">{{ old('notes', $sale->notes) }}</textarea>
            </div>

            <div style="display:flex; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-primary"" style="flex:1;">
                    Update Sale
                </button>
                <a href="{{ route('sales.index') }}" class="btn-secondary"" style="flex:1; text-align:center;">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
