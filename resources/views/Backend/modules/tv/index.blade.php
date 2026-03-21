@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Tvs</h1>
                    <div class="text-muted">Manage your televisions.</div>
                </div>
                <div class="action-toolbar">
                    @can('create_tv')
                        <a href="{{ route('backend.tv.create') }}" class="btn btn-dark-blue">
                            <i class="fas fa-plus"></i>
                            Add Tv
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card shadow-lg border">

                <div class="card-body">
                    <div class="table-responsive w-100">
                        <table id="tv_dt" class="table table-striped ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Language</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Language</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('header')
@endsection
@section('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new window.DataTable('#tv_dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.tv.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "title"},
                    {"data": "thumbnail", "orderable": false},
                    {"data": "category"},
                    {"data": "language"},
                    {"data": "region"},
                    {"data": "status"},
                    {"data": "action", "orderable": false}
                ],

                "createdRow": function (row) {
                    row.querySelectorAll('td').forEach(function (cell) {
                        cell.style.textAlign = 'left';
                        cell.style.verticalAlign = 'middle';
                    });
                },
                "order": [[1, "asc"]]
            });
        });
    </script>
@endsection
