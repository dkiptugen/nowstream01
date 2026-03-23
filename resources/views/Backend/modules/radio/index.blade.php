@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Radios</h1>
                    <div class="text-muted">Manage your radios.</div>
                </div>
                <div class="action-toolbar">
                    @can('create_radio')
                        <a href="{{ route('backend.radio.create') }}" class="btn btn-dark-blue">
                            <i class="fas fa-plus"></i>
                            Add Radio
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card shadow-lg border">


            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="radio_dt" class="table table-striped ">
								<thead>
                                <tr>
									<th>#</th>
									<th>Title</th>
                                    <th>Description</th>
									<th>Logo</th>
                                    <th>Language</th>
                                    <th>Region</th>
                                    <th>Category</th>
                                    <th>Status</th>
									<th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Logo</th>
                                    <th>Language</th>
                                    <th>Region</th>
                                    <th>Category</th>
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
            new window.DataTable('#radio_dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.radio.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "title"},
                    {"data": "description"},
                    {"data": "thumbnail", "orderable": false},
                    {"data": "language"},
                    {"data": "region"},
                    {"data": "category"},
                    {"data": "status", "orderable": false},
                    {"data": "action", "orderable": false}
                ],

                "createdRow": function (row) {
                    row.querySelectorAll('td').forEach(function (cell) {
                        cell.style.textAlign = 'left';
                        cell.style.verticalAlign = 'middle';
                    });
                },
                "order": [[1, "desc"]]
            });
        });
        </script>
@endsection
