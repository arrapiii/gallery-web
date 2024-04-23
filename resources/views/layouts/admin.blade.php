<!DOCTYPE html>
<html lang="en" class="{{ session('theme', 'light') }}">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('images/logo.jpeg') }}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
        <!-- END: CSS Assets-->

        
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        
        <!-- FONTS -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>

        <!-- FA -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Add DataTables CDN -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <style>
            /* width */
            ::-webkit-scrollbar {
            width: 10px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
            background: #175a72;
            border-radius: 10px;
            }

            table.dataTable.no-footer {
                border-bottom: 0 !important;
            }
            table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
                text-align: center;
            }
        </style>
        
    </head>
    <!-- END: Head -->
    <body class="py-5 md:py-0">
        @include('admin.topbar')
        <div class="flex overflow-hidden">
            @include('admin.sidebar')
            
            @yield('content')
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        <div class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div>
        <!-- END: Dark Mode Switcher-->

        <script>
            const html = document.documentElement;
            const darkModeSwitcher = document.querySelector('.dark-mode-switcher');
            const darkModeToggleButton = document.querySelector('.dark-mode-switcher__toggle');
        
            // Function to apply the theme preference from local storage
            function applyThemePreference() {
                const theme = localStorage.getItem('theme') || 'light';
                html.classList.remove('light', 'dark');
                html.classList.add(theme);
        
                darkModeSwitcher.classList.toggle('active', theme === 'dark');
                darkModeToggleButton.classList.toggle('dark-mode-switcher__toggle--active', theme === 'dark');
            }
        
            // Function to toggle the theme and save it to local storage
            function toggleTheme() {
                const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                applyThemePreference(); // Apply the new theme immediately
            }
        
            // Add event listener to the dark mode toggle button
            darkModeToggleButton.addEventListener('click', toggleTheme);
        
            // Apply theme preference when the page is loaded
            applyThemePreference();
        </script>
        
    <!-- Include Moment.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

     <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>