@extends('Backend.includes.layout')

@section('content')
    <div class="accordion" id="accordionExample">
        @php($x = 1)

        @if(is_array($config))
            @foreach($config as $key => $value)
                <div class="accordion-item  mb-2">

                    {{-- HEADER --}}
                    <h2 class="accordion-header px-2 py-1  bg-dark-blue text-white" id="heading-{{ $key }}">
                        <a
                            href="#"
                            class="accordion-button {{ $x !== 1 ? 'collapsed' : '' }} text-white text-decoration-none"

                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $key }}"
                            aria-expanded="{{ $x === 1 ? 'true' : 'false' }}"
                            aria-controls="collapse-{{ $key }}">
                            <span class="mb-0 text-white">{{ $key }}</span>
                        </a>
                    </h2>

                    {{-- BODY --}}
                    <div
                        id="collapse-{{ $key }}"
                        class="accordion-collapse collapse {{ $x === 1 ? 'show' : '' }} py-0"
                        aria-labelledby="heading-{{ $key }}"
                        data-bs-parent="#accordionExample">

                        <div class="accordion-body">
                            <div class="card my-0">
                                <div class="card-body">
                                    <form action="{{ route('backend.configuration.edit') }}" method="POST" class="form create-form">
                                        @csrf

                                        @foreach($config[$key] as $ob => $val)
                                            <div class="mb-3">
                                                <label for="{{ $ob }}" class="form-label">{{ $ob }}</label>
                                                <input
                                                    type="text"
                                                    name="{{ $ob }}"
                                                    id="{{ $ob }}"
                                                    class="form-control"
                                                    value="{{ $val }}">
                                            </div>
                                        @endforeach

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                Save configuration
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>

                @php($x++)
            @endforeach
        @endif
    </div>
@endsection
