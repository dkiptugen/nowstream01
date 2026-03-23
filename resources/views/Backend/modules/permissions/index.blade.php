@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="card card-border-blue">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title my-0 text-blue">Permissions</h3>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-striped " id="permissions-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Permission</th>
                                <th>Group</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Permission</th>
                                <th>Group</th>
                                <th>Roles</th>
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
            new window.DataTable('#permissions-table', {
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ route('backend.role.permission.datatable',$userid??0) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "pos","orderable": false },
                    { "data": "display_name" },
                    { "data": "name" },
                    { "data": "group" },
                    { "data": "roles","orderable":false },
                    { "data": "action","orderable":false }
                ],
                "order": [[ 1, "asc" ]]
            });
        });
    </script>
@endsection
