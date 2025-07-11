<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get top performing projects
        $topProjects = $this->getTopPerformingProjects();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get recent bookings
        $recentBookings = $this->getRecentBookings();

        // Get today's schedule
        $todaySchedule = $this->getTodaySchedule();

        return view('dashboard', compact(
            'stats',
            'topProjects',
            'recentActivities',
            'recentBookings',
            'todaySchedule'
        ));
    }

    private function getDashboardStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Calculate total revenue from sold units
        $totalRevenue = Unit::where('status', 'sold')->sum('price');
        $lastMonthRevenue = Unit::where('status', 'sold')
            ->whereBetween('updated_at', [$lastMonth, $currentMonth])
            ->sum('price');

        $revenueGrowth = $lastMonthRevenue > 0 ?
            round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        // Projects statistics
        $activeProjects = Project::whereIn('status', ['development', 'ready'])->count();
        $newProjectsThisMonth = Project::where('created_at', '>=', $currentMonth)->count();

        // Units statistics
        $unitsSold = Unit::where('status', 'sold')->count();
        $availableUnits = Unit::where('status', 'available')->count();

        // Leads statistics
        $newLeads = Lead::where('created_at', '>=', $currentMonth)->count();
        $leadsToday = Lead::whereDate('created_at', Carbon::today())->count();

        // Lead funnel statistics
        $totalLeads = Lead::count();
        $contactedLeads = Lead::whereNotNull('last_contact_date')->count();
        $qualifiedLeads = Lead::where('status', 'qualified')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();

        return [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => $revenueGrowth,
            'projects' => $activeProjects,
            'new_projects' => $newProjectsThisMonth,
            'units_sold' => $unitsSold,
            'available_units' => $availableUnits,
            'new_leads' => $newLeads,
            'leads_today' => $leadsToday,
            'funnel' => [
                'new_leads' => $totalLeads,
                'contacted' => $contactedLeads,
                'contacted_percentage' => $totalLeads > 0 ? round(($contactedLeads / $totalLeads) * 100) : 0,
                'qualified' => $qualifiedLeads,
                'qualified_percentage' => $totalLeads > 0 ? round(($qualifiedLeads / $totalLeads) * 100) : 0,
                'converted' => $convertedLeads,
                'converted_percentage' => $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100) : 0,
            ]
        ];
    }

    private function getTopPerformingProjects()
    {
        return Project::with(['location'])
            ->withCount([
                'units as units_sold' => function ($query) {
                    $query->where('status', 'sold');
                }
            ])
            ->orderBy('units_sold', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent bookings
        $recentBookings = Booking::with(['customer', 'unit.project'])
            ->latest()
            ->limit(3)
            ->get();

        foreach ($recentBookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'icon' => 'bookmark',
                'title' => 'New Booking',
                'description' => ($booking->customer->name ?? 'Unknown Customer') . ' booked ' . ($booking->unit->unit_code ?? 'Unit'),
                'time' => $booking->created_at->diffForHumans(),
                'created_at' => $booking->created_at
            ]);
        }

        // Recent leads
        $recentLeads = Lead::latest()
            ->limit(3)
            ->get();

        foreach ($recentLeads as $lead) {
            $activities->push([
                'type' => 'lead',
                'icon' => 'user-plus',
                'title' => 'New Lead',
                'description' => ($lead->name ?? 'Unknown') . ' submitted an inquiry',
                'time' => $lead->created_at->diffForHumans(),
                'created_at' => $lead->created_at
            ]);
        }

        // Recent contracts
        $recentContracts = Contract::with(['booking.customer'])
            ->latest()
            ->limit(2)
            ->get();

        foreach ($recentContracts as $contract) {
            $activities->push([
                'type' => 'contract',
                'icon' => 'file-contract',
                'title' => 'Contract Signed',
                'description' => 'Contract ' . ($contract->contract_number ?? 'Unknown') . ' was signed',
                'time' => $contract->created_at->diffForHumans(),
                'created_at' => $contract->created_at
            ]);
        }

        return $activities->sortByDesc('created_at')->take(6)->values();
    }

    private function getRecentBookings()
    {
        return Booking::with(['customer', 'unit.project'])
            ->latest()
            ->limit(5)
            ->get();
    }

    private function getTodaySchedule()
    {
        $schedule = collect();

        // Upcoming follow-ups from leads
        $followUps = Lead::whereDate('next_follow_up', Carbon::today())
            ->with(['assignedTo'])
            ->get();

        foreach ($followUps as $followUp) {
            $schedule->push([
                'time' => $followUp->next_follow_up ? $followUp->next_follow_up->format('H:i') : '09:00',
                'type' => 'Follow-up',
                'title' => 'Follow up with ' . ($followUp->name ?? 'Unknown'),
            ]);
        }

        // Bookings expiring today
        $expiringBookings = Booking::whereDate('expired_at', Carbon::today())
            ->where('status', 'pending')
            ->with(['customer'])
            ->get();

        foreach ($expiringBookings as $booking) {
            $schedule->push([
                'time' => '10:00',
                'type' => 'Reminder',
                'title' => 'Booking expires: ' . ($booking->customer->name ?? 'Unknown Customer'),
            ]);
        }

        // Add some default scheduled items if empty
        if ($schedule->isEmpty()) {
            $schedule->push([
                'time' => '09:00',
                'type' => 'Meeting',
                'title' => 'Team standup meeting',
            ]);
            $schedule->push([
                'time' => '14:00',
                'type' => 'Review',
                'title' => 'Weekly sales review',
            ]);
        }

        return $schedule->sortBy('time')->values();
    }
}
