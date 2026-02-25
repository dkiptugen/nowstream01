<!--end page wrapper -->

<div class="overlay toggle-icon"></div>
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

<div class="d-md-none my-2">
	<p>.</p>
</div>
<footer class="page-footer">

    <ul class="d-flex justify-content-between px-0 d-md-none">
        <li class="nav-item">
            <a class="nav-link" href=" {{ url('/') }}">
                <div class="parent-icon"><i class="bx bx-home"></i>
                </div>
                <div class="menu-title">Home</div>
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" href=" {{ url('events') }}">
                <div class="parent-icon"><i class="bx bx-calendar"></i>
                </div>
                <div class="menu-title">Events</div>
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" href=" {{ url('all-videos') }}">
                <div class="parent-icon"><i class="bx bx-video"></i>
                </div>
                <div class="menu-title">Videos</div>
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" href=" {{ url('channels') }}">
                <div class="parent-icon"><i class='bx bx-tv'></i>
                </div>
                <div class="menu-title">Channels</div>
            </a>

        </li>

        <li class="nav-item">
            <a class="nav-link" href=" {{ url('streams') }}">
                <div class="parent-icon"><i class="bx bx-video-recording"></i>
                </div>
                <div class="menu-title">Streams</div>
            </a>

        </li>
        <div class="user-box dropdown px-3">
            <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @guest
                <a href="{{ route('user.login') }}">
                <img src="{{ asset('avatar.png') }}" class="user-img" alt="user avatar">
                <div class="user-info">
                    <p class="user-name mb-0">Login</p>
                </div>
            </a>
                @else

                <img src="{{ Auth::user()->image ??  asset('avatar.png')}} " class="user-img" alt="user avatar">
                <div class="user-info">
                    <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                </div>
                @endguest
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                @guest
                @if (Route::has('login'))
                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('user.login') }}"><i class="bx bx-log-in-circle fs-5"></i><span>Login</span></a></li>
                @endif
                @if (Route::has('register'))
                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('user.register') }}"><i class="bx bx-user-plus fs-5"></i><span>Register</span></a></li>
                @endif
                @else
                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                <li>
                    <div class="dropdown-divider mb-0"></div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('user.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out-circle"></i><span>Logout</span>
                    </a>
                </li>
                @endguest
            </ul>
        </div>

        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
    <p class="mb-0 d-none d-md-block">Copyright Streamer © 2024. All rights reserved.</p>
</footer>
</div>
<!--end wrapper-->

<!-- search modal -->
<div class="modal pt-5" id="SearchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header gap-2">
                <div class="position-relative popup-search w-100">
                    <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
                    <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
                </div>
                <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search-list">
                    <p class="mb-1 mt-3">Your Search Results</p>
                    <div class="list-group">
                        <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Yes yes yes</a>
                        <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Yes yes yes</a>
                        <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Yes yes yes</a>
                        <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Yes yes yes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end search modal -->




<!-- Bootstrap JS -->
<script src="{{ secure_asset('frontend-assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{ secure_asset('frontend-assets/js/jquery.min.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/chartjs/js/chart.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
<!--Morris JavaScript -->
<script src="{{ secure_asset('frontend-assets/plugins/raphael/raphael-min.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/plugins/morris/js/morris.js')}}"></script>
<script src="{{ secure_asset('frontend-assets/js/index2.js')}}"></script>
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

@if(Auth::check())
<script>
    // pusherScript.js
   // Pusher.logToConsole = true;
    var pusher = new Pusher("cfc4e18a5372052374ee", {
        cluster: 'mt1',
        encrypted: true,
        authEndpoint: '/pusher/auth',
    });

    var channel = pusher.subscribe('login.{{Auth::user()->id}}');
    channel.bind('new_login', function (data) {
       // console.log(data);
        if(data.status)
        {
            window.location.reload();
        }

    });
    channel.bind('new_payment', function (data) {
        window.location.href=data;

    });
    channel.bind('pusher:subscription_count', function (members) {
        //console.log('successfully subscribed!');
    });
    channel.bind('pusher:subscription_succeeded', function (members) {
        //console.log('successfully subscribed!' + members);
    });

@endif

</script>


<!--app JS-->
<script src="{{ secure_asset('frontend-assets/js/app.js')}}"></script>
<script>
    new PerfectScrollbar(".app-container")
</script>
@yield('footer')

<script>
    const player = new Plyr('#player');
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeMoonIcon = document.querySelector('.dark-mode-icon.moon');
        const darkModeSunIcon = document.querySelector('.dark-mode-icon.sun');
        const darkThemeClass = 'dark-theme';
        const storageKey = 'theme';

        // Function to apply the dark theme based on the value
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add(darkThemeClass);
                darkModeMoonIcon.style.display = 'none';
                darkModeSunIcon.style.display = 'flex';
            } else {
                document.documentElement.classList.remove(darkThemeClass);
                darkModeMoonIcon.style.display = 'flex';
                darkModeSunIcon.style.display = 'none';
            }
        }

        // Function to toggle the dark theme
        function toggleDarkTheme() {
            if (document.documentElement.classList.contains(darkThemeClass)) {
                applyTheme('light');
                localStorage.setItem(storageKey, 'light');
            } else {
                applyTheme('dark');
                localStorage.setItem(storageKey, 'dark');
            }
        }

        // Add event listener to the dark mode icons
        darkModeMoonIcon.addEventListener('click', toggleDarkTheme);
        darkModeSunIcon.addEventListener('click', toggleDarkTheme);

        // Check the stored theme preference or the system preference
        const storedTheme = localStorage.getItem(storageKey);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (storedTheme) {
            applyTheme(storedTheme);
        } else if (systemPrefersDark) {
            applyTheme('dark');
        } else {
            applyTheme('light');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentTop = document.querySelector('.comment-top');
        commentTop.scrollTop = commentTop.scrollHeight;
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var subscribeButton = document.getElementById("subscribe-button");
        var unsubscribeButton = document.getElementById("unsubscribe-button");

        function sendAjaxRequest(form, successCallback, errorCallback) {
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        successCallback(response);
                    } else {
                        errorCallback(xhr.responseText);
                    }
                }
            };
            xhr.send(formData);
        }

        function handleSubscriptionSuccess(response) {
            console.log('Subscription success:', response);
            document.getElementById("subscribe-form").style.display = "none";
            document.getElementById("unsubscribe-form").style.display = "block";
            document.getElementById("subscriber-count").textContent = response.subscriber_count;
        }

        function handleUnsubscriptionSuccess(response) {
            console.log('Unsubscription success:', response);
            document.getElementById("unsubscribe-form").style.display = "none";
            document.getElementById("subscribe-form").style.display = "block";
            document.getElementById("subscriber-count").textContent = response.subscriber_count;
        }

        function handleError(error) {
            console.error('Request error:', error);
        }

        if (subscribeButton) {
            subscribeButton.addEventListener("click", function() {
                var subscribeForm = document.getElementById("subscribe-form");
                if (subscribeForm) {
                    sendAjaxRequest(subscribeForm, handleSubscriptionSuccess, handleError);
                }
            });
        }

        if (unsubscribeButton) {
            unsubscribeButton.addEventListener("click", function() {
                var unsubscribeForm = document.getElementById("unsubscribe-form");
                if (unsubscribeForm) {
                    sendAjaxRequest(unsubscribeForm, handleUnsubscriptionSuccess, handleError);
                }
            });
        }
    });
</script> 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoElement = document.querySelector('video'); // Adjust the selector as necessary
        let watchDuration = 0;
        let watchStartTime = null;

        if (videoElement) {
            videoElement.addEventListener('play', function() {
                watchStartTime = new Date();
            });

            videoElement.addEventListener('pause', updateWatchDuration);
            videoElement.addEventListener('ended', updateWatchDuration);

            window.addEventListener('beforeunload', function() {
                updateWatchDuration();
                sendWatchData();
            });
        }

        function updateWatchDuration() {
            if (watchStartTime) {
                watchDuration += Math.floor((new Date() - watchStartTime) / 1000);
                watchStartTime = null;
            }
        }

        function sendWatchData() {
            fetch('/record-watch-history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    video_id: videoElement.dataset.videoId,
                    watch_duration: watchDuration
                })
            });
        }
    });
</script>
<script type="text/javascript">
    history.pushState(null, document.title, location.href);
    window.addEventListener('popstate', function (event) {
        history.pushState(null, document.title, location.href);
    });
</script>

</body>

</html>
