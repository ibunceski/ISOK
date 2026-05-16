<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'risk_level',
        'category',
        'urgency_score',
        'is_priority',
        'ip_address',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'urgency_score' => 'float',
    ];
}
