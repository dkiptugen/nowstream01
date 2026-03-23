@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Payment Methods</h1>
                <div class="text-muted">Manage your payment methods.</div>
            </div>
            <div class="action-toolbar">
                @can('create_payment_method')
                    <a href="{{ route('backend.payment_method.create') }}" class="btn btn-dark-blue">
                        <i class="fas fa-plus"></i>
                        Add Payment Method
                    </a>
                @endcan
            </div>
        </div>
        <div class="card shadow-lg border">

            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-striped " id="payment-method-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Identifier</th>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Notifying</th>
                                <th>Creator</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Identifier</th>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Notifying</th>
                                <th>Creator</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("footer")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
           new window.DataTable('#payment-method-table',{
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('backend.payment_method.datatable') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "pos"},
                    {"data": "identifier"},
                    {"data": "provider"},
                    {"data": "status"},
                    {"data": "notify"},
                    {"data": "creator"},
                    {"data": "date_created"},
                    {"data": "action", "orderable": false}
                ],
                "order": [[1, "asc"]]
            });
        });
        // Initialize Notify (example with Notyf-style)
        const notify = new Notyf({
            duration: 1000,
            position: { x: 'right', y: 'top' }
        });

        // Event delegation (replacement for $(document).on)
        document.addEventListener('change', async function (e) {
            if (!e.target.classList.contains('shortcode-notify')) return;

            const chk = e.target;

            if (chk.checked) {
                try {
                    const res = await fetch("{{ route('mpesa.notify') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            identifier: chk.dataset.shortcode
                        })
                    });

                    const Mess = await res.json();

                    if (Mess) {
                        notify.success('Notification started successfully.');
                    } else {
                        notify.error('Notification failed to start.');

                        // revert checkbox
                        chk.checked = false;
                    }

                } catch (e) {
                    console.error(e);

                    notify.error(
                        e?.responseJSON?.message || 'Something went wrong'
                    );

                    // revert checkbox
                    chk.checked = false;
                }
            }
        });
    </script>
@endsection
