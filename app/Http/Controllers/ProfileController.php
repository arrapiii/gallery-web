<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\User;
use App\Models\Album;
use Laravel\Ui\Presets\Vue;
use Illuminate\Http\Request;
use App\Models\AktivitasUser;
use App\Exports\AktivitasUserExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index() 
    {
        $albums = auth()->user()->albums()->with('photos')->get();

         // Get all the photos owned by the authenticated user
        $userPhotos = auth()->user()->photos;

        // Count the total number of likes received by all user photos
        $totalLikes = $userPhotos->map(function ($photo) {
            return $photo->likes()->count();
        })->sum();
  
        return view('profile.index', ['albums' => $albums, 'totalLikes' => $totalLikes]);
    }

    public function viewProfile($userId)
    {
        // Find the user by their ID
        $user = User::findOrFail($userId);

        // Retrieve albums and other profile information for the user
        $albums = $user->albums()->with('photos')->get();

        $totalLikes = $user->photos->flatMap(function ($photo) {
            return $photo->likes;
        })->count();

        return view('profile.view', ['user' => $user, 'albums' => $albums, 'totalLikes' => $totalLikes]);
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Add more validation rules as needed
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the user's profile information
        $user->name = $request->name;
        $user->email = $request->email;
        // Update other fields as needed
        $user->save();

        // Redirect back to the profile page with a success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function export()
    {
        // Get the authenticated user's ID
        $userId = auth()->id();

        // Fetch the aktivitas data for the authenticated user
        $aktivitas = AktivitasUser::where('user_id', $userId)->get();

        // Generate the Excel export
        return Excel::download(new AktivitasUserExport($aktivitas), 'aktivitas_user.xlsx');
    }
}
