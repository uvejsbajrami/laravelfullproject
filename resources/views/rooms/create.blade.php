<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <a href="{{ route('hotels.index') }}" class="btn btn-sm btn-outline-primary mb-4">
                    <i class="bi bi-arrow-left"></i> Hotels
                </a>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Session::has('status'))
                    <div class="alert alert-info mb-4">
                        {{ Session::get('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('rooms.store') }}">
                    @csrf
                    <label  class="form-label">Select the number of room to create</label>
                     <input type="number" class="form-control mb-2" name="room_number"  placeholder="select number" style="max-width: 300px;" min="1" value="1">
                     <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Select the hotel id" style="max-width: 300px;" name="hotel_id">
                    <datalist id="datalistOptions"  >
                        @if ($hotels->count() > 0)
                            @foreach($hotels as $hotel)
                                <option name="hotel_id" value="{{$hotel->id}}">{{ $hotel->name }}
                            @endforeach
                        @endif
                    </datalist>
                    <input type="text" name="capacity" placeholder="Capacity of room" class="form-control mb-3 mt-2" value="{{ old('capacity') }}" />
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
