@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center pb-0">
                    <h3 class="card-title m-0 h5">Channel Streams</h3>

                </div>
                <hr>

            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="channelstreams_dt" class="table table-striped table-condensed">
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
	</div>

@endsection
@section('header')
@endsection
@section('footer')
    <script>
            $('#channelstreams_dt').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('channel.stream.datatable',$channel) }}",
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
