<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_tag',
        'content',
        'risk_level',
        'category',
        'urgency_score',
        'is_priority',
        'ip_address',
        'archived_at',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'urgency_score' => 'float',
        'archived_at' => 'datetime',
    ];

    /**
     * Get all messages for this report.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Check if report is archived.
     */
    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /**
     * Archive the report.
     */
    public function archive(): bool
    {
        return $this->update(['archived_at' => now()]);
    }

    /**
     * Unarchive the report.
     */
    public function unarchive(): bool
    {
        return $this->update(['archived_at' => null]);
    }
}
