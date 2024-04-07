@extends('layouts.master')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Page</title>
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
    <style>
        .image-container {
            max-width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            max-width: 100%; /* Adjust maximum width */
            height: auto;
        }
    </style>
</head>
<body class="bg-gray-100 mt-28">
    @include('layouts.header')
    
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold mb-2">Unggah Foto</h2>
                <button id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hidden">Unggah</button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Card 1: Photo Input -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Photo Input</h2>
                <form id="photo-upload" action="{{ route('upload.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="relative border border-gray-300 bg-white rounded-md px-3 py-2 flex items-center cursor-pointer">
                        <input id="file-upload" name="file" type="file" class="sr-only"  accept="image/jpeg, image/png, image/gif">
                        <label for="file-upload" class="w-full">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="ml-2 text-sm leading-5 font-medium text-gray-900">Upload a file</span>
                        </label>
                    </div>
                    <div class="image-container mt-2"></div>
                </form>
            </div>
            
             <!-- Card 2: Fillable Inputs -->
             <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Keterangan Foto</h2>
                <select data-placeholder="Select your favorite actors" class="tom-select w-full" multiple> 
                    <option value="1" selected>Leonardo DiCaprio</option> 
                    <option value="2">Ayisha Maylia Ramadhani</option> 
                    <option value="3" selected>Robert Downey, Jr</option> 
                    <option value="4">Samuel L. Jackson</option> 
                    <option value="5">Morgan Freeman</option> 
                </select> 
                <div class="mt-6">
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
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    <script>
        // Listen for file input change event
        document.getElementById('file-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreviewContainer = document.querySelector('.image-container');
                    imagePreviewContainer.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('max-w-full');
                    imagePreviewContainer.appendChild(img);

                    // Show the submit button
                    document.getElementById('submit-button').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
