@extends('Frontend.includes.layout')

@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <section>
            <div class="container">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-white">Success Alert</h6>
                                <div class="text-white">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-white">Error Alert</h6>
                                <div class="text-white">
                                    @foreach ($errors->all() as $error)
                                        <div>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mx-auto text-center card pt-3">
                        <h5>
                            Get Unlimited Streaming Access <br> to
                            {{ $event->event_name }} for <b>
                                @if($country == 'KE')
                                    KES
                                    {{ $rate->cost }}
                                @else
                                    {{ config('custom.BILLING.RESERVED_CURRENCY') . " " . $rate->reserved_currency_cost }}
                                @endif 

                           </b>
                        </h5>


                        <p class="mt-2">Please Select Your Payment Option</p>
                        <div class="card-body w-100 radius-10 mt-2">
                            <form action="{{ route('subscribe') }}" method="POST">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <input type="hidden" name="channel_id" value="{{ $event->channel_id }}">
                                <input type="hidden" name="cost" value="{{ $rate->cost }}">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="rate_id" value="{{ $rate->id }}">
                                <div class="card radius-10 border-primary border shadow-none">
                                    <label class="card-body" for="mpesa">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <img src="{{asset('frontend-assets/images/mpesa.png')}}" height="40"
                                                    alt="">
                                            </div>
                                            <div class="ms-auto">
                                                <input class="widgets-icons-2 bg-success text-white" type="radio"
                                                    name="payment_method_id" id="mpesa" value="1" checked>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="card radius-10 border-primary border shadow-none">
                                    <label class="card-body radius-10" for="creditcard">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <img src="{{asset('frontend-assets/images/card.png')}}" height="40"
                                                    alt="">
                                            </div>
                                            <div class="ms-auto">
                                                <input class="widgets-icons-2 bg-gradient-ibiza text-white" type="radio"
                                                    name="payment_method_id" id="creditcard" value="2">
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-danger w-100">Proceed to Pay</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endsection

        @section('header')
        @endsection

        @section('footer')
        
        
        
        @endsection