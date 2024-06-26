@extends('layouts.master')

@section('content')

<div id="photoContainer" class="w-full max-w-8xl p-5 pb-10 px-20 mx-auto mb-10 gap-4 columns-4 space-y-5 mt-16 min-w=800" style="min-width: 800px">
    @php
    $displayedPaths = [];
    @endphp
    @foreach ($fotos as $foto)
    @if (!in_array($foto->lokasi_file, $displayedPaths))
    <div class="relative group">
        <a href="{{ route('detail', ['foto_id' => $foto->id]) }}">
            <img src="{{ asset('storage/' . $foto->lokasi_file) }}" alt="" class="w-full h-auto rounded-lg" />
            <div
                class="flex justify-center items-center opacity-0 bg-gradient-to-t from-gray-800 via-gray-800 to-opacity-30 group-hover:opacity-50 absolute top-0 left-0 h-full w-full rounded-lg">
            </div>
            <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center opacity-0 hover:opacity-100">
                <div class="flex-row text-center">
                    <h1 class="text-gray-50 font-bold text-lg">{{ $foto->judul_foto }}</h1>
                    <p class="text-gray-200 font-medium text-sm break-words max-w-xs">{{ $foto->deskripsi_foto }}</p>
                    <small class="text-xs font-light text-gray-300">Foto by {{ $foto->user->name }}</small>
                    <div class="absolute bottom-0 right-0 bg-gray-800 px-2 py-1 rounded-bl-lg opacity-80">
                        <p class="text-xs text-gray-200">{{ $foto->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="absolute top-2 left-2">
                        {{-- <!-- Like button -->
                        <button id="likeButton" class="bg-transparent border-none focus:outline-none">
                            <div class="flex items-center rounded-lg px-2 py-1">
                                <i id="likeIcon" class="far fa-heart fa-lg text-red-500 mr-1"></i>
                                <div class="">
                                    <span id="likeCount" class="text-red-500"></span>
                                </div>
                            </div>
                        </button> --}}
                    </div>
                </div>
            </div>
        </a>
    </div>
    @php
    $displayedPaths[] = $foto->lokasi_file;
    @endphp
    @endif
    @endforeach
</div>

@if(session('success'))
<script>
    toastr.success('{{ session('success') }}');
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
</script>

<script>
    $(document).ready(function() {
    $('.like-button').click(function(e) {
        e.preventDefault();
        var photoId = $(this).data('photo-id');
        var likeCount = $(this).find('.like-count');
        var likeIcon = $(this).find('.like-icon');
        
        $.ajax({
            url: "{{ route('photo.like', ':photoId') }}".replace(':photoId', photoId),
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                photo_id: photoId
            },
            success: function(response) {
                // Update like count
                likeCount.text(response.likes);
                // Change like icon color or style
                likeIcon.addClass('text-red-500');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('.unlike-button').click(function(e) {
        e.preventDefault();
        var photoId = $(this).data('photo-id');
        var likeCount = $(this).find('.like-count');
        var likeIcon = $(this).find('.like-icon');
        
        $.ajax({
            url: "{{ route('photo.unlike', ':photoId') }}".replace(':photoId', photoId),
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                photo_id: photoId
            },
            success: function(response) {
                // Update like count
                likeCount.text(response.likes);
                // Change like icon color or style
                likeIcon.removeClass('text-red-500');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
@endif
@endsection
