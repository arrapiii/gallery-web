<?php

namespace App\Http\Controllers;
use App\Models\Foto;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $fotos = Foto::all();

        return view('main.home', ['fotos' => $fotos]);
    }

 
}
