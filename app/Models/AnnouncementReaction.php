<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementReaction extends Model
{
    protected $fillable = [
        'announcement_id', 'tenant_id', 'reaction',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
