<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head class="dark-theme">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        
@include('Frontend.includes.components.partials.audio-player')
    </div>
    @vite('resources/js/app.js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.custom-carousel').forEach(carousel => {

        const track = carousel.querySelector('.carousel-track');
        const items = carousel.querySelectorAll('.carousel-item');
        const prevBtn = carousel.querySelector('.prev');
        const nextBtn = carousel.querySelector('.next');

        if (!track || items.length === 0) return;

        let index = 0;
        let itemsPerView = 4;
        let autoplay = carousel.dataset.autoplay === "true";
        let intervalTime = parseInt(carousel.dataset.interval) || 4000;
        let autoTimer;

        function getItemsPerView() {
            const w = window.innerWidth;
            if (w < 576) return 1;
            if (w < 768) return 2;
            if (w < 992) return 3;
            return 5;
        }

        function updateLayout() {
            itemsPerView = getItemsPerView();
            const itemWidth = carousel.offsetWidth / itemsPerView;

            items.forEach(item => {
                item.style.width = itemWidth + 'px';
            });

            moveTo(index);
        }

        function moveTo(i) {
            const maxIndex = Math.max(0, items.length - itemsPerView);
            index = Math.max(0, Math.min(i, maxIndex));

            const itemWidth = items[0].offsetWidth;
            track.style.transform = `translateX(-${index * itemWidth}px)`;

            updateOverlay();
        }

        function updateOverlay() {
            items.forEach(item => item.classList.remove('edge-overlay'));

            if (window.innerWidth < 992) return;

            const start = index;
            const end = index + itemsPerView - 1;

            if (items[start]) items[start].classList.add('edge-overlay');
            if (items[start + 1]) items[start + 1].classList.add('edge-overlay');
            if (items[end]) items[end].classList.add('edge-overlay');
            if (items[end - 1]) items[end - 1].classList.add('edge-overlay');
        }

        prevBtn.addEventListener('click', () => moveTo(index - 1));
        nextBtn.addEventListener('click', () => moveTo(index + 1));

        /* Autoplay */
        function startAutoplay() {
            if (!autoplay) return;
            autoTimer = setInterval(() => {
                const maxIndex = items.length - itemsPerView;
                index >= maxIndex ? moveTo(0) : moveTo(index + 1);
            }, intervalTime);
        }

        function stopAutoplay() {
            clearInterval(autoTimer);
        }

        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);

        window.addEventListener('resize', updateLayout);

        updateLayout();
        startAutoplay();
    });

});
</script>
</body>
</html>
