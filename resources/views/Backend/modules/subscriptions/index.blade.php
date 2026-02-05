@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center pb-0">
                    <h3 class="card-title m-0 h5">Subscriptions</h3>

                </div>
                <hr>

            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="subscriptions_dt" class="table table-striped table-condensed">
								<thead>
                                <tr>
									<th>#</th>
									<th>Stream Token</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Event</th>
									<th>Cost</th>
                                    <th>Status</th>
									<th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Stream Token</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Event</th>
                                    <th>Cost</th>
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
            $('#subscriptions_dt').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.subscription.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id"},
                    {"data": "stream_token"},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "type"},
                    {"data": "event"},
                    {"data": "cost"},
                    {"data": "status"},
                    {"data": "action", "orderable": false}
                ],

                "createdRow": function(row, data, dataIndex) {
                    $(row).find('td').css({
                        "text-align": "left",
                        "vertical-align": "middle"
                    });
                },
                "order": [[0, "desc"]]
            });
        </script>
@endsection
