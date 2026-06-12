<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller {
    /**
     * Show monthly oil consumption summary.
     *
     * Returns JSON: [{ month: '2026-05', total_liters: 12.3 }, ...]
     */
    public function monthlyOil(Request $request) {
        // Assume `oil_logs` table with columns: id, fryer (string), liters, logged_at (datetime)
        $logs = DB::table('oil_logs')
            ->select(
                DB::raw("DATE_FORMAT(logged_at, '%Y-%m') as month"),
                DB::raw('SUM(liters) as total_liters')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
        return response()->json($logs);
    }
}
