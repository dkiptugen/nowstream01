@extends('Backend.includes.layout')
@section('content')
    
    <div class="col">
        <div class="card card-border-primary">
            <div class="card-header">
                <h1 class="card-title my-0 h5 text-primary">Create Event Rate for : {{ $event->event_name }}</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('event.rates.store', ['event' => $event->id]) }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cost">Cost</label>
                        <input type="text" name="cost" class="form-control @error('cost') is-invalid @enderror"
                               value="{{ old('cost') }}">
                        @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reserved_currency_cost">Dollar Cost</label>
                        <input type="text" name="reserved_currency_cost"
                               class="form-control @error('reserved_currency_cost') is-invalid @enderror"
                               value="{{ old('reserved_currency_cost') }}">
                        @error('reserved_currency_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date_from">Date From</label>
                        <input type="datetime-local" name="date_from"
                               class="form-control @error('date_from') is-invalid @enderror"
                               value="{{ old('date_from') }}">
                        @error('date_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To</label>
                        <input type="datetime-local" name="date_to"
                               class="form-control @error('date_to') is-invalid @enderror" value="{{ old('date_to') }}">
                        @error('date_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Event Rate</button>
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
