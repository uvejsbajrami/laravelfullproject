<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-image"  >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
            <div class="mb-4">
                <h3 class=""> Welcome to bookings.com</h3>
            </div>
            <div class="py-3">
                <a href="{{ route('hotels.index') }}" class="btn btn-sm btn-outline-primary">Check Our Hotels</a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
