<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $bookings = DB::table('bookings')->orderByDesc('created_at')->get();
        }else{
            $userId = Auth::id();
            $bookings = DB::table('bookings')
                ->where('UserID',$userId)
                ->orderByDesc('created_at')
                ->get();
        }


    //    $bookings = Booking::when(auth()->user()->hasRole('admin'), function ($query) {
    //     return $query->orderByDesc('created_at')->get();
    // }, function ($query) {
    //     return $query->where('UserID', auth()->id())->orderByDesc('created_at')->get();
    // });

        return view('bookings.index', ['bookings' => $bookings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {


        $checkin = Carbon::parse($request->input('checkin'));
        $checkout = Carbon::parse($request->input('checkout'));
        $room_id = $request->input('room_id');


        $existingBooking = Booking::where('RoomID', $room_id)
            ->where('status', '1')
            ->where(function ($query) use ($checkin, $checkout) {
                $query->whereBetween('CheckInDate', [$checkin, $checkout])
                    ->orWhereBetween('CheckOutDate', [$checkin, $checkout])
                    ->orWhere(function ($query) use ($checkin, $checkout) {
                        $query->where('CheckInDate', '<=', $checkin)
                            ->where('CheckOutDate', '>=', $checkout);
                    });
            })
            ->first();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['room_id' => 'The room is not available for the selected dates.']);
        }

        $data = $request->all();
        $data['UserID'] = Auth::id();
        $data['HotelID'] = $request->input('hotel_id');
        $data['RoomID'] = $room_id;
        $data['CheckInDate'] = $checkin;
        $data['CheckOutDate'] = $checkout;


        $dayCount = $checkin->diffInDays($checkout);

        $data['days'] = $dayCount;
        $booking = Booking::create($data);
        $booking->update(['status' => '1']);

        return redirect()->back()->with('status', 'Hotel was booked successfully!');
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->back()->with('status', 'Product was deleted successfully.');
    }
}
