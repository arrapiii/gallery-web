<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Vue;

class ProfileController extends Controller
{
    public function index() 
    {
        return view('profile.index');
    }
}
