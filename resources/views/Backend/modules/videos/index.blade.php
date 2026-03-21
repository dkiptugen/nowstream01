@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Videos</h1>
                    <div class="text-muted">Manage your videos.</div>
                </div>
                <div class="action-toolbar">
                    @can('create_video')
                        <a href="{{ route('backend.video.create') }}" class="btn btn-dark-blue">+ Add Video</a>
                    @endcan
                </div>
            </div>
            <div class="card shadow-lg border">


            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="videos" class="table table-striped ">
								<thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Thumbnail</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
								</thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Thumbnail</th>
                                        <th>Created Date</th>
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
            new window.DataTable('#videos',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.video.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id"},
                    {"data": "title"},
                    {"data": "description"},
                    {"data": "thumbnail", "orderable": false},
                    {"data": "created_at"},
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
