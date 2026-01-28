@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">


            <div class="card card-border-blue" id="view-table" aria-labelledby="view-table" >
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title my-0 text-blue">Roles</h5>
                    <a class="btn btn-secondary btn-sm"  href="{{ route('role.create') }}" >
                        <i class="bx bx-plus"></i>Add Role
                    </a>

                </div>
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
        $(document).ready(function() {
            $('#roles-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.role.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id","orderable":false},
                    {"data": "name"},
                    {"data": "access","orderable":false},
                    {"data": "action","orderable":false}
                ],
                "columnDefs": [{
                    "targets": 2, // Index of the column you want to add the class to (e.g., 'age' column)
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'display': 'flex',
                            'flex-wrap':'wrap'

                        });

                    }
                }],
                "order": [[1, "asc"]]

            });
        });
    </script>
@endsection
