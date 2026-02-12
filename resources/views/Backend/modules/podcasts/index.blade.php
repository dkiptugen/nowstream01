@extends('Backend.includes.layout')
@section('content')
    <div class="col">
        <div class="card card-border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title my-0 text-primary h5">
                    Podcasts
                </h3>
                <div class="actbtn">
                    @if(\Illuminate\Support\Facades\Auth::user()->can('create_podcast'))
                        <a href="{{ route('backend.podcast.create') }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-plus"></i> Add Podcast
                        </a>
                   @endif
                </div>
            </div>
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
        $('#podcast-table').DataTable({
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
                {"data": "action","orderable": false},

            ],
            "order": [[1, "asc"]]
        });
    </script>
@endsection
