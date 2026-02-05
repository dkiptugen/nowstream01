@extends('Backend.includes.layout')
@section('content')


    <div class="col-12 mb-3">
            <div class="card card-border-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-0 h5 text-primary">Events</h3>
                    @can('create_event')
                        <a href="{{ route('backend.event.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Event
                        </a>
                    @endcan
                </div>
            	<div class="card-body">

                    <div class="table-responsive mt-3">
							<table id="events_dt" class="table table-striped table-condensed">
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
            $('#events_dt').DataTable({
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
                "createdRow": function(row, data, dataIndex) {
                    $(row).find('td').css({
                        "text-align": "left",
                        "vertical-align": "middle"
                    });
                },

                "order": [[7, "desc"]]

            });
        </script>
@endsection
