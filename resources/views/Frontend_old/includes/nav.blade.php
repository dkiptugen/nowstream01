<!--navigation-->
<div class="primary-menu">
    <nav class="navbar navbar-expand-lg align-items-center">
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom">
                <div class="d-flex align-items-center">
                    <div class="">
                        <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                    </div>
                    <div class="">
                        <h4 class="logo-text">Baze Live</h4>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav align-items-center flex-grow-1">

                    <li class="nav-item">
                        <a class="nav-link" href=" {{ url('/') }}">
                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
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
                    <li class="nav-item">
                        <a class="nav-link" href=" {{ url('continue') }}">
                            <div class="parent-icon"> <i class="bx bx-video"></i>
                            </div>
                            <div class="menu-title">Continue Watching</div>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=" {{ url('/favorites') }}">
                            <div class="parent-icon"><i class="bx bx-heart"></i>
                            </div>
                            <div class="menu-title">Favorites</div>
                        </a>

                    </li>
                </ul>
               @php
use App\Models\Event;

$current_event = Event::orderBy('created_at', 'asc')->first();
@endphp

                <a href="{{ url("/event/{$current_event->id}/{$current_event->slug}") }}" class="btn btn-danger d-inline-flex align-items-center gap-2"
                                aria-label="buttons">
                               <i class="lni lni-ticket"></i> Buy Ticket</a>
                <!-- <div class="user-info">
                    <a class="nav-link d-flex align-items-center pe-3" href="{{ url('/admin/register') }}"><i class="bx bx-camera-movie fs-5"></i> <span>Content With Us</span></a>
                </div> -->
            </div>
        </div>
    </nav>
</div>
<!--end navigation-->
