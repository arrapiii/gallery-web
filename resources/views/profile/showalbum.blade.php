@extends('layouts.master')

@section('content')
<div class="w-full mx-auto px-12">
    <div class="bg-white rounded-lg shadow-md p-5 mt-40">
        @if(auth()->user() && auth()->user()->id === $album->user_id)
                <form action="{{ route('export.album', $album->id) }}" method="post" class="mt-2">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow-md">
                        Export
                    </button>
                </form>
            @endif
        <div class="text-center mb-4"> <!-- Center the album name text -->
            <h2 class="text-3xl font-semibold">{{ $album->nama_album }}</h2>
        </div>
        @if ($fotos->isEmpty())
            <p class="text-center text-gray-500">This album does not have any photos.</p>
        @else
            <div class="grid grid-cols-3 gap-5">
                @foreach ($fotos as $photo)
                <div class="relative group h-64"> <!-- Set a fixed height for the container -->
                    <a href="{{ route('detail', ['foto_id' => $photo->id]) }}">
                        <img src="{{ asset('storage/' . $photo->lokasi_file) }}" alt="" class="w-full h-full object-cover rounded-lg" /> <!-- Use object-cover to fit the image within the container -->
                        <div class="flex justify-center items-center opacity-0 bg-gradient-to-t from-gray-800 via-gray-800 to-opacity-30 group-hover:opacity-50 absolute top-0 left-0 h-full w-full rounded-lg"></div>
                        <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center opacity-0 hover:opacity-100">
                            <div class="flex-row text-center">
                                <h1 class="text-gray-50 font-bold text-lg">{{ $photo->judul_foto }}</h1>
                                <p class="text-gray-200 font-medium text-sm break-words max-w-md">{{ $photo->deskripsi_foto }}</p>
                                <small class="text-xs font-light text-gray-300">Foto by {{ $photo->user->name }}</small>
                                <div class="absolute bottom-0 right-0 bg-gray-800 px-2 py-1 rounded-bl-lg opacity-80">
                                    <p class="text-xs text-gray-200">{{ $photo->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Back Button -->
<div class="fixed top-24 left-4">
    <a href="javascript:history.back()" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-full shadow-md hover:bg-blue-600">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </a>
</div>
@endsection
