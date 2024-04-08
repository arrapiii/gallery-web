@extends('layouts.master')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mt-20">
  <div class="flex flex-col items-center">
    @auth
      <div class="mb-8">
        @if(auth()->user()->profile_image_url)
          <img src="{{ auth()->user()->profile_image_url }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover">
        @else
          <div class="w-32 h-32 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 text-4xl font-semibold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
        @endif
      </div>
    @endauth

    <div class="flex flex-col items-center justify-center">
      @auth
        <h2 class="text-4xl font-semibold mb-4">{{ auth()->user()->name }}</h2>
      @endauth

      <div class="flex justify-center">
        <div class="flex flex-col items-center mx-4">
            <span class="text-lg font-bold">10</span>
            <span class="text-gray-500">Followers</span>
        </div>
        <div class="border-l border-gray-300 h-8"></div>
        <div class="flex flex-col items-center mx-4">
            <span class="text-lg font-bold">150</span>
            <span class="text-gray-500">Following</span>
        </div>
        <div class="border-l border-gray-300 h-8"></div>
        <div class="flex flex-col items-center mx-8">
            <span class="text-lg font-bold">500</span>
            <span class="text-gray-500">Likes</span>
        </div>
    </div>
    </div>
  </div>

  <div class="text-center mt-12 mb-4">
    <h1 class="text-4xl font-bold">Album Kamu</h1> 
  </div>

  <div class="grid grid-cols-3 gap-4 mt-8">
    <a href="{{ route('album') }}">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="{{ asset('images/76a39b47-75b4-4804-a717-75c71038f2ba.jpeg') }}" alt="Grid item 1" class="w-full h-48 object-cover">
            <div class="p-4">
              <h2 class="text-lg font-semibold">Nama Album 1</h2>
              <p class="text-gray-500 text-sm">Description for Nama Album 1</p>
            </div>
        </div>
    </a>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ asset('images/Xd.jpeg') }}" alt="Nama Album 2" class="w-full h-48 object-cover">
      <div class="p-4">
        <h2 class="text-lg font-semibold">Nama Album 2</h2>
        <p class="text-gray-500 text-sm">Description for Nama Album 2</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ asset('images/76a39b47-75b4-4804-a717-75c71038f2ba.jpeg') }}" alt="Nama Album 2" class="w-full h-48 object-cover">
      <div class="p-4">
        <h2 class="text-lg font-semibold">Nama Album 2</h2>
        <p class="text-gray-500 text-sm">Description for Nama Album 2</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ asset('images/Xd.jpeg') }}" alt="Nama Album 2" class="w-full h-48 object-cover">
      <div class="p-4">
        <h2 class="text-lg font-semibold">Nama Album 2</h2>
        <p class="text-gray-500 text-sm">Description for Nama Album 2</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ asset('images/23168b5c-6334-49db-bbab-16b7a36f9608.jpeg') }}" alt="Nama Album 2" class="w-full h-48 object-cover">
      <div class="p-4">
        <h2 class="text-lg font-semibold">Nama Album 2</h2>
        <p class="text-gray-500 text-sm">Description for Nama Album 2</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ asset('images/75b89463-49fb-406c-88d9-08b05f7be570.jpeg') }}" alt="Nama Album 2" class="w-full h-48 object-cover">
      <div class="p-4">
        <h2 class="text-lg font-semibold">Nama Album 2</h2>
        <p class="text-gray-500 text-sm">Description for Nama Album 2</p>
      </div>
    </div>
    </div>
</div>

@endsection
