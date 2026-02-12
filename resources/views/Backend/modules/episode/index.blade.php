@extends('includes.body')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">{{ $podcast->title }} Episodes</h5>
                <div class="actbtn">
                    @can('create_podcast_episode')
                        <a href="{{ route('podcast.episodes.create',['podcast'=>$podcast->id]) }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-plus"></i> Add Podcast
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="podcast-table">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Link</th>
                                <th>Description</th>
                                <th>Keywords</th>
                                <th>Content Rating</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                        <tfoot >

                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Link</th>
                                <th>Description</th>
                                <th>Keywords</th>
                                <th>Content Rating</th>
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
            "ajax":{
                "url": "{{ route('podcast.episodes.datatable',$podcast->id) }}",
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                { "data": "pos" },
                { "data": "title" },
                { "data": "thumbnail" },
                { "data": "category" },
                { "data": "link" },
                { "data": "description" },
                { "data": "keywords" },
                { "data": "content_rating" },

                { "data": "status"  },
                { "data": "action" }

            ],
            "order": [[ 1, "asc" ]]
        });
    </script>
@endsection

