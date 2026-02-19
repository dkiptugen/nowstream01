@extends('Backend.includes.layout')
@section('content')


    <div class="row">
        <div class="col">
            <div class="card card-border-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0 h5 text-primary">Add Event</h3>
                </div>
                <div class="card-body">


                    <form action="{{ route('backend.event.store') }}" class="form form-horizontal create-form" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="event_name" class="control-label"> Event Name</label>
                            <input type="text" name="event_name" id="event_name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mt-2">
                            <label for="event_description" class="control-label">Description</label>
                            <textarea name="event_description" id="event_description" class="form-control editor" rows="10"></textarea>
                        </div>
                        <div class="form-group form-row">
                            <div class="col">
                                <label for="thumbnail" class="control-label">Event Image/ Flyer</label>
                                <input type="file" name="thumbnail" id="thumbnail_image" class="form-control-file"  accept="image/*">

                            </div>
                            <div class="col">
                                <label for="stream_thumbnail" class="control-label">Stream Thumbnail</label>
                                <input type="file" name="stream_thumbnail" id="stream_thumbnail" class="form-control-file" accept="image/*">
                            </div>

                        </div>
                        <div class="form-group form-row">
                            <div class="col">
                                <label for="publishdate" class="control-label">Publish Date</label>
                                <input type="text" name="publishdate" id="publishdate" class="form-control datesingle">
                            </div>
                            <div class="col">
                                <label for="event_time" class="control-label"> Event Time</label>
                                <input type="text" name="event_time" id="event_time" class="form-control datetimes">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue" class="control-label"> Venue</label>
                            <input type="text" name="venue" id="venue" class="form-control">
                        </div>



                        <!-- TICKETS -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" id="hasTickets" class="form-check-input">
                                <label for="hasTickets" class="form-check-label">Has Tickets?</label>
                            </div>
                            <button type="button" id="addTicketBtn" class="btn btn-primary mb-2" style="display:none;">Add Ticket</button>
                            <div id="ticketsContainer"></div>
                        </div>

                        <!-- STREAMS -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" id="hasStream" class="form-check-input">
                                <label for="hasStream" class="form-check-label">Has Stream?</label>
                            </div>
                            <button type="button" id="addStreamBtn" class="btn btn-primary mb-2" style="display:none;">Add Stream Price</button>
                            <div id="streamsContainer"></div>
                        </div>

                        <!-- MERCHANDISE -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" id="hasMerch" class="form-check-input">
                                <label for="hasMerch" class="form-check-label">Has Merchandise?</label>
                            </div>
                            <button type="button" id="addMerchBtn" class="btn btn-primary mb-2" style="display:none;">Add Merchandise</button>
                            <div id="merchContainer"></div>
                        </div>
                        <div class="form-group">

                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="featured" value="1">
                                <span class="form-check-label">Is Featured</span>
                            </label>

                        </div>

                        <div class="form-group d-flex justify-content-end mt-2">
                            <button type="submit" name="" id="" class="btn btn-sm btn-primary">Add Event</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('header')
@endsection
@section('footer')

    <script>
        $(document).ready(function() {

            // Default currency
            const defaultCurrency = 'USD';

            /*** TICKETS ***/
            $('#hasTickets').on('change', function() {
                $('#addTicketBtn').toggle(this.checked);
                if (!this.checked) $('#ticketsContainer').empty();
            });

            $('#addTicketBtn').on('click', function() {
                let index = $('#ticketsContainer .form-group').length + 1;
                let row = $(`
            <div class="form-group form-row border p-2 mb-2">
                <div class="col">
                    <label class="control-label">Ticket #${index} Type</label>
                    <input type="text" name="ticket[type][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Quantity</label>
                    <input type="number" name="ticket[quantity][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Currency</label>
                    <input type="text" name="ticket[currency][]" class="form-control" value="${defaultCurrency}">
                </div>
                <div class="col">
                    <label class="control-label">Cost</label>
                    <input type="number" name="ticket[cost][]" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger removeTicketBtn mt-4">Remove</button>
                </div>
            </div>
        `);
                $('#ticketsContainer').append(row);
            });

            $('#ticketsContainer').on('click', '.removeTicketBtn', function() {
                $(this).closest('.form-group').remove();
            });


            /*** STREAMS ***/
            $('#hasStream').on('change', function() {
                $('#addStreamBtn').toggle(this.checked);
                if (!this.checked) $('#streamsContainer').empty();
            });

            $('#addStreamBtn').on('click', function() {
                let index = $('#streamsContainer .form-group').length + 1;
                let row = $(`
            <div class="form-group form-row border p-2 mb-2">
                <div class="col">
                    <label class="control-label">Stream #${index} Rate Name</label>
                    <input type="text" name="stream[rate_name][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Currency</label>
                    <input type="text" name="stream[currency][]" class="form-control" value="${defaultCurrency}">
                </div>
                <div class="col">
                    <label class="control-label">Price</label>
                    <input type="number" name="stream[price][]" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger removeStreamBtn mt-4">Remove</button>
                </div>
            </div>
        `);
                $('#streamsContainer').append(row);
            });

            $('#streamsContainer').on('click', '.removeStreamBtn', function() {
                $(this).closest('.form-group').remove();
            });


            /*** MERCHANDISE ***/
            $('#hasMerch').on('change', function() {
                $('#addMerchBtn').toggle(this.checked);
                if (!this.checked) $('#merchContainer').empty();
            });

            $('#addMerchBtn').on('click', function() {
                let index = $('#merchContainer .form-group').length + 1;
                let row = $(`
            <div class="form-group form-row border p-2 mb-2">
                <div class="col">
                    <label class="control-label">Merch #${index} Name</label>
                    <input type="text" name="merch[name][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Currency</label>
                    <input type="text" name="merch[currency][]" class="form-control" value="${defaultCurrency}">
                </div>
                <div class="col">
                    <label class="control-label">Price</label>
                    <input type="number" name="merch[price][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Image</label>
                    <input type="file" name="merch[image][]" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger removeMerchBtn mt-4">Remove</button>
                </div>
            </div>
        `);
                $('#merchContainer').append(row);
            });

            $('#merchContainer').on('click', '.removeMerchBtn', function() {
                $(this).closest('.form-group').remove();
            });

        });
    </script>
@endsection
