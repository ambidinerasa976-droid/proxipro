<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name', 'description', 'icon', 'color',
        'points_required', 'level_required'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps()
                    ->withPivot('earned_at');
    }
}
