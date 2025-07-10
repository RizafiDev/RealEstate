<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'developer_id',
        'location_id',
        'name',
        'description',
        'address',
        'status',
        'start_date',
        'estimated_completion',
        'total_units',
        'facilities',
        'phone',
        'sales_phone',
        'sales_email',
        'images',
        'master_plan',
        'brochure_url'
    ];

    protected $casts = [
        'start_date' => 'date',
        'estimated_completion' => 'date',
        'images' => 'array',
        'facilities' => 'array',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function unitTypes()
    {
        return $this->hasMany(UnitType::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProjectGallery::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'interested_project');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['development', 'ready']);
    }

    // Accessors
    public function getAvailableUnitsAttribute()
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getSoldUnitsAttribute()
    {
        return $this->units()->where('status', 'sold')->count();
    }

    public function getBookedUnitsAttribute()
    {
        return $this->units()->where('status', 'booked')->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->units()->where('status', 'sold')->sum('price');
    }

    public function getCompletionPercentageAttribute()
    {
        $totalUnits = $this->total_units;
        if ($totalUnits == 0)
            return 0;

        return ($this->sold_units / $totalUnits) * 100;
    }
}
