<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'unit_type_id',
        'unit_code',
        'status',
        'price',
        'discount_price',
        'facing',
        'certificate',
        'cash_hard_percentage',
        'cash_tempo_percentage',
        'description',
        'notes',
        'images'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cash_hard_percentage' => 'decimal:2',
        'cash_tempo_percentage' => 'decimal:2',
        'images' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    // Accessors
    public function getEffectivePriceAttribute()
    {
        return $this->discount_price ?: $this->price;
    }

    public function getPricePerMeterAttribute()
    {
        return $this->unitType && $this->unitType->building_area > 0
            ? $this->effective_price / $this->unitType->building_area
            : 0;
    }
}
