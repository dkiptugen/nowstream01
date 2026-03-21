@extends('Backend.includes.layout')

@section('content')
    <div class="col">
    <div class="card card-border-primary">
        <div class="card-header">
            <h1 class="card-title my-0 h5 text-primary">Edit Event event for : {{ $eventRate->name }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('backend.event.rates.update', [ $eventRate->event->id, $eventRate->id]) }}" class="form form-horizontal create-form" method="post">
                @csrf
                @method('PUT') <!-- Use PUT method for update -->

                <input type="hidden" name="event_id" value="{{ $eventRate->id }}">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $eventRate->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cost">Cost</label>

                    <input type="text" name="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $eventRate->cost) }}">

                    @error('cost')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">

                    <label for="dollar_cost">Dollar Cost</label>
                    <input type="text" name="reserved_currency_cost"
                           class="form-control @error('reserved_currency_cost') is-invalid @enderror"
                           value="{{ old('reserved_currency_cost', $eventRate->reserved_currency_cost) }}">

                    @error('reserved_currency_cost')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{$eventRate->date_from}}
                <div class="form-group">
                    <label for="date_from">Date From</label>
                    <input type="datetime-local" name="date_from"
                           class="form-control @error('date_from') is-invalid @enderror"
                           value="{{ old('date_from', $eventRate->date_from ? $eventRate->date_from->format('Y-m-d\TH:i') : '') }}">
                    @error('date_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_to">Date To</label>
                    <input type="datetime-local" name="date_to"
                           class="form-control @error('date_to') is-invalid @enderror"
                           value="{{ old('date_to', $eventRate->date_to ? $eventRate->date_to->format('Y-m-d\TH:i') : '') }}">
                    @error('date_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="0" {{ old('status', $eventRate->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        <option value="1" {{ old('status', $eventRate->status) == '1' ? 'selected' : '' }}>Active</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Event Rate</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection
