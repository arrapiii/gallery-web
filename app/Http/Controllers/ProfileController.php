<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Album;
use Laravel\Ui\Presets\Vue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
