@extends('layouts.admin')

@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10">
            Data List Report
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <button class="btn btn-primary shadow-md mr-2">Export press "+"</button>
                <div class="dropdown">
                    <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i> </span>
                    </button>
                    <div class="dropdown-menu w-40">
                        <ul class="dropdown-content">
                            <li>
                                <a href="" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                            </li>
                            <li>
                                <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export to Excel </a>
                            </li>
                            <li>
                                <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export to PDF </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <div class="w-56 relative text-slate-500">
                        <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="searchRep">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </div>
                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2" id="myTable">
                    <thead>
                        <tr>
                            <th data-priority="1" class="whitespace-nowrap">No</th>
                            <th class="text-center whitespace-nowrap">Image</th>
                            <th class="text-center whitespace-nowrap">Owner Image</th>
                            <th class="text-center whitespace-nowrap">Count Reported</th>
                            <th class="text-center whitespace-nowrap" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uniqueLaporanFotos as $reportedPhoto)
                            <tr class="intro-x">
                                <td class="w-4 text-center">
                                    {{ $loop->iteration }}.
                                </td>
                                <td class="flex justify-center align-center">
                                    <!-- Display image for reported photo -->
                                    <div class="w-12 h-12 image-fit zoom-in">
                                        <img data-action="zoom" src="{{ asset('storage/' . $reportedPhoto->foto->lokasi_file) }}" alt="Reported Photo" class="">
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-center">
                                    <!-- Display owner image for reported photo -->
                                    <div class="font-medium whitespace-nowrap">{{ $reportedPhoto->foto->user->name }}</div>
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <!-- Display count of reports for reported photo -->
                                    {{ $fotoReportCounts[$reportedPhoto->foto_id] ?? 0 }}
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center">
                                        <form action="{{ route('photo.approve') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="foto_id" value="{{ $reportedPhoto->foto_id }}">
                                            <button class="text-success" type="submit"><i data-lucide="check-square" class="w-4 h-4 mr-1 text-success"></i> Approve</button>
                                        </form>
                                        <div class="w-10">

                                        </div>
                                        <form action="{{ route('photo.reject') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="foto_id" value="{{ $reportedPhoto->foto_id }}">
                                            <button class="text-danger" type="submit"><i data-lucide="trash-2" class="w-4 h-4 mr-1 text-danger"></i> Reject</button>
                                        </form>                                        
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
            <!-- END: Data List -->
        </div>
        <!-- BEGIN: approve Confirmation Modal -->
        <div id="approve-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> 
                            <div class="text-3xl mt-5">Are you sure?</div>
                            <div class="text-slate-500 mt-2">
                                Do you really want to delete these records? 
                                <br>
                                This process cannot be undone.
                            </div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                            <button type="button" class="btn btn-primary w-24">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END: Delete Confirmation Modal -->
        <!-- BEGIN: reject Confirmation Modal -->
        <div id="reject-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> 
                            <div class="text-3xl mt-5">Are you sure?</div>
                            <div class="text-slate-500 mt-2">
                                Do you really want to delete these records? 
                                <br>
                                This process cannot be undone.
                            </div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                            <button type="button" class="btn btn-danger w-24">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Delete Confirmation Modal -->
    </div>  
    <!-- END: Content -->
    <script type="text/javascript">
         // data table
         jQuery(document).ready(function($) {
            var dataTable = new DataTable('#myTable', {
                buttons: ['showSelected'],
                dom: 'rtip',
                select: true,
                pageLength: 5,
                border: false,
            });

            $('#searchRep').on('keyup', function() {
                dataTable.search($(this).val()).draw();
            });

        });
    </script>
@endsection