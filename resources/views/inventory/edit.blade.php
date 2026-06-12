@extends('layouts.app')
@section('title', 'Edit Item')

@section('content')
<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('inventory.index') }}" style="color:#64748b;">← Back</a>
            <div class="page-title">Edit Item</div>
        </div>

        <div class="page-subtitle" style="margin-bottom:24px;">Update inventory item</div>

        <form action="{{ route('inventory.update', $inventory) }}" method="POST">
            @csrf @method('PUT')

            <div style="display:flex; flex-direction:column; gap:20px;">
                <div class="form-group">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}" required
                           class="form-input" style="width:100%;">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" required class="form-select" style="width:100%;">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $inventory->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Unit</label>
                    <select name="unit" required class="form-select" style="width:100%;">
                        @foreach(['Gms','kg','pkt','Nos','ltr','ml'] as $u)
                            <option value="{{ $u }}" {{ old('unit', $inventory->unit) === $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Minimum Stock (triggers Low Stock alert when closing stock falls below this)</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $inventory->minimum_stock) }}" step="0.01" min="0"
                           class="form-input" style="width:100%;">
                </div>

                <div class="form-group">
                    <label class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" value="{{ old('unit_price', $inventory->unit_price) }}" step="0.01" min="0"
                           class="form-input" style="width:100%;">
                </div>

                <div style="display:flex; align-items:center; gap:12px; padding:12px; background:rgba(234,88,12,0.1); border:1px solid rgba(234,88,12,0.2); border-radius:8px;">
                    <input type="checkbox" name="is_mayo" id="is_mayo" value="1"
                           {{ old('is_mayo', $inventory->is_mayo) ? 'checked' : '' }}
                           style="width:16px; height:16px; accent-color:var(--accent);">
                    <label for="is_mayo" style="font-size:14px;">
                        Oil + Milk + Bottles tracking (Mayo type)
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:24px;">
                <button type="submit" class="btn-primary"" style="flex:1;">
                    Update Item
                </button>
                <a href="{{ route('inventory.index') }}" class="btn-secondary"" style="flex:1; text-align:center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
