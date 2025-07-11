<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'land_area',
        'building_area',
        'bedrooms',
        'bathrooms',
        'garages', // Changed from 'garage' to 'garages'
        'floors',
        'specifications', // Added missing 'specifications'
        'floor_plan',
    ];

    protected $casts = [
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'garages' => 'integer', // Changed from 'garage' to 'garages'
        'floors' => 'integer',
        'specifications' => 'array', // Added specifications casting
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    // Accessors
    public function getAvailableUnitsCountAttribute()
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getTotalUnitsCountAttribute()
    {
        return $this->units()->count();
    }

    public function getStartingPriceAttribute()
    {
        return $this->units()->min('price');
    }
}
