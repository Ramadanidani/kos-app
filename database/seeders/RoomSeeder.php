<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Kamar A1', 'type' => 'Standard', 'price' => 800000,  'status' => 'available',  'size' => 12, 'floor' => 1, 'facilities' => ['WiFi', 'Kipas Angin', 'Lemari']],
            ['name' => 'Kamar A2', 'type' => 'Standard', 'price' => 800000,  'status' => 'occupied',   'size' => 12, 'floor' => 1, 'facilities' => ['WiFi', 'Kipas Angin', 'Lemari']],
            ['name' => 'Kamar B1', 'type' => 'Deluxe',   'price' => 1200000, 'status' => 'available',  'size' => 16, 'floor' => 2, 'facilities' => ['WiFi', 'AC', 'Lemari', 'Kamar Mandi Dalam']],
            ['name' => 'Kamar B2', 'type' => 'Deluxe',   'price' => 1200000, 'status' => 'available',  'size' => 16, 'floor' => 2, 'facilities' => ['WiFi', 'AC', 'Lemari', 'Kamar Mandi Dalam']],
            ['name' => 'Kamar C1', 'type' => 'VIP',      'price' => 1800000, 'status' => 'maintenance','size' => 20, 'floor' => 3, 'facilities' => ['WiFi', 'AC', 'TV', 'Kulkas', 'Kamar Mandi Dalam', 'Balkon']],
            ['name' => 'Kamar C2', 'type' => 'VIP',      'price' => 1800000, 'status' => 'available',  'size' => 20, 'floor' => 3, 'facilities' => ['WiFi', 'AC', 'TV', 'Kulkas', 'Kamar Mandi Dalam', 'Balkon']],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}