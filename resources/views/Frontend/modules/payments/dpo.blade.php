@extends('Frontend.includes.layout')
@section('content')
	<section class="full-page-iframe">
    <div class="container">
        <div class="row">
            <iframe src="{{ $checkout->iframe }}" ></iframe>
        </div>
    </div>
</section>


@endsection
@section('header')
	<style>
    .full-page-iframe {
	    position: relative;
	    width: 100vw;
	    height: 80vh;
	    margin: 0;
	    padding: 0;
    }
    
    .full-page-iframe .container, .full-page-iframe .row {
	    width: 80%;
	    height: 80%;
	    margin: 0;
	    padding: 0;
    }
    
    .full-page-iframe iframe {
	    position: absolute;
	    top: 7vh;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    border: none; /* Optional: Remove iframe border */
    }
</style>

@endsection
@section('footer')
@endsection