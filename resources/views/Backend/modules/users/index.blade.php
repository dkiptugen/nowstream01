@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <h1 class="h3 ">Users</h1>
                <div class="text-muted">Manage your users.</div>
            </div>
            <div class="actionbtn">
                @can('create_user')
                    <a href="{{ route('backend.user.create') }}" class="btn btn-dark-blue">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                @endcan
            </div>
        </div>
        <div class="card shadow-lg border">
            <div class="card-body">


                <div class="table-responsive">
                    <table id="userstable" class="table table-striped table-hover ">
                        <thead>
                        <tr>
                            <th>*</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>*</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
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
            new window.DataTable('#userstable', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.user.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id", "orderable": false},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "status", "orderable": false},
                    {"data": "role", "orderable": false},
                    {"data": "action", "orderable": false}
                ],
                "order": [[1, "asc"]]


            });
        });
    </script>
@endsection
