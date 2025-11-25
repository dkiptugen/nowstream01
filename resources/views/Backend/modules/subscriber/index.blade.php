@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="card card-border-nation">
            <div class="card-header d-flex justify-content-between align-itens-center">
                <h3 class="card-title my-0 text-nation">Subscribers</h3>
                <a href="{{ route('product.subscriber.create',0) }}" class="btn btn-sm btn-outline-nation">
                    <i class="fas fa-plus"></i>Add Subscribers
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-hover" id="subscriber-table">
                        <thead class="bg-nation text-white">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Subscriptions</th>
                                <th>Status</th>
                                <th>Notifying</th>
                                <th>Last Login</th>
                                <th>Registration Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot class="bg-nation text-white">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Subscriptions</th>
                                <th>Status</th>
                                <th>Notifying</th>
                                <th>Last Login</th>
                                <th>Registration Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        $('#subscriber-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('product.subscriber.datatable',0) }}",
                "dataType": "json",
                "type": "POST",
                "data": {_token: "{{csrf_token()}}"}
            },
            "columns": [
                {"data": "pos"},
                {"data": "name"},
                {"data": "email"},
                {"data": "company"},
                {"data": "subscriptions"},
                {"data": "status"},
                { "data": "notify" },
                {"data": "last_login"},
                {"data": "registration"},
                {"data": "action","orderable":false}
            ],
            "order": [[1, "asc"]]
        });
    </script>
@endsection
