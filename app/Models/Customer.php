<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'id_number',
        'address',
        'city',
        'province',
        'postal_code',
        'occupation',
        'monthly_income',
        'profile_photo',
        'segment',
        'notes',
        'last_interaction_at'
    ];

    protected $casts = [
        'monthly_income' => 'decimal:0',
        'last_interaction_at' => 'datetime'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'email', 'email');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('bookings', function ($q) {
            $q->whereIn('status', ['confirmed', 'paid']);
        });
    }

    public function scopeVip($query)
    {
        return $query->where('segment', 'vip');
    }

    public function scopeProspect($query)
    {
        return $query->where('segment', 'prospect');
    }

    public function scopeBySegment($query, $segment)
    {
        return $query->where('segment', $segment);
    }

    // Accessors
    public function getBookingsCountAttribute()
    {
        return $this->bookings()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->bookings()
            ->where('status', 'paid')
            ->sum('total_price');
    }

    public function getActiveBookingsCountAttribute()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'paid'])
            ->count();
    }

    public function getLastBookingAttribute()
    {
        return $this->bookings()
            ->latest()
            ->first();
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code
        ]);

        return implode(', ', $parts);
    }

    // Methods
    public function updateSegment()
    {
        $totalSpent = $this->total_spent;
        $bookingsCount = $this->bookings_count;

        if ($totalSpent >= 2000000000 || $bookingsCount >= 3) {
            $segment = 'vip';
        } elseif ($totalSpent > 0 || $bookingsCount > 0) {
            $segment = 'active';
        } elseif ($this->leads()->exists()) {
            $segment = 'prospect';
        } else {
            $segment = 'inactive';
        }

        $this->update(['segment' => $segment]);
    }

    public function recordInteraction()
    {
        $this->update(['last_interaction_at' => now()]);
    }
}
