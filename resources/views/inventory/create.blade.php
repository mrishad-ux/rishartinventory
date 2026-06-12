@extends('layouts.app')
@section('title', 'Add Inventory Item')

@section('content')
<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('inventory.index') }}" style="color:#64748b;">← Back</a>
            <div class="page-title">Add Item</div>
        </div>

        <div class="page-subtitle" style="margin-bottom:24px;">Add inventory item</div>

        <form action="{{ route('inventory.store') }}" method="POST" x-data="{ isMayo: false }">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">
                <div class="form-group">
                    <label class="form-label">Item Name <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-input" style="width:100%;"
                           placeholder="e.g. Zinger Masala">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Category <span style="color:#dc2626;">*</span></label>
                    <select name="category" required class="form-select" style="width:100%;">
                        <option value="">Select category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Unit <span style="color:#dc2626;">*</span></label>
                    <select name="unit" required class="form-select" style="width:100%;">
                        <option value="">Select unit</option>
                        <option value="Gms" {{ old('unit') === 'Gms' ? 'selected' : '' }}>Gms</option>
                        <option value="kg"  {{ old('unit') === 'kg'  ? 'selected' : '' }}>kg</option>
                        <option value="pkt" {{ old('unit') === 'pkt' ? 'selected' : '' }}>pkt</option>
                        <option value="Nos" {{ old('unit') === 'Nos' ? 'selected' : '' }}>Nos</option>
                        <option value="ltr" {{ old('unit') === 'ltr' ? 'selected' : '' }}>ltr</option>
                        <option value="ml"  {{ old('unit') === 'ml'  ? 'selected' : '' }}>ml</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Minimum Stock (triggers Low Stock alert when closing stock falls below this)</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" step="0.01" min="0"
                           class="form-input" style="width:100%;">
                </div>

                <div class="form-group">
                    <label class="form-label">Unit Price (optional)</label>
                    <input type="number" name="unit_price" value="{{ old('unit_price', 0) }}" step="0.01" min="0"
                           class="form-input" style="width:100%;">
                </div>

                <div style="display:flex; align-items:center; gap:12px; padding:12px; background:rgba(234,88,12,0.1); border:1px solid rgba(234,88,12,0.2); border-radius:8px;">
                    <input type="checkbox" name="is_mayo" id="is_mayo" value="1"
                           {{ old('is_mayo') ? 'checked' : '' }}
                           x-model="isMayo"
                           style="width:16px; height:16px; accent-color:var(--accent);">
                    <label for="is_mayo" style="font-size:14px;">
                        This item uses <strong>Oil + Milk + Bottles</strong> tracking (like Eggless Mayo)
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-primary"" style="flex:1;">
                    Add Item
                </button>
                <a href="{{ route('inventory.index') }}" class="btn-secondary"" style="flex:1; text-align:center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
