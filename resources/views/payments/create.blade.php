@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')

<div style="padding:0">
    <div class="card" style="max-width:640px; margin:0 auto; padding:32px;">

        <div style="margin-bottom:24px;">
            <div class="page-title">Record Payment</div>
            <div class="page-subtitle">Record a payment against an expense</div>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
            @csrf

            <div style="display:flex; flex-direction:column; gap:20px;">

                <div class="form-group">
                    <label class="form-label">Expense *</label>
                    <div style="position:relative;">
                        <input type="text" id="expense-search" class="form-input"
                            placeholder="Search expenses..." autocomplete="off"
                            oninput="filterExpenses(this.value)"
                            style="margin-bottom:6px;">
                        <select name="expense_id" id="expense-select"
                            class="form-select" style="width:100%;"
                            onchange="updatePendingAmount(this.value)">
                            <option value="">-- Select an Expense --</option>
                            @foreach($expenses as $expense)
                            <option value="{{ $expense->id }}"
                                data-amount="{{ $expense->amount }}"
                                data-paid="{{ $expense->paid_amount }}"
                                data-pending="{{ $expense->amount - $expense->paid_amount }}">
                                [{{ $expense->supplier->name ?? $expense->vendor_name ?? 'N/A' }}] — ₹{{ number_format($expense->amount, 2) }}
                                (Pending: ₹{{ number_format($expense->amount - $expense->paid_amount, 2) }})
                                @if($expense->title) - {{ $expense->title }} @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('expense_id') <p class="form-error">{{ $message }}</p> @enderror

                    <div id="pending-amount-display" style="margin-top:8px; display:none;">
                        <span style="font-size:13px; color:#64748b;">Pending Amount: </span>
                        <span id="pending-amount-value" style="font-size:16px; font-weight:700; color:var(--accent);">₹0.00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="payment-amount"
                        value="{{ old('amount') }}"
                        class="form-input" style="width:100%;"
                        placeholder="0.00">
                    @error('amount') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', $defaultDate) }}"
                        class="form-input" style="width:100%;">
                    @error('payment_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2"
                        class="form-textarea" style="width:100%;"
                        placeholder="Any additional details (optional)">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
                <button type="submit" class="btn-primary"" id="save-btn">
                    <span id="btn-text">Save Payment</span>
                    <span id="btn-loader" style="display:none;">⏳</span>
                </button>
                <a href="{{ route('payments.index') }}" class="btn-secondary"">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function filterExpenses(query) {
    const select = document.getElementById('expense-select');
    const options = select.querySelectorAll('option');
    query = query.toLowerCase().trim();

    options.forEach(opt => {
        if (opt.value === '') return;
        const text = opt.textContent.toLowerCase();
        opt.style.display = (!query || text.includes(query)) ? '' : 'none';
    });
}

function updatePendingAmount(expenseId) {
    const display = document.getElementById('pending-amount-display');
    const valueSpan = document.getElementById('pending-amount-value');

    if (!expenseId) {
        display.style.display = 'none';
        return;
    }

    const select = document.getElementById('expense-select');
    const selected = select.options[select.selectedIndex];

    if (selected && selected.dataset.pending) {
        const pending = parseFloat(selected.dataset.pending);
        valueSpan.textContent = '₹' + pending.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        display.style.display = 'block';
    } else {
        // Fallback: fetch from server
        fetch('/expenses/' + expenseId + '/payment-info')
            .then(r => r.json())
            .then(data => {
                valueSpan.textContent = '₹' + data.pending_amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                display.style.display = 'block';
            })
            .catch(() => {
                display.style.display = 'none';
            });
    }
}

// Show pending amount on page load if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('expense-select');
    if (select.value) {
        updatePendingAmount(select.value);
    }

    // Loading state on submit
    document.getElementById('paymentForm').addEventListener('submit', function() {
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-loader').style.display = 'inline';
        document.getElementById('save-btn').disabled = true;
    });
});
</script>

@endsection