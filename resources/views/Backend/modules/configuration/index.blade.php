@extends('Backend.includes.layout')
@section('content')
    <div id="accordion">
        @php($x=1)
        @if(is_array($config))
        @foreach($config as $key => $value)
        <div class="card card-border-nation">
            <div class="card-header bg-light" id="headingOne">
              <h3 class="mb-0 h6 card-title text-nation my-0" data-toggle="collapse" data-target="#{{$key}}" aria-expanded="true"
                  aria-controls="collapseOne">
                    {{$key}}
              </h3>
            </div>

            <div id="{{$key}}" class="collapse @if($x == 1)show @endif" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                  <form action="{{ route('configuration.edit') }}" method="post" class="form form form-horizontal create-form">
                      @csrf
                      @foreach($config[$key] as $ob => $val)
                      <div class="form-group">
                          <label for="{{ $ob }}" class="control-label">{{ $ob }}</label>
                          <input type="text" name="{{ $ob }}" id="{{ $ob }}" class="form-control" value="{{ $val }}">
                      </div>
                      @endforeach
                          <div class="form-group d-flex justify-content-end align-items-center">
                          <button type="submit" class="btn  btn-primary">
                              Save configuration
                          </button>
                          </div>
                  </form>
              </div>
            </div>
          </div>
            @php($x++)
        @endforeach
            @endif;
        </div>
@endsection
