<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Show') }}
    </h2>
    <style>
     .scroll-container {
        max-height: 300px;
        overflow-y: auto;
    }

    .scroll-container::-webkit-scrollbar {
        width: 0.5em;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background-color: darkgrey;
    }
    </style>
    <!-- Add this line in the head section of your HTML file -->

</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            <a href="{{ route('hotels.index') }}" class="btn btn-sm btn-outline-primary m-3"><i class="bi bi-arrow-left"></i> Go back</a>
            @if ($images->count() > 0)
                  <div class="image-gallery d-flex">
                    <div id="carouselExampleRide" class="carousel slide" data-bs-ride="true" style="max-width: 500px;">
                        <div class="carousel-inner">
                            @foreach ($images as $image)
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/products/'.$image->name) }}" class="d-block w-100 rounded" alt="{{ $image->name }}">
                                </div>
                            @endforeach
                        </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="container ms-2">
                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingform">
                        @csrf
                        <input type="hidden" id="hotel_id" name="hotel_id" value="{{ $hotel->id }}"/>

                        <select name="room_id" id="room_id" class="form-select mb-3" style="max-width: 300px">
                            <option value="">Select Room</option>
                            @if($rooms->count() > 0)
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" data-capacity="{{ $room->capacity }}">Room: {{ $room->room_number }}  <span>Capacity: {{ $room->capacity }}</span></option>
                                @endforeach
                                @else
                                <option disabled >No rooms available </option>
                            @endif
                        </select>
                            <p id="capacityDisplay"></p>
                            <label for="checkin" class="label-control ms-2">Check In</label>
                            <input type="date" id="checkin"  class="form-control" name="checkin" style="max-width: 300px"/>
                            <label for="checkout" class="label-control ms-2">Check Out</label>
                            <input type="date" id="checkout" class="form-control" name="checkout" style="max-width: 300px"/>

                            <button type="submit" class="btn btn-sm btn-outline-success mt-3 ms-1">Book Hotel</button>
                            @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endif
         @if ($hotel)
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>#</th>
                        <td>{{ $hotel->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $hotel->name }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $hotel->address }}</td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td>{{ $hotel->contact }}</td>
                    </tr>
                    <tr>
                        <th>Price per Day</th>
                        <td>{{ number_format($hotel->price, 2, ".", "") }} &euro;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

   @endif
@php
    $user = App\Models\User::find(Auth::user()->id);
    $hotel = App\Models\Hotel::find($hotel->id);

    $ratingPivot = $user->hotels()->wherePivot('hotel_id', $hotel->id)->first();

    $rating = $ratingPivot ? $ratingPivot->pivot->rating : null;


    $pivots = App\Models\hotel_user::where('hotel_id', $hotel->id)->get();

    $averageRating = $pivots->avg('rating');
    $averageRating = round($averageRating, 2);
@endphp



<div class="reviews ms-2">
    <p>Average Reviews: {{ $averageRating }}</p>
    <form method="POST" action="{{ route('addRating', ['hotelId' => $hotel->id]) }}" id="reviewForm">
        @csrf
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $rating)
                <button type="button" class="btn btn-link star-btn" data-value="{{ $i }}">
                    <i class="bi bi-star-fill" style="color: rgb(188, 188, 57);"></i>
                </button>
            @else
                <button type="button" class="btn btn-link star-btn" data-value="{{ $i }}">
                    <i class="bi bi-star" style="color: rgb(188, 188, 57);"></i>
                </button>
            @endif
        @endfor
        <input type="hidden" name="rating" id="ratingInput" value="{{ $rating }}">
    </form>
</div>

        <p>Comments:</p>
       <div class="my-3 scroll-container col-4" >
            @foreach ($comments as $comment)
               <div class="d-flex">
                    <p class="bg-secondary rounded p-1" style="max-width: 400px;color:white;" >{{ App\Models\User::find($comment->UserID)->name }}: {{ $comment->comment }}
                        @role('admin')
                    <form method="POST" action="{{ route('comments.destroy',['comment'=> $comment->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endrole
                </p>
               </div>
            @endforeach
       </div>
       @if($hotel)
        <form method="POST" action="{{ route('comments.store') }}">
            @csrf
            <input type="hidden" name="HotelID" value="{{ $hotel->id }}">
            <textarea name="comment" placeholder="Your comment" class="form-control mb-3" style="max-width: 400px"></textarea>
            <button type="submit" class="btn btn-sm btn-outline-success">Submit</button>
        </form>
        @endif
        </div>
    </div>
</div>
<script>
    document.getElementById('room_id').addEventListener('change', function() {
        var selectedRoomId = this.value;
        var selectedRoom = document.querySelector('option[value="' + selectedRoomId + '"]');

        if (selectedRoom) {
            var capacity = selectedRoom.getAttribute('data-capacity');
            document.getElementById('capacityDisplay').innerText = 'Room Capacity: ' + capacity;
        } else {
            document.getElementById('capacityDisplay').innerText = '';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('ratingInput');

        starButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;


                document.getElementById('reviewForm').submit();
            });
        });
    });

</script>
</x-app-layout>
