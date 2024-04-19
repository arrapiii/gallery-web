<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Album;
use App\Models\LikeFoto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imagePaths = Storage::files('public/images');
        $images = [];
    
        foreach ($imagePaths as $path) {
            // Convert the storage path to a public URL
            $url = asset(str_replace('public', 'storage', $path));
            $images[] = $url;
        }
    
        return view('gallery', compact('images'));
    }

    public function getLikeCount($fotoId)
    {
        // Count the number of likes for the specified photo
        $likeCount = LikeFoto::where('foto_id', $fotoId)->count();

        // Return the like count as a JSON response
        return response()->json(['likeCount' => $likeCount]);
    }


    public function detail(Request $request)
    {
        // Retrieve the photo ID from the request
        $photoId = $request->input('foto_id');

        // Retrieve the specific photo data
        $foto = Foto::findOrFail($photoId);

        $likeCount = $this->getLikeCount($photoId);

        // Pass the photo data to the detail view
        return view('main.detail', compact('foto', 'likeCount'));
    }

     /**
     * Like the specified photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Foto  $foto
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, Foto $foto)
    {
        // Check if the user has already liked the photo
        $existingLike = LikeFoto::where('foto_id', $foto->id)
            ->where('user_id', Auth::id())
            ->first();

        // If the user has already liked the photo, do nothing
        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this photo.']);
        }

        // Create a new like record
        $like = new LikeFoto();
        $like->foto_id = $foto->id;
        $like->user_id = Auth::id();
        $like->save();

        return response()->json(['message' => 'Photo liked successfully.']);
    }

    /**
     * Unlike the specified photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Foto  $foto
     * @return \Illuminate\Http\Response
     */
    public function unlike(Request $request, Foto $foto)
    {
        // Find the like record
        $like = LikeFoto::where('foto_id', $foto->id)
            ->where('user_id', Auth::id())
            ->first();

        // If the like record exists, delete it
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Photo unliked successfully.']);
        }

        // If the like record does not exist, do nothing
        return response()->json(['message' => 'You have not liked this photo.']);
    }

    public function checkLike(Foto $foto)
    {
        // Check if the authenticated user has liked the photo
        $liked = $foto->likes()->where('user_id', Auth::id())->exists();

        // Return a JSON response indicating whether the user has liked the photo
        return response()->json(['liked' => $liked]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $albums = Album::where('user_id', auth()->id())->get();

        return view('create.create', compact('albums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'album_id' => 'required|array',
            'judul_foto' => 'required',
            'deskripsi_foto' => 'required',
            'file' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Generate a unique filename
        $filename = auth()->user()->name . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Store the file in the public directory with the generated filename
        $path = $file->storeAs('images', $filename, 'public');

        foreach ($validatedData['album_id'] as $albumId) {
            // Create a new Foto model instance
            $foto = new Foto();
            $foto->judul_foto = $validatedData['judul_foto'];
            $foto->deskripsi_foto = $validatedData['deskripsi_foto'];
            $foto->lokasi_file = $path;
            $foto->user_id = auth()->id();
            $foto->album_id = $albumId;
            $foto->save();
        }

        Session::flash('success', 'Photo uploaded successfully.');

        return redirect()->back();
    }

    public function comments(Foto $foto)
    {
        $comments = $foto->comments()->with('user')->get();
        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Foto $foto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $foto = Foto::findOrFail($id);
        $albums = Album::all();
    
        return view('edit.foto', compact('foto', 'albums'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Foto $foto)
{
    // Validate the form data
    $validator = Validator::make($request->all(), [
        'album_id' => 'nullable|exists:albums,id',
        'judul_foto' => 'required',
        'deskripsi_foto' => 'required',
    ]);

    // If validation fails, return the error response
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Update the photo data
    $foto->update([
        'album_id' => $request->input('album_id'), // Ensure album_id is passed as a single integer value
        'judul_foto' => $request->input('judul_foto'),
        'deskripsi_foto' => $request->input('deskripsi_foto'),
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Photo updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Foto $foto)
    {
        $foto->delete();

        if ($foto->delete()) {
            // Add success message to session only if the photo was deleted successfully
            session()->flash('success', 'Photo deleted successfully.');
        }

        // Redirect back to the home page
        return redirect()->route('home');
    }
}
