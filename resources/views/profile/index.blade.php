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
    <form action="{{ route('profile.edit') }}">
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
    <h1 class="text-2xl font-bold">Album & Foto</h1> 
    <div class="mt-4">
        <button id="albumButton" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow-md mr-4">Album Kamu</button>
        <button id="likedPhotoButton" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow-md">Foto yang disukai</button>
    </div>
  </div>

<!-- Display user albums by default -->
<div id="albumSection">
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


<div id="likedPhotoSection" class="hidden">
    <div class="grid grid-cols-3 gap-5">
    @foreach ($likedPhotos as $photo)
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

</div>




</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const albumButton = document.getElementById('albumButton');
      const likedPhotoButton = document.getElementById('likedPhotoButton');
      const albumSection = document.getElementById('albumSection');
      const likedPhotoSection = document.getElementById('likedPhotoSection');

      // Function to load user albums
      function loadUserAlbums() {
          // Make an AJAX request to fetch user albums
          // Replace the sample code below with your actual implementation
          fetch('/api/user/albums')
              .then(response => response.json())
              .then(data => {
                  // Clear existing content
                  albumSection.innerHTML = '';
                  // Display each album
                  data.forEach(album => {
                      albumSection.innerHTML += `<div>${album.name}</div>`;
                  });
              })
              .catch(error => console.error('Error loading albums:', error));
      }

      // Function to load liked photos
      function loadLikedPhotos() {
          // Make an AJAX request to fetch liked photos
          // Replace the sample code below with your actual implementation
          fetch('/api/user/liked-photos')
              .then(response => response.json())
              .then(data => {
                  // Clear existing content
                  likedPhotoSection.innerHTML = '';
                  // Display each liked photo
                  data.forEach(photo => {
                      likedPhotoSection.innerHTML += `<div>${photo.name}</div>`;
                  });
              })
              .catch(error => console.error('Error loading liked photos:', error));
      }

      // Load user albums by default
      loadUserAlbums();

      // Add event listener for album button click
      albumButton.addEventListener('click', function() {
          albumSection.classList.remove('hidden');
          likedPhotoSection.classList.add('hidden');
          loadUserAlbums();
      });

      // Add event listener for liked photo button click
      likedPhotoButton.addEventListener('click', function() {
          albumSection.classList.add('hidden');
          likedPhotoSection.classList.remove('hidden');
          loadLikedPhotos();
      });
  });
</script>

@endsection
