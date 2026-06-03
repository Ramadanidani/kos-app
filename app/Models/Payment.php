<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tenant_id', 'room_id', 'amount',
        'due_date', 'paid_date', 'status', 'method', 'notes'
    ];

    protected $casts = [
        'due_date'  => 'date',
        'paid_date' => 'date',
        'amount'    => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}