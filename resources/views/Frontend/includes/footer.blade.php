
	<!-- newsletter-area -->
	<section class="newsletter-area newsletter-bg" data-background="{{ asset('assets/img')}}/bg/newsletter_bg.jpg">
		<div class="container">
			<div class="newsletter-inner-wrap">
				<div class="row align-items-center">
					<div class="col-lg-6">
						<div class="newsletter-content">
							<h4>Trial Start First 30 Days.</h4>
							<p>Enter your email to create or restart your membership.</p>
						</div>
					</div>
					<div class="col-lg-6">
						<form action="#" class="newsletter-form">
							<input type="email" required placeholder="Enter your email">
							<button class="btn">get started</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- newsletter-area-end -->

</main>
<!-- main-area-end -->
<!-- footer-area -->
<footer>
    <div class="footer-top-wrap">
        <div class="container">
            <div class="footer-menu-wrap">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <div class="footer-logo">
                            <a href="{{url('/')}}">
                                        <img src="{{ asset('assets/img/logo/logo.png') }}" class="logo-icon" alt="Streamer Logo" height="40">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="footer-menu">
                            <nav>
                                <ul class="navigation">
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
                    <!-- <li class="nav-item">
                        <a class="nav-link" href=" {{ url('continue') }}">
                            <div class="parent-icon"> <i class="bx bx-video"></i>
                            </div>
                            <div class="menu-title">Continue Watching</div>
                        </a>

                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href=" {{ url('/favorites') }}">
                            <div class="parent-icon"><i class="bx bx-heart"></i>
                            </div>
                            <div class="menu-title">Favorites</div>
                        </a>

                    </li>
                                </ul>
                                <div class="footer-search">
                                    <form action="#">
                                        <input type="text" placeholder="Find Favorite Shows & Streams">
                                        <button><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-quick-link-wrap">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="quick-link-list">
                            <ul>
                                <li><a href="#">FAQ</a></li>
                                <li><a href="#">Help Center</a></li>
                                <li><a href="#">Terms of Use</a></li>
                                <li><a href="#">Privacy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="copyright-text">
                        <p class="mb-0 d-none d-md-block">Copyright Streamer © 2024. All rights reserved.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="payment-method-img text-center text-md-right">
                        <img src="{{ asset('assets/img/images/card_img.png') }}" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->

<!--end page wrapper -->

<div class="overlay toggle-icon"></div>
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

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
   
</footer>
</div>
<!--end wrapper-->





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
{{-- new  --}}
<script src="{{ asset('assets')}}/js/vendor/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets')}}/js/popper.min.js"></script>
<script src="{{ asset('assets')}}/js/bootstrap.min.js"></script>
<script src="{{ asset('assets')}}/js/isotope.pkgd.min.js"></script>
<script src="{{ asset('assets')}}/js/imagesloaded.pkgd.min.js"></script>
<script src="{{ asset('assets')}}/js/jquery.magnific-popup.min.js"></script>
<script src="{{ asset('assets')}}/js/owl.carousel.min.js"></script>
<script src="{{ asset('assets')}}/js/jquery.odometer.min.js"></script>
<script src="{{ asset('assets')}}/js/jquery.appear.js"></script>
<script src="{{ asset('assets')}}/js/slick.min.js"></script>
<script src="{{ asset('assets')}}/js/ajax-form.js"></script>
<script src="{{ asset('assets')}}/js/wow.min.js"></script>
<script src="{{ asset('assets')}}/js/aos.js"></script>
<script src="{{ asset('assets')}}/js/plugins.js"></script>
<script src="{{ asset('assets')}}/js/main.js"></script>
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
    function toggleSubscription(channelId, isSubscribe) {
        const url = isSubscribe ?
            '{{ route("channels.subscribe", ":id") }}'.replace(':id', channelId) :
            '{{ route("channels.unsubscribe", ":id") }}'.replace(':id', channelId);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                const subscribeBtn = $('#subscribe-btn-' + channelId);
                if (isSubscribe) {
                    subscribeBtn.html(`
                   <button class="btn btn-danger btn-sm" onclick="toggleSubscription(${channelId}, false)">
                      Unsubscribe
                   </button>
                `);
                } else {
                    subscribeBtn.html(`
                   <button class="btn btn-outline-primary btn-sm" onclick="toggleSubscription(${channelId}, true)">
                      Subscribe
                   </button>
                `);
                }
                $('#subscriber-count-' + channelId).text(response.subscriber_count);
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    }
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

<script>
(function () {

    function initCarousel(carousel) {

        const track = carousel.querySelector('.pcar-track');
        const items = carousel.querySelectorAll('.pcar-item');

        if (!track || items.length === 0) return;

        let index = 0;
        let visibleItems = 1;
        let itemWidth = 0;

        const gap = 16;

        function getVisibleItems() {
            const w = window.innerWidth;

            if (w >= 992) return parseInt(carousel.dataset.desktop) || 5;
            if (w >= 768) return parseInt(carousel.dataset.tablet) || 3;
            return parseInt(carousel.dataset.mobile) || 1;
        }

        function setSizes() {
            visibleItems = getVisibleItems();

            const containerWidth = carousel.offsetWidth;
            itemWidth = (containerWidth - (gap * (visibleItems - 1))) / visibleItems;

            items.forEach(item => {
                item.style.width = itemWidth + 'px';
            });

            move();
        }

        function move() {
            const distance = index * (itemWidth + gap);
            track.style.transform = `translateX(-${distance}px)`;
        }

        function next() {
            if (index < items.length - visibleItems) {
                index++;
            } else {
                index = 0;
            }
            move();
        }

        /* Autoplay */
        if (carousel.dataset.autoplay === "true") {
            const interval = parseInt(carousel.dataset.interval) || 4000;
            setInterval(next, interval);
        }

        window.addEventListener('resize', setSizes);

        setSizes();
    }

    /* Initialize all carousels */
    document.querySelectorAll('.pcar').forEach(initCarousel);

    /* Set container width variable for overlay */
    function updateOverlayWidth() {
        const container = document.querySelector('.container');
        if (!container) return;

        const width = container.offsetWidth;
        document.documentElement.style.setProperty('--pcar-container-width', width + 'px');
    }

    window.addEventListener('load', updateOverlayWidth);
    window.addEventListener('resize', updateOverlayWidth);

})();
</script>
	<script>
		function syncCommentsHeight() {
			if (window.innerWidth < 1200) return;

			let videoWrap = document.getElementById('videoWrap');
			let commentsCard = document.getElementById('commentsCard');

			if (!videoWrap || !commentsCard) return;

			commentsCard.style.height = videoWrap.offsetHeight + "px";
		}

		$(document).ready(function() {
			syncCommentsHeight();
			$(window).on('resize', syncCommentsHeight);

			// delay to allow Plyr render
			setTimeout(syncCommentsHeight, 300);
			setTimeout(syncCommentsHeight, 1000);
		});
	</script>

</body>

</html>
