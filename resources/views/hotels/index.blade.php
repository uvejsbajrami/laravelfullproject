<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Hotels') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            @role('admin')
        <div  class="d-flex">
            <a href="{{ route('hotels.create') }}" class="m-3 btn btn-sm btn-outline-primary">Create Hotel</a>
            <a href="{{ route('rooms.create') }}" class="m-3 btn btn-sm btn-outline-primary">Create Rooms</a>
                @endrole
                @if ($hotels->count() > 0)
                <form style="max-width: 340px" action="{{ route('hotels.search') }}" >
                    <input class="from-control rounded mt-2" type="text" name="search" placeholder="Search Hotels..."  />
                    <button class="btn  btn-outline-success mb-1" type="submit">Go</button>
                </form>
            @endif
        </div>
         @if ($hotels->count() > 0)
            <div class="container">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach ($hotels as $hotel)
                        <div class="col">
                            <div class="card h-100">
                                @if ($hotel->images->isNotEmpty())
                                    @foreach ($hotel->images->take(1) as $image)
                                        <img src="{{ asset('storage/products/'.$image->name) }}" class="card-img-top h-100" alt="{{ $image->name }}" style="object-fit: cover;">
                                    @endforeach
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $hotel->name }}</h5>
                                    <p class="card-text">{{ $hotel->address }}</p>
                                    <p class="card-text">{{ $hotel->contact }}</p>
                                    <p class="card-text">{{ number_format($hotel->price, 2, ".", "") }} &euro; perDay</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('hotels.show', $hotel->id) }}" class="btn btn-sm btn-outline-primary">Check more information</a>
                                        @role('admin')
                                        <a href="{{ route('hotels.edit', ['hotel' => $hotel->id]) }}" class="btn btn-sm btn-outline-secondary">
                                             <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('hotels.destroy', ['hotel' => $hotel->id]) }}" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
