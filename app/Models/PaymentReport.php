<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReport extends Model
{
    protected $fillable = [
        'tenant_id', 'room_id', 'period',
        'amount', 'method', 'proof_image',
        'notes', 'status','rejection_reason',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Format period jadi nama bulan
    public function getPeriodLabelAttribute()
    {
        return \Carbon\Carbon::parse($this->period . '-01')
            ->translatedFormat('F Y');
    }
}