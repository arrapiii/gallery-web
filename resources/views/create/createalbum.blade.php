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
<body class="mt-28">
    @include('layouts.header')
    
    <form id="albumForm" action="{{ route('store.album') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container mx-auto px-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold mb-2">Unggah Album</h2>
                        <button type="button" id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md">Unggah</button>
                </div>
            </div>
            <!-- Card 2: Fillable Inputs -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Keterangan Album</h2>
                    <div class="">
                        <label for="nama_album" class="form-label">Judul</label>
                        <input id="nama_album" name="nama_album" type="text" class="form-control" placeholder="Input text" required>
                        <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
                    <div class="mt-6">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control pl-2 py-6 resize-none" rows="1" placeholder="Input text"></textarea>
                        <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
            </div>
        </div>
    </form>

    <!-- BEGIN: JS Assets-->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Check if a success message exists
            var successMessage = "{{ session('success') }}";
            if (successMessage) {
                // Display Toastr success message
                toastr.success(successMessage);
            }

            // Handle form submission
            document.getElementById('submit-button').addEventListener('click', function(event) {
                // Check if the form is valid
                if (document.getElementById('albumForm').checkValidity()) {
                    // If valid, submit the form
                    document.getElementById('albumForm').submit();
                    // Show loading animation
                    document.getElementById('submit-button').innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V2.5A1.5 1.5 0 0010.5 1h-1A1.5 1.5 0 008 2.5V4a8 8 0 014 6.928V11h2.086a1.5 1.5 0 00.97-2.622l-8-7a1.5 1.5 0 00-2.112 2.122L12.086 9H10a10 10 0 00-6.598 17.67 1 1 0 00.11-.914l.57-2.068a1.5 1.5 0 00-2.296-1.268l-.75.54A12.001 12.001 0 004 12z"></path>
                    </svg> Uploading...`;
                } else {
                    // If not valid, display error message using Toastr
                    toastr.error('Judul tidak boleh kosong.');
                }
            });
        });
    </script>

</body>
</html>
