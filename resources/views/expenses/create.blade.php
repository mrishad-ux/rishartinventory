@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">
        
        <div style="margin-bottom:24px;">
            <div class="page-title">Add Expense</div>
            <div class="page-subtitle">Record a new outgoing payment</div>
        </div>

        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="form-input" style="width:100%;"
                        placeholder="e.g. Chicken Purchase, Gas Bill, Staff Salary">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" id="category-select"
                            class="form-select" style="width:100%;"
                            onchange="handleCategoryChange(this)">
                            <option value="">-- Select Category --</option>
                            <option value="raw_materials">Raw Materials</option>
                            <option value="masala">Masala</option>
                            <option value="salary">Salary</option>
                            <option value="packing_materials">Packing Materials</option>
                            <option value="cleaning_materials">Cleaning Materials</option>
                            <option value="petty_cash">Petty Cash</option>
                            <option value="utilities">Utilities</option>
                            <option value="maintenance_repairs">Maintenance and Repairs</option>
                            <option value="marketing">Marketing</option>
                            <option value="others">Others</option>
                            @foreach($customCategories ?? [] as $cat)
                                <option value="{{ $cat->value }}" {{ old('category') == $cat->value ? 'selected' : '' }}>
                                    {{ $cat->label }}
                                </option>
                            @endforeach
                            <option value="__add_new__">➕ Add New Category...</option>
                        </select>
                        @error('category') <p class="form-error">{{ $message }}</p> @enderror
                        
                        <div id="custom-category-input" class="mt-2" style="display:none;">
                            <input type="text" id="new-category-name"
                                placeholder="Enter new category name"
                                class="form-input" style="width:100%;">
                            <div style="display:flex; gap:8px; margin-top:8px;">
                                <button type="button" onclick="saveNewCategory()" class="btn-secondary"">
                                    Save Category
                                </button>
                                <button type="button" onclick="cancelNewCategory()" class="btn-secondary"">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount (₹) *</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                            class="form-input" style="width:100%;"
                            placeholder="0.00">
                        @error('amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Expense Date *</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}"
                            class="form-input" style="width:100%;">
                        @error('expense_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" style="width:100%;">
                            <option value="">-- No Supplier --</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
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
                    Save Expense
                </button>
                <a href="{{ route('expenses.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function handleCategoryChange(select) {
    if (select.value === '__add_new__') {
        document.getElementById('custom-category-input').style.display = 'block';
        select.value = '';
    } else {
        document.getElementById('custom-category-input').style.display = 'none';
    }
}

function saveNewCategory() {
    const name = document.getElementById('new-category-name').value.trim();
    if (!name) { alert('Please enter a category name'); return; }
    
    fetch('{{ route("expense-categories.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ name: name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('category-select');
            const newOption = new Option(data.label, data.value);
            const addNewOption = select.querySelector('option[value="__add_new__"]');
            select.insertBefore(newOption, addNewOption);
            select.value = data.value;
            document.getElementById('custom-category-input').style.display = 'none';
            document.getElementById('new-category-name').value = '';
        }
    });
}

function cancelNewCategory() {
    document.getElementById('custom-category-input').style.display = 'none';
    document.getElementById('new-category-name').value = '';
    document.getElementById('category-select').value = '';
}
</script>

@endsection