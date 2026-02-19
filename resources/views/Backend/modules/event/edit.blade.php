@extends('Backend.includes.layout')

@section('content')

<div class="row">
    <div class="col">
        <div class="card card-border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0 h5 text-primary">Edit Event</h3>
            </div>

```
        <div class="card-body">

            @php
                // If streams is a collection, get first
                $stream = $event->streams instanceof \Illuminate\Support\Collection
                    ? $event->streams->first()
                    : $event->streams;
            @endphp

            <form action="{{ route('backend.event.update', ['event' => $event->uuid]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="form form-horizontal create-form">

                @csrf
                @method('PUT')

                <!-- Event Name -->
                <div class="form-group">
                    <label class="control-label">Event Name</label>
                    <input type="text"
                           name="event_name"
                           class="form-control form-control-sm"
                           value="{{ old('event_name', $event->event_name) }}">
                </div>

                <!-- Description -->
                <div class="form-group mt-2">
                    <label class="control-label">Description</label>
                    <textarea name="event_description"
                              class="form-control editor"
                              rows="8">{{ old('event_description', $event->description) }}</textarea>
                </div>

                <!-- Images -->
                <div class="form-row mt-3">

                    <!-- Event Image -->
                    <div class="col-md-6 d-flex align-items-center">
                        <img src="{{ $event->event_image ?? asset('assets/img/default.png') }}"
                             height="60"
                             class="mr-3 border">

                        <div>
                            <label class="control-label">Event Image / Flyer</label>
                            <input type="file"
                                   name="thumbnail"
                                   class="form-control-file"
                                   accept="image/*">
                        </div>
                    </div>

                    <!-- Stream Thumbnail -->
                    <div class="col-md-6 d-flex align-items-center">
                        <img src="{{ optional($stream)->thumbnail_url ?? asset('assets/img/default.png') }}"
                             height="60"
                             class="mr-3 border">

                        <div>
                            <label class="control-label">Stream Thumbnail</label>
                            <input type="file"
                                   name="stream_thumbnail"
                                   class="form-control-file"
                                   accept="image/*">
                        </div>
                    </div>

                </div>

                <!-- Dates -->
                <div class="form-row mt-3">
                    <div class="col-md-6">
                        <label class="control-label">Publish Date</label>
                        <input type="text"
                               name="publishdate"
                               class="form-control datesingle"
                               value="{{ old('publishdate', $event->publish_date) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Event Time</label>
                        <input type="text"
                               name="event_time"
                               class="form-control datetimes"
                               value="{{ old('event_time', optional($event->start_time)->format('Y/m/d h:i A').' - '.optional($event->end_time)->format('Y/m/d h:i A')) }}">
                    </div>
                </div>

                <!-- Venue -->
                <div class="form-group mt-3">
                    <label class="control-label">Venue</label>
                    <input type="text"
                           name="venue"
                           class="form-control"
                           value="{{ old('venue', $event->venue) }}">
                </div>

                <!-- Options -->
                <div class="form-group mt-2">

                    <label class="form-check form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               name="featured"
                               value="1"
                               {{ old('featured', $event->featured) ? 'checked' : '' }}>
                        <span class="form-check-label">Is Featured</span>
                    </label>

                    <label class="form-check form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               name="has_stream"
                               value="1"
                               {{ $stream ? 'checked' : '' }}>
                        <span class="form-check-label">Has Stream</span>
                    </label>

                </div>

                <!-- Submit -->
                <div class="form-group d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Update Event
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
```

</div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection
