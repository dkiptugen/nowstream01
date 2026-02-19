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
                        <div class="form-group">

                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="featured" value="1">
                                <span class="form-check-label">Is Featured</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_stream" value="1" id="hasStream">
                                <span class="form-check-label">Has Streams?</span>
                            </label>
                            <!-- Checkbox to indicate if tickets are available -->
                            <div class="form-check form-check-inline">
                                <input type="checkbox" id="hasTickets" class="form-check-input">
                                <label for="hasTickets" class="form-check-label">Has Tickets?</label>
                            </div>

                        </div>
                        <div class="form-group mt-4">
                            <button type="button" id="addTicketBtn" class="btn text-dark btn-link  text-decoration-none mb-3 text-nowrap"  style="display:none;">
                                <i class="fas fa-plus"></i>
                                Add Ticket
                            </button>
                            <div id="ticketsContainer"></div>
                        </div>
                        <div class="form-group">
                            <!-- Add Row button -->
                            <button type="button" id="addStreamBtn" class="btn text-dark text-decoration-none btn-link mb-3 text-nowrap" style="display:none;"> <i class="fas fa-plus"></i>Add Stream Price</button>

                            <!-- Container for stream pricing rows -->
                            <div id="streamsContainer"></div>
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

            // Toggle Add Ticket button based on checkbox
            $('#hasTickets').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#addTicketBtn').show();
                } else {
                    $('#addTicketBtn').hide();
                    $('#ticketsContainer').empty(); // remove all ticket rows if unchecked
                }
            });

            // Add a new ticket row
            $('#addTicketBtn').on('click', function() {
                let container = $('#ticketsContainer');

                let row = $(`
            <div class="form-group form-row border p-2 mb-2">
                <div class="col">
                    <label class="control-label">Ticket Type</label>
                    <input type="text" name="ticket[type][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Quantity</label>
                    <input type="number" name="ticket[quantity][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Currency</label>
                    <input type="text" name="ticket[currency][]" class="form-control">
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

                container.append(row);
            });

            // Remove a ticket row
            $('#ticketsContainer').on('click', '.removeTicketBtn', function() {
                $(this).closest('.form-group').remove();
            });


            // Toggle add button when has_stream is checked
            $('#hasStream').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#addStreamBtn').show();
                } else {
                    $('#addStreamBtn').hide();
                    $('#streamsContainer').empty(); // remove all rows if unchecked
                }
            });

            // Add a new stream pricing row
            $('#addStreamBtn').on('click', function() {
                let container = $('#streamsContainer');

                let row = $(`
            <div class="form-group form-row border p-2 mb-2">
                <div class="col">
                    <label class="control-label">Rate Name</label>
                    <input type="text" name="stream[rate_name][]" class="form-control">
                </div>
                <div class="col">
                    <label class="control-label">Currency</label>
                    <input type="text" name="stream[currency][]" class="form-control">
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

                container.append(row);
            });

            // Remove a stream pricing row
            $('#streamsContainer').on('click', '.removeStreamBtn', function() {
                $(this).closest('.form-group').remove();
            });

        });
    </script>
@endsection
