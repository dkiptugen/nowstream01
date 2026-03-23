@extends('Backend.includes.layout')
@section('content')


    <div class="row">
        <div class="col">
            <div class="card card-border-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="h5 my-0 card-title text-primary">{{ $event->event_name }} Rates</h3>
                    @can('create_event_rate')
                        <a href="{{ route('backend.event.rates.create',$event->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Event Rate
                        </a>
                    @endcan
                </div>
            	<div class="card-body">
                    <div class="table-responsive mt-3">
							<table id="rates_dt" class="table table-striped ">
								<thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Cost</th>
                                    <th>{{ config('custom.BILLING.RESERVED_CURRENCY') }} Cost</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Special Offer</th>
                                    <th>Status</th>
                                    <th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Cost</th>
                                    <th>{{ config('custom.BILLING.RESERVED_CURRENCY') }} Cost</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Special Offer</th>
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
            new window.DataTable('#rates_dt', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.event.rate.datatable', $event->id) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "name"},
                    {"data": "cost"},
                    {"data": "reserved_currency_cost"},
                    {"data": "date_from"},
                    {"data": "date_to"},
                    //{"data": "created_by"},
                    {"data": "is_special_offer"},
                    {"data": "status"},
                    {"data": "action", "orderable": false, "searchable": false}
                ],
                "createdRow": function(row) {
                    row.querySelectorAll('td').forEach(function (cell) {
                        cell.style.textAlign = 'left';
                        cell.style.verticalAlign = 'middle';
                    });
                },
                "order": [[4, "desc"]]
            });
        });
    </script>
@endsection
