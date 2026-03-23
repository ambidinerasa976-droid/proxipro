<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id', 'points', 'type', 'description', 
        'source', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
