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
    <div class="container mx-auto px-4 mt-18">
        <div class="max-w-xl mx-auto bg-white shadow-md rounded-md p-8">
            <h1 class="text-2xl font-semibold mb-4">Change ur avatar!</h1>
            <!-- Display Toastr success message after uploading avatar -->
            @if(session('success'))
                <script>
                    toastr.success('{{ session('success') }}');
                </script>
            @endif
    
            <label for="avatarInput" class="relative w-24 h-24 rounded-full flex items-center justify-center bg-gray-300 text-gray-600 cursor-pointer mx-auto mb-4">
                @if (auth()->user()->avatar)
                    <!-- Display the user's avatar if it exists -->
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full rounded-full">
                @else
                    <!-- Display the first letter of the user's name if no avatar -->
                    <span class="text-3xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
            </label>
    
            <!-- Avatar upload form -->
            <form id="avatarForm" action="{{ route('store.avatar') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex justify-center">
                @csrf
                <div class="mb-4 relative">
                    <!-- Hidden file input -->
                    <input type="file" name="avatar" id="avatarInput" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/png, image/jpeg">
                </div>
                @error('avatar')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                <!-- Loading animation -->
                <div id="loading" class="hidden w-6 h-6 border-2 border-blue-500 border-t-blue-900 rounded-full animate-spin"></div>
                <!-- Upload button (initially hidden) -->
                <button type="submit" id="uploadButton" class="hidden px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600">Upload Avatar</button>
            </form>
            <!-- Next button (initially hidden) -->
            <div id="nextButton" class="hidden flex justify-center">
                <a href="{{ route('home') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600">Next</a>
            </div>
            <!-- Remove and Next buttons -->
            @if (auth()->user()->avatar)
            <div class="flex justify-center mt-4">
                <form id="removeAvatarForm" action="{{ route('remove.avatar') }}" method="POST">
                    @csrf
                    <button type="button" id="removeAvatarBtn" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600">Remove Avatar</button>
                </form>
                <a href="{{ route('home') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 ml-4">Next</a>
            </div>
            @endif
    
            <!-- Skip for now link -->
            @if (!auth()->user()->avatar)
            <div class="flex justify-center mt-4">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Skip for now</a>
            </div>
            @endif
    
             <!-- Profile information update section -->
             <div>
                <h2 class="text-lg font-semibold mt-12 mb-2">Update Profile Information</h2>
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" class="mb-4">
                    @csrf
                    @method('put')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="name" id="name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" name="email" id="email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ auth()->user()->email }}" required>
                    </div>
                    <!-- Add more fields as needed -->
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update</button>
                    </div>
                </form>
            </div>
    
        </div>
    </div>

    <!-- Back Button -->
    <div class="fixed top-24 left-4">
        <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-full shadow-md hover:bg-blue-600">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Display the upload button when a file is selected
        document.getElementById('avatarInput').addEventListener('change', function() {
            document.getElementById('uploadButton').classList.remove('hidden');
            toastr.info('Press the "Upload Avatar" button to apply the image.');
        });
    
        // Display success message after uploading or removing avatar
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        
        // Confirmation alert before removing avatar
        document.getElementById('removeAvatarBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('removeAvatarForm').submit();
                }
            });
        });

        function showSuccessMessage() {
        toastr.success('Profile updated successfully.');
    }

    // Listen for form submission success event
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        // Make an AJAX request to submit the form data
        fetch(this.action, {
            method: this.method,
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                showSuccessMessage(); // Display success message
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>