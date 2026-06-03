<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Room;

class HomeController extends Controller
{
    public function index()
    {
        $totalRooms     = Room::count();
        $availableRooms = Room::available()->count();

        return view('public.home', compact('totalRooms', 'availableRooms'));
    }
}