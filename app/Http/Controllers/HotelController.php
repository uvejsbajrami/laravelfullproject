<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\Image;
use App\Models\Booking;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:admin|customer')->only(['index', 'show', 'search']);
    //     $this->middleware('role:admin')->only(['destroy']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotels = Hotel::with('images')->get();

        return view('hotels.index', ['hotels' => $hotels]);
    }

    public function search(Request $request){

        $search = $request->input('search');

        $results = Hotel::where('address', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%')
        ->orderBy('price', 'asc')
        ->with('images')
        ->get();

        return view('hotels.search', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'price' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif'
        ]);

        $data = $request->except('_token');

        $uploadedImages = [];

    if ($request->hasfile('image')) {
        foreach ($request->file('image') as $file) {
            $image = $file->getClientOriginalName();
            $name = pathinfo($image, PATHINFO_FILENAME);
            $ext = pathinfo($image, PATHINFO_EXTENSION);

            $filename = time() . "_" . $name . "." . $ext;

            Storage::putFileAs('public/products/', $file, $filename);

            $uploadedImages[] = $filename;
        }

        $data['image'] = $uploadedImages;
    }


        $hotel = Hotel::create($data);
        $hotelId = $hotel->id;
        foreach ($uploadedImages as $filename) {
        Image::create(['HotelID' => $hotelId, 'name' => $filename]);
    }

        return redirect()->route('hotels.index')->with('status', 'Product was created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hotel = Hotel::find($id);
        $images = Image::where('HotelID', $id)->get();
        $comments = Comment::where('HotelID', $id)->get();
        $rooms = Room::where('HotelID', $id)->where('status', '0')->get();

        $bookings = Booking::where('HotelID',$id)->where('status','1')->get();


        return view('hotels.show', ['hotel' => $hotel, 'images' => $images,'comments'=>$comments,'rooms'=>$rooms,'bookings'=>$bookings]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         return view('hotels.edit', ['hotels' => Hotel::findOrFail($id),'images'=>Image::where('HotelID', $id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'price' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif'
        ]);

        $data = $request->except('_token');

        $hotel = Hotel::find($id);
        $hotel->update($data);

        $uploadedImages = [];

        if ($request->hasfile('image')) {
            foreach ($request->file('image') as $file) {
                $image = $file->getClientOriginalName();
                $name = pathinfo($image, PATHINFO_FILENAME);
                $ext = pathinfo($image, PATHINFO_EXTENSION);

                $filename = time() . "_" . $name . "." . $ext;

                Storage::putFileAs('public/products/', $file, $filename);

                $uploadedImages[] = $filename;
            }

            $hotel->images()->delete();
            foreach ($uploadedImages as $filename) {
                $hotel->images()->create(['name' => $filename]);
            }
        }

        return redirect()->route('hotels.index')->with('status', 'Product was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        return redirect()->back()->with('status', 'Product was deleted successfully.');
    }

     public function addRating(Request $request, $hotelId)
    {
        // Validate the request if needed

        $ratings = $request->input('rating');
        $user = auth()->user();
        $hotel = Hotel::findOrFail($hotelId);

        $user->hotels()->syncWithoutDetaching([$hotelId => ['rating' => $ratings]]);

        return redirect()->back()->with('status', 'Rating updated successfully.');

    }
}
