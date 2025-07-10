<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'unit_id',
        'sales_agent_id',
        'booking_date',
        'booking_fee',
        'unit_price',
        'discount_amount',
        'total_price',
        'status',
        'payment_method',
        'notes',
        'expired_at'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_fee' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'expired_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesAgent()
    {
        return $this->belongsTo(User::class, 'sales_agent_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<', now())
            ->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'completed']);
    }

    // Mutators
    public function setBookingNumberAttribute($value)
    {
        $this->attributes['booking_number'] = $value ?: $this->generateBookingNumber();
    }

    // Accessors
    public function getNetPriceAttribute()
    {
        return $this->unit_price - $this->discount_amount;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expired_at && $this->expired_at->isPast() && $this->status === 'pending';
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expired_at || $this->status !== 'pending') {
            return null;
        }

        return now()->diffInDays($this->expired_at, false);
    }

    // Helper methods
    private function generateBookingNumber()
    {
        $prefix = 'BK';
        $date = now()->format('Ymd');
        $sequence = str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $date . $sequence;
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);

        // Update unit status
        $this->unit->update(['status' => 'sold']);
    }

    public function confirm()
    {
        $this->update(['status' => 'confirmed']);

        // Update unit status
        $this->unit->update(['status' => 'booked']);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);

        // Update unit status back to available
        $this->unit->update(['status' => 'available']);
    }
}
