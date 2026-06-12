<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('supplier')->latest()->get();
        $totalPaid = Expense::sum('paid_amount');
        $totalUnpaid = Expense::selectRaw('COALESCE(SUM(amount - paid_amount), 0) as total')->value('total');

        return view('expenses.index', compact('expenses', 'totalUnpaid', 'totalPaid'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $customCategories = ExpenseCategory::where('is_custom', true)
            ->orderBy('sort_order')->get();

        return view('expenses.create', compact('suppliers', 'customCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function edit(Expense $expense)
    {
        $suppliers = Supplier::all();
        $customCategories = ExpenseCategory::where('is_custom', true)
            ->orderBy('sort_order')->get();

        return view('expenses.edit', compact('expense', 'suppliers', 'customCategories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function paymentInfo(Expense $expense)
    {
        return response()->json([
            'id'           => $expense->id,
            'amount'       => (float) $expense->amount,
            'paid_amount'  => (float) $expense->paid_amount,
            'pending_amount' => (float) ($expense->amount - $expense->paid_amount),
        ]);
    }
}
