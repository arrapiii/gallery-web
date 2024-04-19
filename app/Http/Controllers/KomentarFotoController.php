<?php

namespace App\Http\Controllers;

use App\Models\KomentarFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class KomentarFotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the comment data
        $validatedData = $request->validate([
            'foto_id' => 'required|exists:fotos,id',
            'isi_komentar' => 'required',
        ]);
    
        // Create a new comment
        $comment = new KomentarFoto();
        $comment->foto_id = $validatedData['foto_id'];
        $comment->user_id = auth()->id();
        $comment->isi_komentar = $validatedData['isi_komentar'];
        $comment->save();
    
        $comment->created_at_formatted = Carbon::parse($comment->created_at)->diffForHumans();
        // Return the comment data as JSON
        return response()->json([
            'success' => true,
            'comment' => $comment,
            'created_at_formatted' => $comment->created_at_formatted
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KomentarFoto $komentarFoto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KomentarFoto $komentarFoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KomentarFoto $komentarFoto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KomentarFoto $komentarFoto)
    {
        //
    }
}
