@extends('includes.body')
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Categories</h5>
            <div class="actbtn">
                @if(\Illuminate\Support\Facades\Auth::user()->permission->contains('name','category.create'))
                <a href="{{ route('category.create') }}" class="btn btn-sm btn-outline-dark">
                   <i class="fas fa-plus"></i> Add Category
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="category-table">
                    <thead >
                    <tr >
                        <th>#</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Position</th>
                        <th>Description</th>
                        <th>Top Menu</th>

                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                    <tfoot >
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Position</th>
                            <th>Description</th>
                            <th>Top Menu</th>

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

    <script type="application/javascript">
        $('#category-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('category.datatable') }}",
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "pos" },
                { "data": "name" },
                { "data": "parent" },
                { "data": "position" },
                { "data": "description" },
                { "data": "top_menu"},
                { "data": "action" }

            ],
            "order": [[ 3, "asc" ]]
        });
    </script>
@endsection
