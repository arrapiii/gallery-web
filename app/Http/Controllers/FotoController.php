<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Album;
use App\Models\LikeFoto;
use App\Models\LaporanFoto;
use Illuminate\Support\Str;
use App\Models\JenisLaporan;
use Illuminate\Http\Request;
use App\Models\AktivitasUser;
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

        $jenisLaporans = JenisLaporan::all();
        

        // Pass the photo data to the detail view
        return view('main.detail', compact('foto', 'likeCount', 'jenisLaporans'));
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

        // Get the name of the user whose post is being liked or unliked
        $userWhosePostIsLikedOrDisliked = $foto->user->name;

        $lokasiFoto = $foto->lokasi_file;

        // If the user has already liked the photo, do nothing
        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this photo.']);

            // Log the activity for unliking
            AktivitasUser::create([
                'user_id' => Auth::id(),
                'aktivitas' => "Unlike postingan milik $userWhosePostIsLikedOrDisliked",
                'foto' => $lokasiFoto,
            ]);
        }

        // Create a new like record
        $like = new LikeFoto();
        $like->foto_id = $foto->id;
        $like->user_id = Auth::id();
        $like->save();

        // Log the activity for unliking
            $aktivitas = $existingLike ? 'Membatalkan menyukai' : 'Menyukai';
            AktivitasUser::create([
                'user_id' => Auth::id(),
                'aktivitas' => "$aktivitas postingan milik $userWhosePostIsLikedOrDisliked",
                'foto' => $lokasiFoto,
            ]);


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

            // Get the name of the user whose post is being unliked
            $userWhosePostIsLikedOrDisliked = $foto->user->name;
            $lokasiFoto = $foto->lokasi_file;

            // Log the activity for unliking
            AktivitasUser::create([
                'user_id' => Auth::id(),
                'aktivitas' => "Unlike postingan milik $userWhosePostIsLikedOrDisliked",
                'foto' => $lokasiFoto,
            ]);

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

    public function report(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'foto_id' => 'required|exists:fotos,id',
            'jenis_laporan_id' => 'required|exists:jenis_laporans,id',
        ]);

        // Check if the user has already reported this photo with a pending status
        $existingReport = LaporanFoto::where('foto_id', $validatedData['foto_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {

            return response()->json(['message' => 'You have already reported this photo with status pending'], 400);
        }

        // Create a new report instance
        $report = new LaporanFoto();
        $report->foto_id = $validatedData['foto_id'];
        $report->user_id = auth()->id();
        $report->jenis_laporan_id = $validatedData['jenis_laporan_id'];
        $report->status = 'pending'; // Set the status to pending
        $report->save();

        // Optionally, you can return a response indicating success
        return response()->json(['message' => 'Report submitted successfully'], 200);
    }

    public function comments(Foto $foto)
    {
        $comments = $foto->comments()->with('user')->get();
        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    public function search(Request $request)
    {
        // Retrieve the search query and album name from the request
        $query = $request->query('query');
        $namaAlbum = $request->query('nama_album');
    
        $results = Foto::query()
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('judul_foto', 'like', "%{$query}%")
                        ->orWhereHas('album', function ($albumQuery) use ($query) {
                            $albumQuery->where('nama_album', 'like', "%{$query}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($query) {
                            $userQuery->whereHas('photos', function ($fotoQuery) use ($query) {
                                $fotoQuery->where('name', 'like', "%{$query}%");
                            });
                        });
        })
        ->get();
    
        // Log the search activity
        AktivitasUser::create([
            'user_id' => Auth::id(),
            'aktivitas' => "Melakukan Pencarian: query='{$query}', nama_album='{$namaAlbum}'",
        ]);
    
        // Pass the search results to the view
        return view('main.searchresult', ['results' => $results]);
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
