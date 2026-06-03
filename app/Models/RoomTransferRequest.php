<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTransferRequest extends Model
{
    protected $fillable = [
        'tenant_id', 'from_room_id', 'to_room_id',
        'reason', 'status', 'admin_notes'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function fromRoom()
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function toRoom()
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }
}