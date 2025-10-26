<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the restaurants for this cuisine.
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'cuisine_restaurant');
    }
}