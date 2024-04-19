    @extends('layouts.master')

    @section('content')
    <div class="container mx-auto px-4 mt-48">
        <div class="max-w-xl mx-auto bg-white shadow-md rounded-md p-8">
            <h1 class="text-2xl font-semibold mb-4">Welcome to Our Website!</h1>
            <!-- Display Toastr success message after uploading avatar -->
            @if(session('success'))
                <script>
                    toastr.success('{{ session('success') }}');
                </script>
            @endif

            <p class="mb-4">To personalize your experience, you can add an avatar:</p>
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

        </div>
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
    </script>
    @endsection
