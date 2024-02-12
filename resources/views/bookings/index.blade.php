<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            <div class="container ">
                @if ($bookings)
                @foreach ($bookings as $booking)
                @if (auth()->user()->hasRole('admin') || (auth()->id() == $booking->UserID ))
                    <div class="row ">
                        <div class="card mb-3 " style="width:100%">
                            <div class="row g-0">
                            <div class="col-md-4">
                                @foreach (App\Models\Hotel::find($booking->HotelID)->images->take(1) as $image)
                                    <img src="{{ asset('storage/products/'.$image->name) }}" class="card-img-top h-100" alt="{{ $image->name }}" style="object-fit: cover;">
                                @endforeach
                            </div>
                            <div class="col-md-8 ">
                                <div class="card-body">
                                    <h5 class="card-title">{{ App\Models\Hotel::find($booking->HotelID)->name }}</h5>
                                    @php
                                        $checkInDate = \Carbon\Carbon::parse($booking->CheckInDate);
                                        $checkOutDate = \Carbon\Carbon::parse($booking->CheckOutDate);
                                        $dayCount = now()->diffInDays($checkOutDate);
                                    @endphp
                                    <p class="card-text">Ordered From: {{ App\Models\User::find($booking->UserID)->name }}</p>
                                    <p class="card-text"> @if($dayCount > 0){{ $dayCount }} @if($dayCount >1) Days Left  @else Day Left @endif @else 0 Days Left @endif<i class="bi bi-calendar"></i></p>
                                    <p class="card-text">Room Number: {{ App\Models\Room::find($booking->RoomID)->room_number }}</p>
                                    <p class="card-text">Room Capacity: {{ App\Models\Room::find($booking->RoomID)->capacity }} <i class="bi bi-people-fill"></i></p>
                                    <p>@if($dayCount != 0) Total:  {{ number_format(App\Models\Hotel::find($booking->HotelID)->price * $booking->days, 2, ".", "") }}&euro; @else <button disabled class="btn btn-sm btn-outline-danger">Time Expired</button> @endif </p>
                                    <p class="card-text"><small class="text-body-secondary">{{ $booking->created_at }}</small></p>
                                <div class="d-flex justify-content-between" style="width: 210px">
                                    <a href="{{ route('hotels.show',['hotel'=>$booking->HotelID]) }}" class="btn btn-sm btn-outline-success">Check Hotel</a>
                                    @role('admin')
                                    <form method="POST" action="{{ route('rooms.update', $booking->RoomID) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" id="checkoutdate" name="checkoutdate" value="{{ $booking->CheckOutDate }}" />
                                        <input type="hidden" id="hotel_id" name="hotel_id" value="{{ $booking->HotelID }}" />
                                        @if ($booking->status == 1)
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Clear Room</button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-danger" disabled >Room Cleared</button>
                                        @endif
                                    </form>
                                    @endrole
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @endif
</div>


    </div>
</div>
</x-app-layout>
