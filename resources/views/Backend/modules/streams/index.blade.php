@extends('Backend.includes.layout')
@section('content')


        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Streams</h1>
                    <div class="text-muted">Manage your streams.</div>
                </div>
                <div class="action-toolbar">
                    @can('create_stream')
                        <a href="{{ route('backend.stream.create') }}" class="btn btn-dark-blue">
                            <i class="fas fa-plus"></i>
                            Add stream
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card shadow-lg border">

            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="channelstreams_dt" class="table table-striped ">
								<thead>
                                <tr>
									<th>#</th>
									<th>Title</th>
                                    <th>Description</th>
                                    <th>Stream Key</th>
                                    <th>Stream Link</th>
                                    <th>Stream URL</th>
									<th>Thumbnail</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Ended</th>
									<th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Stream Key</th>
                                    <th>Stream Link</th>
                                    <th>Stream URL</th>
                                    <th>Thumbnail</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Ended</th>
                                    <th>Action</th>
									</tr>
								</tfoot>
							</table>
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
            new window.DataTable('#channelstreams_dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.stream.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "title"},
                    {"data": "description"},
                    {"data": "stream_key", "orderable": false},
                    {"data": "stream_link", "orderable": false},
                    {"data": "stream_url", "orderable": false},
                    {"data": "thumbnail", "orderable": false},
                    {"data": "start_time"},
                    {"data": "end_time"},
                    {"data": "is_ended"},
                    {"data": "action", "orderable": false}
                ],

                "createdRow": function (row) {
                    row.querySelectorAll('td').forEach(function (cell) {
                        cell.style.textAlign = 'left';
                        cell.style.verticalAlign = 'middle';
                    });
                },
                "order": [[7, "desc"]]
            });
        });
        </script>
@endsection
