<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'room_id', 'name', 'phone', 'password',
        'must_change_password', 'id_card',
        'start_date', 'end_date', 'status', 'notes'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'start_date'           => 'date',
        'end_date'             => 'date',
        'must_change_password' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function transferRequests()
    {
        return $this->hasMany(RoomTransferRequest::class);
    }
}