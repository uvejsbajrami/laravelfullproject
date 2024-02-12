<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:admin')->except(['store', 'destroy']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rooms.create',['hotels'=>Hotel::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'capacity' => 'required',
        ]);

        $room_number = $request->input('room_number');
        $hotel_id = $request->input('hotel_id');


        $lastRoomNumber = Room::where('HotelID', $hotel_id)->max('room_number') ?? 0;

        for ($i = $lastRoomNumber + 1; $i <= $lastRoomNumber + $room_number; $i++) {
            Room::create(['HotelID' => $hotel_id, 'capacity' => $request->capacity, 'room_number' => $i]);
        }

        return redirect()->route('hotels.index')->with('status', 'Rooms were created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hotel_id = $request->input('hotel_id');
        $checkoutDate = $request->input('checkoutdate');

        if (strtotime($checkoutDate) === strtotime(now()->toDateString())) {
            Room::where('HotelID', $hotel_id)
                ->whereIn('id', function ($query) use ($hotel_id, $checkoutDate) {
                    $query->select('RoomID')
                        ->from('bookings')
                        ->where('HotelID', $hotel_id)
                        ->where('CheckOutDate', $checkoutDate);
                })
                ->update(['status' => 0]);

                Booking::where('HotelID', $hotel_id)
                ->where(function ($query) use ($hotel_id, $checkoutDate) {
                    $query->where('CheckOutDate', '>=', $checkoutDate)
                        ->orWhere(function ($query) use ($hotel_id, $checkoutDate) {
                                $query->where('CheckInDate', '<=', $checkoutDate)
                                    ->where('CheckOutDate', '>=', $checkoutDate);
                            });
                })
                ->update(['status' => 0]);
        }

        return redirect()->route('bookings.index')->with('status', 'Room status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->back()->with('status', 'Product was deleted successfully.');
    }
}
