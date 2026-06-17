<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'price', 'status',
        'size', 'floor', 'description', 'facilities'
    ];

    protected $casts = [
        'facilities' => 'array',
        'price'      => 'decimal:2',
    ];

    // Relasi ke foto
    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }

    // Foto utama
    public function primaryPhoto()
    {
        return $this->hasOne(RoomPhoto::class)->where('is_primary', true);
    }

    // Scope: hanya kamar tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}