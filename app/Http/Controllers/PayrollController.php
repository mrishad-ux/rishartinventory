<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Staff;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('staff')->latest()->get();
        $totalUnpaid = Payroll::where('status', 'unpaid')->sum('net_amount');
        $monthTotal = Payroll::whereMonth('payment_date', now()->month)->sum('net_amount');

        return view('payroll.index', compact('payrolls', 'totalUnpaid', 'monthTotal'));
    }

    public function create()
    {
        $staff = Staff::where('status', 'active')->get();

        return view('payroll.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'payment_date' => 'required|date',
            'days_worked' => 'nullable|numeric',
            'basic_amount' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable',
        ]);

        $data = $request->all();
        $data['bonus'] = $request->bonus ?? 0;
        $data['deduction'] = $request->deduction ?? 0;
        $data['net_amount'] = $request->basic_amount + $data['bonus'] - $data['deduction'];

        Payroll::create($data);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll recorded successfully!');
    }

    public function edit(Payroll $payroll)
    {
        $staff = Staff::where('status', 'active')->get();

        return view('payroll.edit', compact('payroll', 'staff'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'payment_date' => 'required|date',
            'days_worked' => 'nullable|numeric',
            'basic_amount' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable',
        ]);

        $data = $request->all();
        $data['bonus'] = $request->bonus ?? 0;
        $data['deduction'] = $request->deduction ?? 0;
        $data['net_amount'] = $request->basic_amount + $data['bonus'] - $data['deduction'];

        $payroll->update($data);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll updated successfully!');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record deleted successfully!');
    }

    public function show(Payroll $payroll)
    {
        return view('payroll.show', compact('payroll'));
    }
}
