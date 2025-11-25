@extends('Backend.includes.layout')
@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Channels</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Channels </li>
                </ol>
            </nav>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center pb-0">
                    <h3 class="card-title m-0 h5">Channels</h3>
                    @can('create_channel')
                    <a href="{{ route('channel.create') }}" class="btn btn-dark btn-sm">
                        <i class="bx bx-plus"></i>Channel
                    </a>
                    @endcan
                </div>
                <hr>

            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="channels_dt" class="table table-striped table-condensed">
								<thead>
                                <tr>
									<th>#</th>
									<th>Name</th>
									<th>Thumbnail</th>
                                    <th>Events</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
									<th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
									<th>#</th>
									<th>Name</th>
									<th>Thumbnail</th>
                                    <th>Events</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
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
            $('#channels_dt').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('channel.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "name"},
                    {"data": "thumbnail"},
                    {"data": "events", "orderable": false},
                    {"data": "status"},
                    {"data": "created_at"},
                    {"data": "action", "orderable": false}
                ],
                "columnDefs": [{
                    "targets": 3, // Index of the column you want to add the class to (e.g., 'age' column)
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'width': '200px',
                            'white-space': 'normal !important',
                            'word-wrap': 'break-word !important'
                        });

                    }
                }],
                "createdRow": function(row, data, dataIndex) {
                    $(row).find('td').css({
                        "text-align": "left",
                        "vertical-align": "middle"
                    });
                },
                "order": [[1, "desc"]]
            });
        </script>
@endsection
