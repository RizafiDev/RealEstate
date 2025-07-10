<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Booking;
use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Sales Summary - Remove dp_amount references
        $salesSummary = [
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'dp_paid', 'completed'])->sum('total_price'),
            // Remove this line if 'dp_amount' column doesn't exist
            // 'total_dp_collected' => Booking::whereIn('status', ['dp_paid', 'completed'])->sum('dp_amount'),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        // Unit Status Summary
        $unitSummary = [
            'total_units' => Unit::count(),
            'available' => Unit::where('status', 'available')->count(),
            'booked' => Unit::where('status', 'booked')->count(),
            'sold' => Unit::where('status', 'sold')->count(),
        ];

        // Lead Conversion
        $leadSummary = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'qualified_leads' => Lead::where('status', 'qualified')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'conversion_rate' => Lead::count() > 0 ? round((Lead::where('status', 'converted')->count() / Lead::count()) * 100, 2) : 0,
        ];

        // Monthly Sales Chart Data
        $monthlySales = Booking::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Project Performance
        $projectPerformance = Project::withCount([
            'units',
            'units as available_units' => function ($query) {
                $query->where('status', 'available');
            },
            'units as booked_units' => function ($query) {
                $query->where('status', 'booked');
            },
            'units as sold_units' => function ($query) {
                $query->where('status', 'sold');
            }
        ])
            ->get()
            ->map(function ($project) {
                $project->sold_percentage = $project->units_count > 0
                    ? round(($project->sold_units / $project->units_count) * 100, 2)
                    : 0;
                return $project;
            });

        // Lead Sources
        $leadSources = Lead::select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get();

        // Recent Activities
        $recentBookings = Booking::with(['unit.project', 'customer'])
            ->latest()
            ->limit(5)
            ->get();

        $recentLeads = Lead::with(['project'])
            ->latest()
            ->limit(5)
            ->get();

        // Add missing variables for the view
        $projects = Project::all();
        $topProjects = $projectPerformance->sortByDesc('sold_units')->take(5);
        $topAgents = collect(); // Empty collection if no agent data
        $monthlyLeads = collect(); // Empty collection if no monthly lead data
        $conversionFunnel = [
            'leads' => Lead::count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];
        $leadStatuses = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('reports.index', compact(
            'salesSummary',
            'unitSummary',
            'leadSummary',
            'monthlySales',
            'projectPerformance',
            'leadSources',
            'recentBookings',
            'recentLeads',
            'projects',
            'topProjects',
            'topAgents',
            'monthlyLeads',
            'conversionFunnel',
            'leadStatuses'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');

        switch ($type) {
            case 'sales':
                return $this->exportSalesReport();
            case 'units':
                return $this->exportUnitsReport();
            case 'leads':
                return $this->exportLeadsReport();
            case 'customers':
                return $this->exportCustomersReport();
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }

    private function exportSalesReport()
    {
        $bookings = Booking::with(['unit.project', 'customer', 'salesPerson'])->get();

        $csvData = [];
        $csvData[] = [
            'Booking Number',
            'Customer Name',
            'Unit Number',
            'Project',
            'Booking Date',
            'Total Price',
            'DP Amount',
            'Status',
            'Sales Person',
            'Payment Method'
        ];

        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->booking_number,
                $booking->customer->name,
                $booking->unit->unit_number ?? '',
                $booking->unit->project->name ?? '',
                $booking->booking_date?->format('Y-m-d'),
                $booking->total_price,
                $booking->dp_amount,
                $booking->status,
                $booking->salesPerson->name ?? '',
                $booking->payment_method,
            ];
        }

        return $this->downloadCsv($csvData, 'sales_report_' . date('Y-m-d'));
    }

    private function exportUnitsReport()
    {
        $units = Unit::with(['project', 'unitType'])->get();

        $csvData = [];
        $csvData[] = [
            'Unit Number',
            'Project',
            'Unit Type',
            'Price',
            'Status',
            'Building Area',
            'Land Area',
            'Bedrooms',
            'Bathrooms'
        ];

        foreach ($units as $unit) {
            $csvData[] = [
                $unit->unit_number,
                $unit->project->name ?? '',
                $unit->unitType->name ?? '',
                $unit->price,
                $unit->status,
                $unit->unitType->building_area ?? '',
                $unit->unitType->land_area ?? '',
                $unit->unitType->bedrooms ?? '',
                $unit->unitType->bathrooms ?? '',
            ];
        }

        return $this->downloadCsv($csvData, 'units_report_' . date('Y-m-d'));
    }

    private function exportLeadsReport()
    {
        $leads = Lead::with(['project', 'assignedTo'])->get();

        $csvData = [];
        $csvData[] = [
            'Name',
            'Email',
            'Phone',
            'Project',
            'Source',
            'Status',
            'Budget Min',
            'Budget Max',
            'Assigned To',
            'Created At'
        ];

        foreach ($leads as $lead) {
            $csvData[] = [
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->project->name ?? '',
                $lead->source,
                $lead->status,
                $lead->budget_min,
                $lead->budget_max,
                $lead->assignedTo->name ?? '',
                $lead->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $this->downloadCsv($csvData, 'leads_report_' . date('Y-m-d'));
    }

    private function exportCustomersReport()
    {
        $customers = Customer::with(['bookings'])->get();

        $csvData = [];
        $csvData[] = [
            'Name',
            'Email',
            'Phone',
            'Customer Type',
            'Total Bookings',
            'Total Spent',
            'City',
            'Occupation',
            'Status'
        ];

        foreach ($customers as $customer) {
            $csvData[] = [
                $customer->name,
                $customer->email,
                $customer->phone,
                $customer->customer_type,
                $customer->bookings->count(),
                $customer->bookings->sum('total_price'),
                $customer->city,
                $customer->occupation,
                $customer->status,
            ];
        }

        return $this->downloadCsv($csvData, 'customers_report_' . date('Y-m-d'));
    }

    private function downloadCsv($data, $filename)
    {
        $filename = $filename . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
