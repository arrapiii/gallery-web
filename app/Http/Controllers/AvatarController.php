<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('avatar', compact('user'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Only allow jpeg, png, jpg files with maximum size of 2MB
        ]);

        // Get the user
        $user = Auth::user();

        // Check if the user already has an avatar
        if ($user->avatar) {
            // Delete the old avatar file if it exists
            Storage::delete($user->avatar);
        }

        // Store the new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        // Update the user's avatar path
        $user->avatar = $avatarPath;
        $user->save();

        // Flash success message
        session()->flash('success', 'Avatar uploaded successfully');

        // Redirect back
        return back();
    }

    public function remove()
    {
        // Get the user
        $user = Auth::user();

        // Check if the user has an avatar
        if ($user->avatar) {
            // Delete the avatar file
            Storage::delete($user->avatar);

            // Clear the user's avatar path
            $user->avatar = null;
            $user->save();
        }

        // Flash success message
        session()->flash('success', 'Avatar removed successfully');

        // Redirect back
        return back();
    }
}
