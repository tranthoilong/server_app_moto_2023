<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function Report_invoice(Request $request)
    {
        // Get filter values from request
        $filterMonth = $request->input('filter_month');
        $filterQuarter = $request->input('filter_quarter');
        $filterYear = $request->input('filter_year');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Create the initial query builder
        $query = Order::where('status', 3);

        // Apply the filter conditions if provided
        if ($filterMonth) {
            $query->whereMonth('created_at', $filterMonth);
        }
        if ($filterQuarter) {
            $query->whereRaw('QUARTER(created_at) = ?', [$filterQuarter]);
        }
        if ($filterYear) {
            $query->whereYear('created_at', $filterYear);
        }
        if ($fromDate && $toDate) {
            $query->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate);
        }

        // Perform the query and retrieve the list of orders
        $orders = $query->get();

        return view('backend.report.report_invoice', compact('orders'));
    }

}
