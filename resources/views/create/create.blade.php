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
<body class="mt-28">
    @include('layouts.header')
    
    <form id="photo-upload-form" action="{{ route('store.foto') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container mx-auto px-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold mb-2">Unggah Foto</h2>
                    <button type="submit" id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V2.5A1.5 1.5 0 0010.5 1h-1A1.5 1.5 0 008 2.5V4a8 8 0 014 6.928V11h2.086a1.5 1.5 0 00.97-2.622l-8-7a1.5 1.5 0 00-2.112 2.122L12.086 9H10a10 10 0 00-6.598 17.67 1 1 0 00.11-.914l.57-2.068a1.5 1.5 0 00-2.296-1.268l-.75.54A12.001 12.001 0 004 12z"></path>
                        </svg> Unggah
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Card 1: Photo Input -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Photo Input</h2>
                    <div class="relative border border-gray-300 bg-white rounded-md px-3 py-2 flex items-center cursor-pointer">
                        <input id="file-upload" name="file" type="file" class="sr-only" accept="image/jpeg, image/png, image/gif">
                        <label for="file-upload" class="w-full">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="ml-2 text-sm leading-5 font-medium text-gray-900">Upload a file</span>
                        </label>
                    </div>
                    <div class="image-container mt-2"></div>
                </div>
                
                <!-- Card 2: Fillable Inputs -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Keterangan Foto</h2>
                    @if($albums->isEmpty())
                        <!-- Display this select if the user doesn't have any albums -->
                        <label for="album_id" class="form-label">Pilih Album</label>
                        <select id="album_id" name="album_id[]" data-placeholder="" class="tom-select w-full" disabled> 
                            <option value="Kamu Belum Punya Album, buatlah dahulu di Header Create">Kamu Belum Punya Album, buatlah dahulu di Header Create</option>
                        </select> 
                        <a class="form-help cursor-pointer text-blue-500" onclick="showCreateAlbumAlert()">Tap di sini untuk pindah ke form album dengan mudah</a>
                    @else
                        <!-- Display this select if the user has albums -->
                        <label for="album_id" class="form-label">Pilih Album</label>
                        <select id="album_idelse" name="album_id[]" data-placeholder="Pilih Album" class="tom-select w-full"> 
                            @foreach ($albums as $album)
                                <option value=""></option>
                                <option value="{{ $album->id }}">{{ $album->nama_album }}</option>
                            @endforeach
                        </select> 
                    @endif
                    <div class="mt-6">
                        <label for="judul_foto" class="form-label">Judul</label>
                        <input id="judul_foto" name="judul_foto" type="text" class="form-control" placeholder="Input text">
                        <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
                    <div class="mt-6">
                        <label for="deskripsi_foto" class="form-label">Deskripsi</label>
                        <textarea class="form-control pl-2 py-6 resize-none" id="deskripsi_foto" name="deskripsi_foto" rows="1" placeholder="Input text"></textarea>
                        <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

        // Handle form submission
        document.getElementById('photo-upload-form').addEventListener('submit', function(event) {
            // Validate form before submission
            if (!validateForm()) {
                // Prevent form submission
                event.preventDefault();
                // Show Toastr error message
                toastr.error('Please fill in all required fields.');
            } else {
                // Show loading animation
                document.getElementById('submit-button').innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V2.5A1.5 1.5 0 0010.5 1h-1A1.5 1.5 0 008 2.5V4a8 8 0 014 6.928V11h2.086a1.5 1.5 0 00.97-2.622l-8-7a1.5 1.5 0 00-2.112 2.122L12.086 9H10a10 10 0 00-6.598 17.67 1 1 0 00.11-.914l.57-2.068a1.5 1.5 0 00-2.296-1.268l-.75.54A12.001 12.001 0 004 12z"></path>
                </svg> Uploading...`;
            }
        });

        // Function to validate the form
        function validateForm() {
            const judulFoto = document.getElementById('judul_foto').value;
            const deskripsiFoto = document.getElementById('deskripsi_foto').value;
            const albumFoto = document.getElementById('album_idelse');
            // Add more fields for validation as needed

            // Check if any of the required fields are empty
            if (!judulFoto || !deskripsiFoto || !albumFoto) {
                return false;
            }
            return true;
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Check if a success message exists
            var successMessage = "{{ session('success') }}";
            if (successMessage) {
                // Display Toastr success message
                toastr.success(successMessage);
            }
        });

        function showCreateAlbumAlert() {
            Swal.fire({
                title: 'No albums found!',
                text: 'You currently have no albums. Would you like to create one?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Create Album',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect the user to the create album page or perform any action you want
                    window.location.href = "{{ route('create.album') }}";
                }
            });
        }

        // Listen for click event on the album selection input
        document.getElementById('album_id').addEventListener('click', function(event) {
            // Check if the user has any albums
            var albumsExist = "{{ $albums->isNotEmpty() }}";
            if (!albumsExist) {
                // If the user doesn't have any albums, show the create album alert
                showCreateAlbumAlert();
            }
        });
    </script>
</body>
</html>
