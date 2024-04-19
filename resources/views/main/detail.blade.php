@extends('layouts.master')

@section('content')
<div class="w-full mx-auto px-72 py-12 mt-12">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Main card container with rounded corners -->
        <div class="flex">
            <!-- Container for the photo on the left -->
            <div class="w-1/3 flex justify-center items-center p-4">
                <img src="{{ asset('storage/' . $foto->lokasi_file) }}" alt="Photo" class="max-w-full h-auto rounded-lg">
            </div>
            <!-- Container for title, desc, profile, followers, like, follow, and comments -->
            <div id="iniIsi" class="w-2/3 p-4 hidden">
                <!-- Title and description -->
                <div class="mb-4">
                    <h1 class="text-gray-800 font-bold text-lg mb-2">{{ $foto->judul_foto }}</h1>
                    <p class="text-gray-700 mb-4">{{ $foto->deskripsi_foto }}</p>
                    <!-- Add a hidden class to initially hide the div -->
                    <div id="likeButtonsContainer" class="mb-4">
                        <!-- Like button -->
                        <button id="likeButton" class="bg-transparent border-none focus:outline-none">
                            <div class="flex items-center rounded-lg border border-red-500 px-2 py-1">
                                <i id="likeIcon" class="far fa-heart fa-lg text-red-500 mr-1"></i>
                                <div class="">
                                    <span id="likeCount" class="text-red-500">{{ $likeCount }}</span>
                                    <span class="text-red-500">Like</span>
                                </div>
                            </div>
                        </button>
                         <!-- Download button -->
                         <button id="downloadButton" class="bg-transparent border-none focus:outline-none ml-4">
                            <div class="flex items-center rounded-lg border border-blue-500 px-2 py-1">
                                <i id="downloadIcon" class="fas fa-download fa-lg text-blue-500 mr-1"></i>
                                <span class="text-blue-500">Download</span>
                            </div>
                        </button>
                        <!-- Album Info Button -->
                        @if(!$foto->album_id) <!-- Check if the photo doesn't belong to any album -->
                            <button id="Album" class="bg-transparent border-none focus:outline-none ml-4">
                                <div class="flex items-center rounded-lg border border-blue-500 px-2 py-1">
                                    <i id="infoIcon" class="fas fa-info-circle fa-lg text-blue-500 mr-1"></i> <!-- Info icon -->
                                    <span class="text-blue-500">This photo has no album</span>
                                </div>
                            </button>
                        @endif
                        <!-- Report button -->
                        <button id="reportButton" class="bg-transparent border-none focus:outline-none ml-4">
                            <div class="flex items-center rounded-lg border border-yellow-500 px-2 py-1">
                                <i id="reportIcon" class="fas fa-exclamation-triangle fa-lg text-yellow-500 mr-1"></i>
                                <span class="text-yellow-500">Report</span>
                            </div>
                        </button>
                        <!-- Report button (Edit) -->
                        @if(auth()->check() && auth()->user()->id === $foto->user_id)
                        <button id="reportButton" class="bg-transparent border-none focus:outline-none ml-4">
                            <div class="flex items-center rounded-lg border border-yellow-500 px-2 py-1">
                                <i id="editIcon" class="fas fa-edit fa-lg text-yellow-500 mr-1"></i> <!-- Edit icon -->
                                <span class="text-yellow-500"><a href="{{ route('edit.foto', $foto->id) }}">Edit</a>
                                </span>
                            </div>
                        </button>
                        @endif
                    </div>
                </div>  
                 <!-- Profile, followers, and follow button -->
                 <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <a href="{{ route('profile.show', ['userId' => $foto->user]) }}" class="mr-2">
                            <img src="{{ asset('storage/' . $foto->user->avatar) }}" alt="Profile Photo" class="w-12 h-12 rounded-full">
                        </a>
                        <div class="flex flex-col">
                            <div class="text-gray-800 font-bold">{{ $foto->user->name }}</div>
                            <div class="text-gray-500 text-sm">{{ $foto->user->photos()->count() }} Post</div>
                        </div>
                    </div>
                    {{-- <div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Follow</button>
                    </div> --}}
                </div>
                <!-- Comments -->
                <h2 class="text-gray-800 font-bold text-lg mb-4">Komentar (<span id="commentCount">{{ count($foto->comments) }}</span>)</h2>
                <div id="commentsSection" class="max-h-48 overflow-auto">
                    <!-- Sample comments -->
                    @if($foto->comments)
                        @foreach($foto->comments as $comment)
                            <div class="w-full mb-4">
                                <div class="flex items-start">
                                    <a href="{{ route('profile.show', ['userId' => $comment->user->id]) }}" class="mr-2">
                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Profile Photo" class="w-12 h-12 rounded-full mr-4">
                                    </a>
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Profile Photo" class="w-12 h-12 rounded-full mr-4">
                                    <div class="flex-grow">
                                        <div class="flex justify-between">
                                            <strong>{{ $comment->user->name }}</strong>
                                            <span class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p>{{ $comment->isi_komentar }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4"> <!-- Divider line -->
                        @endforeach
                    @else
                        <p>No comments available.</p>
                    @endif
                    <!-- Add more comments here -->
                </div>                        
                <div class="flex items-center mt-4">
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Photo" class="w-12 h-12 rounded-full mr-4">
                    <div class="relative flex-grow">
                        <form id="commentForm" action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="foto_id" value="{{ $foto->id }}">
                            <textarea name="isi_komentar" id="commentInput" placeholder="Write a comment..." class="border border-gray-300 px-4 py-2 rounded-md w-full h-12"></textarea>
                            <button type="submit" id="sendComment" class="absolute top-0 right-0 mt-2 mr-2 focus:outline-none transition duration-150 ease-in-out hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 mt-1 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>                        
                    </div>
                </div>         
            </div>
        </div>
    </div>
</div>

    <!-- Back Button -->
    <div class="fixed top-24 left-4">
        <a href="javascript:history.back()" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-full shadow-md hover:bg-blue-600">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

<!-- Add a loading indicator -->
<div id="loadingIndicator" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex justify-center items-center z-50 hidden">
    <span class="text-white">Loading...</span>
</div>

<script type="text/javascript">
    console.log('JavaScript file loaded');
    
    jQuery(document).ready(function($) {
    // Function to check if the current user has liked the photo
        function checkLikeStatus() {
            $.ajax({
                url: '{{ route("photos.checklike", $foto) }}',
                type: 'GET',
                success: function(response) {
                    // Update the heart icon based on the response
                    if (response.liked) {
                        $('#likeIcon').addClass('fas').removeClass('far');
                    } else {
                        $('#likeIcon').addClass('far').removeClass('fas');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Handle error
                }
            });
        }

       // Function to update the like count
        function updateLikeCount() {
            $.ajax({
                url: '{{ route("photos.getlikecount", $foto) }}',
                type: 'GET',
                success: function(response) {
                    $('#likeCount').text(response.likeCount);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }


        // Check the like status when the page is loaded
        checkLikeStatus();

        updateLikeCount();

        // Handle like button click
        $('#likeButton').click(function() {
            // Check if the heart icon is filled or outlined
            var isLiked = $('#likeIcon').hasClass('fas');

            // Determine the route based on whether the photo is already liked or not
            var route = isLiked ? '{{ route("photos.unlike", $foto) }}' : '{{ route("photos.like", $foto) }}';

            // Determine the request method based on whether the photo is already liked or not
            var method = isLiked ? 'DELETE' : 'POST';

            $.ajax({
                url: route,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response:', response);
                    // Toggle the heart icon based on the response
                    $('#likeIcon').toggleClass('far fas'); // Toggle between empty and filled heart icons
                    // Handle other UI updates if necessary
                    updateLikeCount();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Handle error
                }
            });
        });

        // Wait for 1 second before showing the like buttons
        setTimeout(function() {
            var likeButtonsContainer = document.getElementById('iniIsi');
            if (likeButtonsContainer) {
                likeButtonsContainer.classList.remove('hidden');
            }
        }, 800);

        $('#commentInput').on('input', function() {
            var $submitButton = $('#sendComment');
            if ($(this).val().trim().length > 0) {
                $submitButton.removeClass('hidden');
            } else {
                $submitButton.addClass('hidden');
            }
        });

        function loadComments() {
            console.log('Loading comments...');

            $.ajax({
                url: '{{ route("photos.comments", $foto) }}',
                type: 'GET',
                success: function(response) {
                    console.log('Comments loaded successfully:', response);
                    // Clear the existing comments section
                    $('#commentsSection').empty();
                    
                    // Check if there are comments
                    if (response.comments.length > 0) {
                        var allUserIds = [];
                        // Loop through each comment in the response
                        response.comments.forEach(function(comment) {
                    
                            var createdAtFormatted = moment(comment.created_at).fromNow();
                            // Construct HTML for each comment
                            var commentHtml = `
                                <div class="comment flex items-start mb-4">
                                    <a href="/profile/${comment.user.id}" class="mr-2">
                                        <img src="{{ asset('storage/') }}/${comment.user.avatar}" alt="Profile Photo" class="w-12 h-12 rounded-full mr-4">
                                    </a>
                                    <div class="flex-grow">
                                        <div class="flex justify-between">
                                            <strong>${comment.user.name}</strong>
                                            <span class="text-gray-500 text-sm">${createdAtFormatted}</span>
                                        </div>
                                        <p>${comment.isi_komentar}</p>
                                    </div>
                                </div>
                                <hr class="my-4">
                            `;
                            
                            // Append the comment HTML to the comments section
                            $('#commentsSection').append(commentHtml);
                            allUserIds.push(comment.user.id);
                        });
                        // Update comment count
                        $('#commentCount').text(response.comments.length);
                        console.log('All user IDs:', allUserIds);
                    } else {
                        // If there are no comments, display a message
                        $('#commentsSection').html('<p>No comments available.</p>');
                        // Update comment count
                        $('#commentCount').text('0');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading comments:', error);
                }
            });
        }
        loadComments();

        // Submit comment form via Ajax
        $('#commentForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data
            
            $.ajax({
                url: $(this).attr('action'), // Form action URL
                type: $(this).attr('method'), // Form method (POST)
                data: formData, // Form data
                success: function(response) {
                    // Clear comment input
                    $('#commentInput').val('');
                    
                    console.log('Comment submitted successfully.');

                    loadComments();

                    // Optionally, you can show a success message or perform other UI updates
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
    
@endsection