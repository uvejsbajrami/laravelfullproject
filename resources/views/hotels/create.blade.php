<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Hotel') }}
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


                <form method="POST" action="{{ route('hotels.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="text" name="name" placeholder="Name" class="form-control mb-3" value="{{ old('name') }}" />
                    <input type="text" name="address" placeholder="Address" class="form-control mb-3" value="{{ old('address') }}" />
                    <input type="text" name="contact" placeholder="Contact" class="form-control mb-3" value="{{ old('contact') }}" />
                    <input type="text" name="price" placeholder="Price" class="form-control mb-3" value="{{ old('price') }}" />
                    <input type="file" name="image[]" class="form-control mb-3" multiple />
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
