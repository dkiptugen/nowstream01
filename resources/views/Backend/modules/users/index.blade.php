@extends('Backend.includes.layout')
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h5 my-0">Users</h1>
                <div class="actionbtn">
                    <a href="{{ route('user.create') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-plus"></i> Add User
                    </a>
    
                </div>
            </div>
            <hr>
            
            <div class="table-responsive">
                <table id="userstable" class="table table-striped table-hover table-condensed">
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
        $('#userstable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('user.datatable') }}",
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "id","orderable":false },
                { "data": "name" },
                { "data": "email" },
                { "data": "status","orderable":false },
                { "data": "role","orderable":false },
                { "data": "action","orderable":false }
            ],
            "order": [[ 1, "asc" ]]


        });
    </script>
@endsection
