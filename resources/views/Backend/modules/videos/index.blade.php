@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card card-border-primary">
                <div class="card-body d-flex justify-content-between align-items-center pb-0">
                    <h3 class="card-title m-0 h5 text-primary">Videos</h3>
                    <a href="{{ route('backend.video.create') }}" class="btn btn-primary m-0 h5">+ Add Video</a>

                </div>
                <hr>

            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="videos" class="table table-striped table-condensed">
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
            $('#videos').DataTable({
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
                    {"data":"created_at"},
                    {"data": "action", "orderable": false}
                ],
                "createdRow": function(row, data, dataIndex) {
                    $(row).find('td').css({
                        "text-align": "left",
                        "vertical-align": "middle"
                    });
                },
                "order": [[6, "desc"]]
            });
        </script>
@endsection
