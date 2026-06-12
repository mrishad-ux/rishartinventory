<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('expense')
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        $totalPaymentsCount = Payment::count();
        $totalAmount = Payment::sum('amount');

        return view('payments.index', compact('payments', 'totalPaymentsCount', 'totalAmount'));
    }

    public function create()
    {
        $expenses = Expense::where('paid_amount', '<', DB::raw('amount'))->get();
        $defaultDate = now()->format('Y-m-d');

        return view('payments.create', compact('expenses', 'defaultDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_id'   => 'required|exists:expenses,id',
            'amount'       => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $expense = Expense::findOrFail($validated['expense_id']);

        DB::transaction(function () use ($validated, $expense) {
            Payment::create($validated);

            $newPaidAmount = $expense->paid_amount + $validated['amount'];
            $cappedAmount = min($newPaidAmount, $expense->amount);
            $expense->update(['paid_amount' => $cappedAmount]);
        });

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load('expense');
        return view('payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $expense = $payment->expense;
            $newPaidAmount = $expense->paid_amount - $payment->amount;
            $expense->update(['paid_amount' => max(0, $newPaidAmount)]);
            $payment->delete();
        });

        return redirect()->back()
            ->with('success', 'Payment deleted successfully!');
    }
}