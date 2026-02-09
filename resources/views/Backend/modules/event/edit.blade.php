@extends('Backend.includes.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-border-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0 h5 text-primary">Edit Event</h3>
                </div>
                <div class="card-body">


                    <form action="{{ route('backend.event.update',['event'=>$event->uuid]) }}" class="form form-horizontal create-form" enctype="multipart/form-data" method="post">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="event_name" class="control-label"> Event Name</label>
                            <input type="text" name="event_name" id="event_name" class="form-control form-control-sm" value="{{ $event->event_name }}">
                        </div>
                        <div class="form-group mt-2">
                            <label for="event_description" class="control-label">Description</label>
                            <textarea name="event_description" id="event_description" class="form-control editor" rows="10">{{ $event->description }}</textarea>
                        </div>
                        <div class="form-group form-row">
                            <div class="col d-flex align-items-center">
                                <img src="{{ $event->event_image }}" class="mr-2" height="50">
                                <span>
                                    <label for="thumbnail" class="control-label">Event Image/ Flyer</label>
                                <input type="file" name="thumbnail" id="thumbnail_image" class="form-control-file"  accept="image/*">
                                </span>

                            </div>
                            <div class="col d-flex align-items-center">
                                <img src="{{ optional($event->streams)->thumbnail_url }}" class="mr-2" height="50">
                                <span>
                                    <label for="stream_thumbnail" class="control-label">Stream Thumbnail</label>
                                    <input type="file" name="stream_thumbnail" id="stream_thumbnail" class="form-control-file" accept="image/*">
                                </span>

                            </div>

                        </div>
                        <div class="form-group form-row">
                            <div class="col">
                                <label for="publishdate" class="control-label">Publish Date</label>
                                <input type="text" name="publishdate" id="publishdate" class="form-control datesingle" value="{{ $event->publishdate }}">
                            </div>
                            <div class="col">
                                <label for="event_time" class="control-label"> Event Time</label>
                                <input type="text" name="event_time" id="event_time" class="form-control datetimes" value="{{ $event->start_time.' - '.$event->end_time }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue" class="control-label"> Venue</label>
                            <input type="text" name="venue" id="venue" class="form-control" value="{{ $event->venue }}">
                        </div>
                        <div class="form-group">

                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="featured" value="1" @if($event->featured) selected @endif>
                                <span class="form-check-label">
                                Is Featured
                            </span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_stream" value="1">
                                <span class="form-check-label">Has Stream</span>
                            </label>

                        </div>

                        <div class="form-group d-flex justify-content-end mt-2">
                            <button type="submit" name="" id="" class="btn btn-sm btn-primary">Edit Event</button>
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
@endsection
