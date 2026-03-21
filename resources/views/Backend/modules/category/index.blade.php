
@extends('Backend.includes.layout')
@section('content')
<div class="col-12">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1">Category</h1>
            <div class="text-muted">Manage your category tree and drag siblings to reorder them.</div>
        </div>
        <div class="action-toolbar">
            @can('create_category')
                <a href="{{ route('backend.category.create') }}" class="btn  btn-dark-blue">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            @endcan
        </div>
    </div>
    <div class="card shadow-lg border">
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
        document.addEventListener('DOMContentLoaded', function () {
            new window.DataTable('#category-table', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.category.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "name"},
                    {"data": "parent"},
                    {"data": "position"},
                    {"data": "description"},
                    {"data": "top_menu"},
                    {"data": "action"}

                ],
                "order": [[3, "asc"]]
            });
        });
    </script>
@endsection
