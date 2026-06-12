<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $activeStaff = Staff::where('status', 'active')->get();
        $inactiveStaff = Staff::where('status', 'inactive')->get();

        return view('staff.index', compact('activeStaff', 'inactiveStaff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'phone' => 'nullable',
            'salary_type' => 'required|in:daily,monthly',
            'salary_amount' => 'required|numeric',
            'joining_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully!');
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'phone' => 'nullable',
            'salary_type' => 'required|in:daily,monthly',
            'salary_amount' => 'required|numeric',
            'joining_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member removed successfully!');
    }

    public function show(Staff $staff)
    {
        $payrolls = $staff->payrolls()->latest()->take(10)->get();

        return view('staff.show', compact('staff', 'payrolls'));
    }
}
