<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Booking;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Quick Statistics
        $stats = [
            'total_units' => Unit::count(),
            'available_units' => Unit::where('status', 'available')->count(),
            'booked_units' => Unit::where('status', 'booked')->count(),
            'sold_units' => Unit::where('status', 'sold')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_customers' => Customer::count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'dp_paid', 'completed'])->sum('total_price'),
        ];

        // Monthly Sales Chart (Last 6 months)
        $monthlySales = Booking::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
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

        // Top Projects by Sales
        $topProjects = Project::withCount([
            'units as sold_units' => function ($query) {
                $query->where('status', 'sold');
            }
        ])
            ->having('sold_units', '>', 0)
            ->orderBy('sold_units', 'desc')
            ->limit(5)
            ->get();

        // Recent Activities
        $recentActivities = collect();
        
        // Add recent bookings to activities
        foreach ($recentBookings->take(3) as $booking) {
            $recentActivities->push([
                'type' => 'booking',
                'title' => 'New Booking',
                'description' => ($booking->customer->name ?? 'Unknown customer') . ' booked ' . ($booking->unit->unit_code ?? 'a unit'),
                'time' => $booking->created_at->diffForHumans(),
                'icon' => 'calendar-plus'
            ]);
        }
        
        // Add recent leads to activities
        foreach ($recentLeads->take(2) as $lead) {
            $recentActivities->push([
                'type' => 'lead',
                'title' => 'New Lead',
                'description' => $lead->name . ' showed interest in ' . ($lead->project->name ?? 'a project'),
                'time' => $lead->created_at->diffForHumans(),
                'icon' => 'user-plus'
            ]);
        }
        
        // Sort by time and take latest 5
        $recentActivities = $recentActivities->sortByDesc(function($item) {
            return $item['time'];
        })->take(5);

        // Lead Sources
        $leadSources = Lead::select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get();

        // Today's Schedule - Add this missing variable
        $todaySchedule = collect();
        
        // Add today's appointments/meetings (if you have an appointments table)
        // If you don't have appointments, create some sample schedule items from bookings and leads
        
        // Today's new bookings
        $todayBookings = Booking::with(['customer', 'unit'])
            ->whereDate('created_at', Carbon::today())
            ->get();
            
        foreach ($todayBookings as $booking) {
            $todaySchedule->push([
                'time' => $booking->created_at->format('H:i'),
                'type' => 'booking',
                'title' => 'New Booking',
                'description' => 'Booking from ' . ($booking->customer->name ?? 'Unknown'),
                'status' => 'completed'
            ]);
        }
        
        // Today's new leads
        $todayLeads = Lead::whereDate('created_at', Carbon::today())->get();
        
        foreach ($todayLeads as $lead) {
            $todaySchedule->push([
                'time' => $lead->created_at->format('H:i'),
                'type' => 'lead',
                'title' => 'New Lead',
                'description' => 'Lead from ' . $lead->name,
                'status' => 'pending'
            ]);
        }
        
        // Follow-up calls for leads that need attention
        $followUpLeads = Lead::where('status', 'contacted')
            ->where('updated_at', '<=', Carbon::now()->subDays(3))
            ->limit(3)
            ->get();
            
        foreach ($followUpLeads as $lead) {
            $todaySchedule->push([
                'time' => '14:00', // Default follow-up time
                'type' => 'follow-up',
                'title' => 'Follow-up Call',
                'description' => 'Follow up with ' . $lead->name,
                'status' => 'pending'
            ]);
        }
        
        // Sort schedule by time
        $todaySchedule = $todaySchedule->sortBy('time')->values();

        // Upcoming Tasks (optional - you can create a tasks table later)
        $upcomingTasks = collect([
            [
                'title' => 'Review pending bookings',
                'due_date' => Carbon::now()->addHours(2),
                'priority' => 'high',
                'type' => 'review'
            ],
            [
                'title' => 'Call new leads',
                'due_date' => Carbon::now()->addHours(4),
                'priority' => 'medium',
                'type' => 'call'
            ],
            [
                'title' => 'Update project status',
                'due_date' => Carbon::now()->addDay(),
                'priority' => 'low',
                'type' => 'update'
            ]
        ]);

        return view('dashboard', compact(
            'stats',
            'monthlySales',
            'recentBookings',
            'recentLeads',
            'topProjects',
            'leadSources',
            'recentActivities',
            'todaySchedule',
            'upcomingTasks'
        ));
    }
}
