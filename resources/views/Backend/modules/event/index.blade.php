@extends('Backend.includes.layout')
@section('content')


    <div class="col-12 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Events</h1>
                <div class="text-muted">Manage your events.</div>
            </div>
            <div class="action-toolbar">
                @can('create_event')
                    <a href="{{ route('backend.event.create') }}" class="btn btn-dark-blue">
                        <i class="fas fa-plus"></i> Add Event
                    </a>
                @endcan
            </div>
        </div>
            <div class="card shadow-lg border">

            	<div class="card-body">

                    <div class="table-responsive mt-3">
							<table id="events_dt" class="table table-striped ">
								<thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Thumbnail</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Publish Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Rates</th>
                                    <th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Thumbnail</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Publish Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Rates</th>
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
            new window.DataTable('#events_dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.event.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "event_name"},
                    {"data": "thumbnail"},
                    {"data": "description"},
                    {"data": "status"},
                    {"data": "publish_date"},
                    {"data": "start_date"},
                    {"data": "end_date"},
                    {"data": "rates"},
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
