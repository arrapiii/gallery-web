@auth
    @if (!request()->is('avatar*')) <!-- Check if the current route is not related to the avatar -->
    <header class="bg-white py-4 fixed top-0 left-0 w-full z-50" style="min-width: 800px">
        <nav class="flex justify-between items-center w-[97%] mx-auto">
          <header class="bg-white py-4 fixed top-0 left-0 w-full z-50">
            <nav class="flex justify-between items-center w-[97%] mx-auto">
              <div class="flex items-center"> <!-- Grouping div starts here -->
                <a href="/" class="relative flex items-center inline-block h-5 h-full font-black leading-none">
                  <span class="ml-3 text-xl text-gray-800">Arra<span class="text-pink-500">'s</span></span>
                  <span class="ml-3 text-xl text-gray-800">Gallery<span class="text-pink-500">.</span></span>
                </a>
                
                <div class="nav-links duration-500 md:w-auto w-full flex items-center px-5" style="display: flex; flex-wrap: wrap">
                  <ul class="flex md:flex-row flex-col md:items-center md:gap-[1vw] gap-8" style="flex-direction: row">
                    <li>
                      <a class="home-button {{ Request::is('home*') ? 'bg-blue-500 text-white rounded-md px-4 py-2' : '' }} mobile-list" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="relative">
                      <p class="create-button relative cursor-pointer {{ Request::is('gallery/create*') || Request::is('album/create*') ? 'bg-blue-500 text-white rounded-md px-4 py-2' : '' }} mobile-list" onclick="toggleCreateDropdown()">Unggah</p>
                      <div id="createDropdown" class="hidden absolute left-0 mt-6 bg-white border rounded-lg shadow-lg w-48">
                        <a href="{{ route('create.album') }}" class="block px-4 py-2 hover:bg-gray-200 rounded-lg">Buat Album</a>
                        <a href="{{ route('create.foto') }}" class="block px-4 py-2 hover:bg-gray-200 rounded-lg">Unggah Foto</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div> <!-- Grouping div ends here -->

              <!-- Search bar -->
              <form id="searchForm" action="{{ route('search.results') }}" method="GET" class="relative flex-grow">
                <input id="searchInput" name="query" type="text" placeholder="Search..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500">
              </form>

 
              <div class="pl-6 flex items-center gap-6">
                <div class="relative ml-auto">
                  @if(auth()->user()->avatar)
                      <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Image" class="w-10 h-10 rounded-full cursor-pointer" onclick="toggleProfileDropdown()">
                  @else
                      <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-300 text-gray-600 cursor-pointer" onclick="toggleProfileDropdown()">
                          <span>{{ ucfirst(substr(auth()->user()->name, 0, 1)) }}</span>
                      </div>
                  @endif
                  <ul id="profileOptions" class="hidden absolute right-0 mt-4 bg-white border rounded-lg shadow-lg w-48">
                      <li><a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 rounded-lg">Profile</a></li>
                      <li>
                          <form action="{{ route('logout') }}" method="POST">
                              @csrf
                              <button type="submit" class="block w-full px-4 py-2 text-gray-800 hover:bg-gray-200 rounded-lg">Logout</button>
                          </form>
                      </li>
                  </ul>
                </div>              
              </div>
            </nav>
          </header>
        </nav>
    </header>
    @endif


<script>
  const navLinks = document.querySelector('.nav-links');
  const profileOptions = document.getElementById('profileOptions');
  const mobileLists = document.querySelectorAll('.mobile-list');

  function toggleCreateDropdown() {
    const createDropdown = document.getElementById('createDropdown');
    createDropdown.classList.toggle('hidden');
  }

  function toggleProfileDropdown() {
    profileOptions.classList.toggle('hidden');
  }

  mobileLists.forEach(list => {
    list.addEventListener('click', function() {
      mobileLists.forEach(item => item.classList.remove('active'));
      this.classList.add('active');
    });
  });

  $(document).ready(function() {
    $('#search-form').submit(function(event) {
        event.preventDefault();

        var query = $('#query').val();
        var name = $('#name').val();

        $.ajax({
            url: '{{ route('search.results') }}',
            method: 'GET',
            data: {
                query: query,
                name: name
            },
            success: function(response) {
                $('.gallery').html(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
});


</script>
@endauth
