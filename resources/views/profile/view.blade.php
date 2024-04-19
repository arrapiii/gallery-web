@extends('layouts.master')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mt-20">
  <div class="flex flex-col items-center">
    <div class="mb-8">
      @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Image" class="w-48 h-48 rounded-full object-cover">
      @else
        <div class="w-32 h-32 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 text-4xl font-semibold">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
      @endif
    </div>

    <div class="flex flex-col items-center justify-center">
      <h2 class="text-3xl font-semibold mb-4">{{ $user->name }}</h2>

      <div class="flex justify-center">
        <div class="flex flex-col items-center mx-8">
            <span class="text-lg font-bold">{{ $user->photos()->count() }}</span>
            <span class="text-gray-500">Post</span>
        </div>
        <div class="border-l border-gray-300 h-8"></div>
        <div class="flex flex-col items-center mx-4">
            <span class="text-lg font-bold">{{ $user->albums()->count() }}</span>
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
    <h1 class="text-2xl font-bold">Album {{ $user->name }}</h1> 
  </div>

  <div class="grid grid-cols-3 gap-4 mt-8">
    @if($albums->isEmpty())
      <div class="col-span-3 flex justify-center">
        <p>{{ $user->name }} doesn't have an album.</p>
      </div>
    @else
        @foreach($albums as $album)
            <a href="{{ route('album.show', $album->id) }}">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @php
                        $coverPhoto = $album->photos()->latest()->first();
                    @endphp
                    <img src="{{ $coverPhoto ? asset('storage/' . $coverPhoto->lokasi_file) : asset('images/no_photo.jpg') }}" alt="Album Cover" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold">{{ $album->nama_album }}</h2>
                        <p class="text-gray-500 text-sm">{{ $album->deskripsi }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    @endif
  </div>

</div>

@endsection
