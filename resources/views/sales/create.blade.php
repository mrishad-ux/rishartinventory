@extends('layouts.app')

@section('title', 'Record Sale')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="card" style="padding:32px;">
        <h2 class="page-title" style="font-size:18px; margin-bottom:20px;">Record New Sale</h2>

        <form action="{{ route('sales.store') }}" method="POST" x-data="{ saleType: '{{ old('sale_type', 'cash') }}', isOnline: function() { return this.saleType === 'swiggy' || this.saleType === 'zomato'; } }">
            @csrf

            <div style="display:flex; flex-direction:column; gap:24px;">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label class="form-label">Sale Date *</label>
                        <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="form-label">Sale Type *</label>
                        <select name="sale_type" x-model="saleType"
                            class="form-select" style="width:100%;">
                            <option value="cash">Cash</option>
                            <option value="gp">Google Pay</option>
                            <option value="swiggy">Swiggy</option>
                            <option value="zomato">Zomato</option>
                        </select>
                    </div>
                </div>

                <!-- Online only fields -->
                <div x-show="isOnline()" x-transition>
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select" style="width:100%;">
                        <option value="">-- Select Platform --</option>
                        <option value="Swiggy" {{ old('platform') == 'Swiggy' ? 'selected' : '' }}>Swiggy</option>
                        <option value="Zomato" {{ old('platform') == 'Zomato' ? 'selected' : '' }}>Zomato</option>
                        <option value="Other" {{ old('platform') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">
                        <span x-show="!isOnline()">Total Sale Amount (₹) *</span>
                        <span x-show="isOnline()">Gross Order Value (₹) *</span>
                    </label>
                    <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount') }}"
                        class="form-input" style="width:100%;"
                        placeholder="0.00">
                    @error('gross_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Commission - online only -->
                <div x-show="isOnline()" x-transition>
                    <label class="form-label">Platform Commission (%) *</label>
                    <input type="number" step="0.01" name="commission_percent" value="{{ old('commission_percent', 31) }}"
                        class="form-input" style="width:100%;"
                        placeholder="e.g. 31">
                    <p class="text-gray-400 text-xs mt-1">Net receivable will be calculated automatically</p>
                </div>

                <!-- Settlement date - online only -->
                <div x-show="isOnline()" x-transition>
                    <label class="form-label">Expected Settlement Date</label>
                    <input type="date" name="expected_settlement_date" value="{{ old('expected_settlement_date') }}"
                        class="form-input" style="width:100%;">
                    <p class="text-gray-400 text-xs mt-1">Usually 7 days from sale date</p>
                </div>

                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2"
                        class="form-textarea" style="width:100%;"
                        placeholder="Any additional details">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Save Sale
                </button>
                <a href="{{ route('sales.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection