@extends('Backend.includes.layout')
@section('content')
    <div class="container-fluid">
        <div class="card card-border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0 h5 text-primary">Microsites</h3>
                <div class="actbtn">
                    @can('create_microsite')
                        <a href="{{ route('backend.microsite.create') }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-plus"></i> Add Microsite
                        </a>
                    @endcan
                </div>
            </div>
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
        $('#microsite-dt').DataTable({
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
    </script>
@endsection
