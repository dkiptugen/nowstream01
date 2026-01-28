@extends('Backend.includes.layout')
@section('content')

    <div class="col-12">
            <div class="table-responsive">
                <div class="card card-border-nation">



                    <div class="card-body">
                        <h3 class="my-0 card-title h5">Logs</h3>
                        <hr>
                        <div class="table-responsive">
                        <table class="table table-striped table-condensed" id="logger">
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

        </div>

@endsection
@section('header')


@endsection
@section('footer')
    <script>
        $('#logger').DataTable({
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
    </script>

@endsection
