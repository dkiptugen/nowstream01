@extends('Backend.includes.layout')
@section('content')
    <div class="col">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Podcasts</h1>
                <div class="text-muted">Manage your podcasts.</div>
            </div>
            <div class="action-toolbar">
                @can('create_podcast')
                    <a href="{{ route('backend.podcast.create') }}" class="btn btn-dark-blue">
                        <i class="fas fa-plus"></i>
                        Add Podcast
                    </a>
                @endcan
            </div>
        </div>
        <div class="card shadow-lg border">

            <div class="card-body">
                <div class="w-100 table-responsive">
                    <table class="table table-striped table-hover " id="podcast-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Keywords</th>
                            <th>Source</th>
                            <th>Episodes</th>
                            <th>Publish date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Keywords</th>
                            <th>Source</th>
                            <th>Episodes</th>
                            <th>Publish date</th>
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
            new window.DataTable('#podcast-table', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.podcast.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "title"},
                    {"data": "thumbnail"},
                    {"data": "description"},
                    {"data": "category"},
                    {"data": "keywords"},
                    {"data": "source"},
                    {"data": "episodes"},
                    {"data": "publishdate"},
                    {"data": "status"},
                    {"data": "action", "orderable": false},

                ],
                "order": [[1, "asc"]]
            });
        });
    </script>
@endsection
