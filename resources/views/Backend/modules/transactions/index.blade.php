@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Transactions</h1>
                    <div class="text-muted">Manage your transactions.</div>
                </div>

            </div>
            <div class="card shadow-lg border">


            	<div class="card-body">
                    <div class="table-responsive w-100">
							<table id="transactions_dt" class="table table-striped ">
								<thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt</th>
                                        <th>Payment Method</th>
                                        <th>Event</th>
                                        <th>Cost</th>
                                        <th>Amount Paid</th>
                                        <th>Balance</th>
                                        <th>Date Paid</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
									</tr>
								</thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt</th>
                                        <th>Payment Method</th>
                                        <th>Event</th>
                                        <th>Cost</th>
                                        <th>Amount Paid</th>
                                        <th>Balance</th>
                                        <th>Date Paid</th>
                                        <th>Name</th>
                                        <th>Email</th>
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
            new window.DataTable('#transactions_dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.transaction.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id"},
                    {"data": "receipt"},
                    {"data": "payment_method"},
                    {"data": "event"},
                    {"data": "cost"},
                    {"data": "amount_paid"},
                    {"data": "balance"},
                    {"data": "date_paid"},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "status"},
                    {"data": "action", "orderable": false}
                ],

                "createdRow": function (row) {
                    row.querySelectorAll('td').forEach(function (cell) {
                        cell.style.textAlign = 'left';
                        cell.style.verticalAlign = 'middle';
                    });
                },
                "order": [[0, "desc"]]
            });
        });
        </script>
@endsection
