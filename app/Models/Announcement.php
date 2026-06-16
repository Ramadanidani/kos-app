<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'priority', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->hasMany(AnnouncementReaction::class);
    }

    public function reactionCounts()
    {
        return $this->reactions()
            ->selectRaw('reaction, count(*) as total')
            ->groupBy('reaction');
    }

    public function tenantReaction($tenantId)
    {
        return $this->reactions()->where('tenant_id', $tenantId)->first();
    }
}
