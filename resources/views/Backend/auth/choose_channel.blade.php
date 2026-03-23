@extends('Backend.auth.layout')

@section('content')
    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">



            <div class="card">
                <div class="card-body">
                    <div class="m-sm-4">
                        <div class="text-center">
                            <img src="{{ $logo }}" width="154" alt="">
                        </div>
                        <form  method="POST" action="{{ route('backend.select_brand') }}">
                            @csrf
                            <div class="form-group">
                                <label for="channel">{{ __('Select Brand') }}</label>
                                <select class="form-select" name="microsite"  id="microsite"  autocomplete="microsite"  >
                                    @foreach($product as $value)
                                        <option value="{{ $value->microsite->uuid }}">{{ $value->microsite->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                Don't have a brand yet? <a href="{{ route('backend.create_brand') }}">Create One</a>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn w-100 btn-info">Proceed</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @if($message = Session::get('error'))
                <div class="alert alert-danger ">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
    </div>




@endsection
