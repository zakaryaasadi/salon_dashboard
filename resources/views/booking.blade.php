@extends('master')

@section('content')
<div class="page-heading">
    <h1 class="page-title">BOOKING</h1>
</div>
@include('info')
<div class="page-content fade-in-up">
<div class="ibox">
    <div class="ibox-body">
        <h5 class="font-strong mb-4">BOOKING LIST</h5>
        <div class="flexbox mb-4">
            <div class="flexbox">
                <label class="mb-0 mr-2">Location:</label>
                <select class="form-control" id="type-filter" title="Please select" data-style="btn-solid" data-width="150px">
            <option value="">All</option>
            <option>JBR</option>
            <option>Al Wasl</option>
            <option>Burj Al Arab</option>
        </select>
            </div>
            <div class="input-group-icon input-group-icon-left mr-3">
                <span class="input-icon input-icon-right font-16"><i class="fa fa-search"></i></span>
                <input class="form-control form-control-rounded form-control-solid" id="key-search" type="text" placeholder="Search ...">
            </div>
        </div>
        <div class="table-responsive row">
            <table class="table table-hover" id="table">
                <thead class="thead-default thead-lg">
                    <tr>
                        <th>#</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Approved</th>
                        <th>Date</th>
                        <th class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $index => $item)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>#{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->phone}}</td>
                        <td>{{$item->email}}</td>
                        @if ($item->location == "JBR")
                            <td>
                                <span class="badge badge-primary badge-pill font-13">{{$item->location}}</span>
                            </td>
                        @elseif ($item->location == "Al Wasl")
                            <td>
                                <span class="badge badge-warning badge-pill font-13">{{$item->location}}</span>
                            </td>
                        @elseif ($item->location == "Burj Al Arab")
                            <td>
                                <span class="badge badge-success badge-pill font-13">{{$item->location}}</span>
                            </td>
                        @endif
                        @if ($item->is_approved)
                            <td>
                                <span><i class="fa fa-check-circle text-success font-20"></i></span>
                            </td>
                        @else
                            <td>
                                <span><i class="fa fa-times text-danger font-20"></i></span>
                            </td>
                        @endif
                        <td>{{$item->date}}</td>
                        <td>
                            <a href="{{url('/booking', ['id' => $item->id])}}" class="btn btn-sm btn btn-warning btn-air">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script src="{{asset('vendors/chart.js/dist/Chart.min.js')}}"></script>
<script src="{{asset('vendors/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
<script src="{{asset('js/dashboard_ecommerce.js')}}"></script> 
<script>
    info_this_week = @json($info_this_week);
    info_last_week = @json($info_last_week);
    info_year = @json($info_year);
    $(function() {
        
        var table = $('#table').DataTable({
            pageLength: 20,
            responsive: true,
            fixedHeader: true,
            dom: 'rtip',
            columnDefs: [{
                targets: 'no-sort',
                orderable: false
            }],
            select: true
        });
        
        $('#key-search').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#type-filter').on('change', function() {
            table.column(5).search($(this).val()).draw();
        });
    });

    </script>
@endsection