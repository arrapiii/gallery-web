@extends('layouts.master')

@section('content')

<div id="photoContainer" class="w-full max-w-8xl p-5 pb-10 mx-auto mb-10 gap-5 columns-3 space-y-5 mt-16">
    @foreach ($results as $foto)
    <!-- Display each search result -->
    <div class="relative group">
        <a href="{{ route('detail', ['foto_id' => $foto->id]) }}">
            <img src="{{ asset('storage/' . $foto->lokasi_file) }}" alt="" class="w-full h-auto rounded-lg" />
            <div
                class="flex justify-center items-center opacity-0 bg-gradient-to-t from-gray-800 via-gray-800 to-opacity-30 group-hover:opacity-50 absolute top-0 left-0 h-full w-full rounded-lg">
            </div>
            <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center opacity-0 hover:opacity-100">
                <div class="flex-row text-center">
                    <h1 class="text-gray-50 font-bold text-lg">{{ $foto->judul_foto }}</h1>
                    <p class="text-gray-200 font-medium text-sm break-words max-w-md">{{ $foto->deskripsi_foto }}</p>
                    <small class="text-xs font-light text-gray-300">Foto by {{ $foto->user->name }}</small>
                    <div class="absolute bottom-0 right-0 bg-gray-800 px-2 py-1 rounded-bl-lg opacity-80">
                        <p class="text-xs text-gray-200">{{ $foto->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

@endsection