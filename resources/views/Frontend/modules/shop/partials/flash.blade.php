@if(session('success'))
    <div class="shop-flash shop-flash--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="shop-flash shop-flash--error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="shop-flash shop-flash--error">
        {{ $errors->first() }}
    </div>
@endif
