<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Exports\AlbumExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('profile.album');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create.createalbum');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
     {
        $validated = $request->validate([
            'nama_album' => 'required',
            'deskripsi' => 'required',
        ]);

        Album::create([
            'nama_album' => $validated['nama_album'],
            'deskripsi' => $validated['deskripsi'],
            'user_id' => auth()->id(),
        ]);

        Session::flash('success', 'Album created successfully.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $album = Album::findOrFail($id);
        $fotos = $album->photos()->get();

        return view('profile.showalbum', compact('album', 'fotos'));
    }

    public function export(Album $album)
    {
        return Excel::download(new AlbumExport($album->id), 'album_' . $album->nama_album . '.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        //
    }
}
