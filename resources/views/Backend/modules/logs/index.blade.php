@extends('Backend.includes.layout')
@section('content')

    <div class="col-12">
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-1">Logs</h1>
        <div class="text-muted">Manage your logs.</div>
    </div>
</div>
                <div class="card shadow-lg border">



                    <div class="card-body">

                        <div class="table-responsive">
                        <table class="table table-striped " id="logger">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Excecutor</th>
                                <th>Model</th>
                                <th>Affected Id</th>
                                <th>Change</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Excecutor</th>
                                <th>Model</th>
                                <th>Affected Id</th>
                                <th>Change</th>
                                <th>Time</th>
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
            new window.DataTable('#logger',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.logs.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos", "orderable": false},
                    {"data": "action", "orderable": false},
                    {"data": "executer"},
                    {"data": "model", "orderable": false},
                    {"data": "affectedid"},
                    {"data": "change"},
                    {"data": "time"}

                ],
                "order": [[6, "desc"]]
            });
        });
    </script>

@endsection
