<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'priority',
        'customer_id',
        'project_id', // maps to interested_project relationship
        'unit_id',
        'sales_agent_id',
        'budget_min',
        'budget_max',
        'preferred_location',
        'requirements',
        'notes',
        'assigned_to',
        'last_contact_date',
        'next_follow_up',
        'conversion_date'
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'last_contact_date' => 'datetime',
        'next_follow_up' => 'datetime',
        'conversion_date' => 'datetime'
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesAgent()
    {
        return $this->belongsTo(User::class, 'sales_agent_id');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    // Legacy method for backward compatibility
    public function interestedProject()
    {
        return $this->project();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Accessors
    public function getDaysOldAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get the human readable last contacted date.
     *
     * @return string
     */
    public function getLastContactedHumanAttribute()
    {
        $date = $this->last_contact_date;
        return $date instanceof Carbon ? $date->diffForHumans() : 'Never';
    }
}
