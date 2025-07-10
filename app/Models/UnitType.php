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
        'garage',
        'floors',
        'features',
        'floor_plan',
        'images'
    ];

    protected $casts = [
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'garage' => 'integer',
        'floors' => 'integer',
        'features' => 'array',
        'images' => 'array'
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
