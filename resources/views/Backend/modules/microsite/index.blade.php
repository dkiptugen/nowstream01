@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Brands</h1>
                <div class="text-muted">Manage your brands.</div>
            </div>
            <div class="action-toolbar">
                @can('create_microsite')
                    <a href="{{ route('backend.microsite.create') }}" class="btn btn-dark-blue">
                        <i class="fas fa-plus"></i> Add Brand
                    </a>
                @endcan
            </div>
        </div>
        <div class="card shadow-lg border">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="microsite-dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Banner</th>
                            <th>Cover</th>
                            <th>Description</th>
                            <th>Keywords</th>
                            <th>Social Links</th>
                            <th>Views</th>
                            <th>Followers</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Banner</th>
                            <th>Cover</th>
                            <th>Description</th>
                            <th>Keywords</th>
                            <th>Social Links</th>
                            <th>Views</th>
                            <th>Followers</th>
                            <th>Status</th>
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
            new window.DataTable('#microsite-dt',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.microsite.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "name"},
                    {"data": "domain"},
                    {"data": "banner"},
                    {"data": "cover"},
                    {"data": "description"},
                    {"data": "keywords"},
                    {"data": "social_links"},
                    {"data": "views"},
                    {"data": "followers"},
                    {"data": "status"},
                    {"data": "action", "orderable": false},

                ],
                "order": [[1, "asc"]]
            });
        });
    </script>
@endsection
