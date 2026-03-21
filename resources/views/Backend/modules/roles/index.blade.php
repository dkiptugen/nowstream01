@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Roles</h1>
                <div class="text-muted">Manage your roles.</div>
            </div>
            <div class="actionbtn">
                @can('create_role')
                    <a class="btn btn-dark-blue" href="{{ route('backend.role.create') }}">
                        <i class="bx bx-plus"></i>Add Role
                    </a>
                @endcan
            </div>

        </div>
        <div class="card shadow-lg border" id="view-table" aria-labelledby="view-table">

            <div class="card-body">
                <div class="table-responsive w-100">
                    <table class="table table-striped " id="roles-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Access</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Access</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>


    </div>

@endsection
@section("header")

@endsection
@section("footer")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new window.DataTable('#roles-table', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.role.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id", "orderable": false},
                    {"data": "name"},
                    {"data": "access", "orderable": false},
                    {"data": "action", "orderable": false}
                ],
                "columnDefs": [{
                    "targets": 2, // Index of the column you want to add the class to (e.g., 'age' column)
                    "createdCell": function (td) {
                        td.style.display = 'flex';
                        td.style.flexWrap = 'wrap';
                    }
                }],
                "order": [[1, "asc"]]

            });
        });
    </script>
@endsection
