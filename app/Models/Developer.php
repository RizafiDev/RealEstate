<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Developer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'status',
        'established_year'
    ];

    protected $casts = [
        'established_year' => 'integer'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Accessors
    public function getActiveProjectsCountAttribute()
    {
        return $this->projects()
            ->whereIn('status', ['development', 'ready'])
            ->count();
    }

    public function getTotalUnitsAttribute()
    {
        return $this->projects()
            ->sum('total_units');
    }
}
