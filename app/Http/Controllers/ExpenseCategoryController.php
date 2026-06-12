<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        
        $label = trim($request->name);
        $value = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $label));
        
        if (ExpenseCategory::where('value', $value)->exists()) {
            return response()->json(['success' => false, 'message' => 'Category already exists']);
        }
        
        $cat = ExpenseCategory::create([
            'label'      => $label,
            'value'      => $value,
            'is_custom'  => true,
            'sort_order' => 99,
        ]);
        
        return response()->json([
            'success' => true,
            'label'   => $cat->label,
            'value'   => $cat->value,
        ]);
    }
}
