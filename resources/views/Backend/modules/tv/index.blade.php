@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card card-border-primary">
                <div class="card-body d-flex justify-content-between align-items-center pb-0">
                    <h3 class="card-title m-0 h5 text-primary">TVs</h3>
                    @can('create_tv')
                        <a href="{{ route('backend.tv.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Tv
                        </a>
                    @endcan
                </div>
                <hr>

                <div class="card-body">
                    <div class="table-responsive w-100">
                        <table id="tv_dt" class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>

                                <th>Stream URL</th>
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

                                <th>Stream URL</th>
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
        $('#tv_dt').DataTable({
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
                {"data": "stream_url", "orderable": false},
                {"data": "thumbnail", "orderable": false},
                {"data": "category"},
                {"data": "language"},
                {"data": "region"},
                {"data": "status"},
                {"data": "action", "orderable": false}
            ],

            "createdRow": function (row, data, dataIndex) {
                $(row).find('td').css({
                    "text-align": "left",
                    "vertical-align": "middle"
                });
            },
            "order": [[1, "asc"]]
        });
    </script>
@endsection
