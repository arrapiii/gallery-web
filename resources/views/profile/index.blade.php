@extends('layouts.master')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mt-20">
  <div class="flex justify-end mx-5 mb-4">
    <form action="{{ route('export.activity.logs') }}" method="POST">
      @csrf
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-6">
        Report Log Activity
    </button>
    </form>
    <form action="{{ route('profile.edit') }}" method="POST">
      @csrf
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Edit Profil
    </button>
    </form>
  </div>

  <div class="flex flex-col items-center">
    @auth
      <div class="mb-8">
        @if(auth()->user()->avatar)
          <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Image" class="w-48 h-48 rounded-full object-cover">
        @else
          <div class="w-32 h-32 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 text-4xl font-semibold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
        @endif
      </div>
    @endauth

    <div class="flex flex-col items-center justify-center">
      @auth
        <h2 class="text-3xl font-semibold mb-4">{{ auth()->user()->name }}</h2>
        @endauth
      <div class="flex justify-center">
        <div class="flex flex-col items-center mx-8">
            <span class="text-lg font-bold">{{ auth()->user()->photos()->count() }}</span>
            <span class="text-gray-500">Post</span>
        </div>
        <div class="border-l border-gray-300 h-8"></div>
        <div class="flex flex-col items-center mx-4">
            <span class="text-lg font-bold">{{ auth()->user()->albums()->count() }}</span>
            <span class="text-gray-500">Album</span>
        </div>
        <div class="border-l border-gray-300 h-8"></div>
        <div class="flex flex-col items-center mx-8">
            <span class="text-lg font-bold">{{ $totalLikes }}</span>
            <span class="text-gray-500">Likes</span>
        </div>
    </div>
    </div>
  </div>

  <div class="text-center mt-12 mb-4">
    <h1 class="text-2xl font-bold">Album Kamu</h1> 
  </div>

  <div class="grid grid-cols-3 gap-4 mt-8">
    @if($albums->isEmpty())
        <div class="col-span-3 flex justify-center">
            <p>You don't have any albums.</p>
        </div>
    @else
        @foreach($albums as $index => $album)
            @php
                $coverPhoto = $album->photos()->latest()->first();
                $photoCount = $album->photos()->count();
            @endphp
            <a href="{{ route('album.show', $album->id) }}" class="relative">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $coverPhoto ? asset('storage/' . $coverPhoto->lokasi_file) : asset('images/no_photo.jpg') }}" alt="Album Cover" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold">{{ $album->nama_album }}</h2>
                        <p class="text-gray-500 text-sm">{{ $album->deskripsi }}</p>
                    </div>
                </div>
                <!-- Display photo count -->
                <div class="absolute bottom-2 right-2 bg-white rounded-md shadow-md px-2 py-1 text-xs text-gray-500">{{ $photoCount }} photos</div>
            </a>
            <!-- Break the loop after the first album -->
            @if($index == 0)
                @break
            @endif
        @endforeach
        <!-- Display remaining albums -->
        @foreach($albums->slice(1) as $album)
            @php
                $coverPhoto = $album->photos()->latest()->first();
                $photoCount = $album->photos()->count();
            @endphp
            <a href="{{ route('album.show', $album->id) }}" class="relative">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $coverPhoto ? asset('storage/' . $coverPhoto->lokasi_file) : asset('images/no_photo.jpg') }}" alt="Album Cover" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold">{{ $album->nama_album }}</h2>
                        <p class="text-gray-500 text-sm">{{ $album->deskripsi }}</p>
                    </div>
                </div>
                <!-- Display photo count -->
                <div class="absolute bottom-2 right-2 bg-white rounded-md shadow-md px-2 py-1 text-xs text-gray-500">{{ $photoCount }} photos</div>
            </a>
        @endforeach
    @endif
</div>



</div>

@endsection
