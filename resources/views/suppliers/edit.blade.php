@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Edit Supplier</div>
            <div class="page-subtitle">Update supplier details</div>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div class="form-group">
                    <label class="form-label">Supplier Name *</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                        class="form-input" style="width:100%;">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                            class="form-input" style="width:100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                            class="form-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Supplied</label>
                    <input type="text" name="product_supplied" value="{{ old('product_supplied', $supplier->product_supplied) }}"
                        class="form-input" style="width:100%;">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2"
                        class="form-textarea" style="width:100%;">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Outstanding Balance (₹)</label>
                    <input type="number" step="0.01" name="outstanding_balance" value="{{ old('outstanding_balance', $supplier->outstanding_balance) }}"
                        class="form-input" style="width:100%;">
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"">
                    Update Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
