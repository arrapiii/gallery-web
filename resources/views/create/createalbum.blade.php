@extends('layouts.master')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Page</title>
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
</head>
<body class="bg-gray-100 mt-28">
    @include('layouts.header')
    
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold mb-2">Unggah Album</h2>
                <button id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md">Unggah</button>
            </div>
        </div>
        <!-- Card 2: Fillable Inputs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Keterangan Album</h2>
            <div class="">
                <label for="regular-form-1" class="form-label">Judul</label>
                <input id="regular-form-1" type="text" class="form-control" placeholder="Input text">
                <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
            </div>
            <div class="mt-6">
                <label for="regular-form-1" class="form-label">Deskripsi</label>
                <textarea class="form-control pl-2 py-6 resize-none" rows="1" placeholder="Input text"></textarea>
                <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
            </div>
        </div>
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</body>
</html>
