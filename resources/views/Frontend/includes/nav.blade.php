<div class="navbar-wrap main-menu d-none d-lg-flex">
    <ul class="navigation">

        <li class="nav-item">
            <a class="nav-link" href="{{ url('events') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-calendar-event"></i>
                </div>
                <div class="menu-title">Events</div>
            </a>
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link" href="{{ url('streams') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-play-circle"></i>
                </div>
                <div class="menu-title">Streams</div>
            </a>
        </li> -->

        <li class="nav-item">
            <a class="nav-link" href="{{ url('all-videos') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-video"></i>
                </div>
                <div class="menu-title">Videos</div>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('radios') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-radio"></i>
                </div>
                <div class="menu-title">Radios</div>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('podcasts') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-microphone"></i>
                </div>
                <div class="menu-title">Podcasts</div>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('tvs') }}">
                <div class="parent-icon mr-1">
                    <i class="bx bx-tv"></i>
                </div>
                <div class="menu-title">Live TV</div>
            </a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('shop') }}">
                    <div class="parent-icon mr-1">
                        <i class="bx bx-store"></i>
                    </div>
                    <div class="menu-title">Shop</div>
                </a>
            </li>

        <li class="menu-item-has-children theme-switcher-menu">
            <a class="nav-link theme-switcher-trigger" href="#">
                <div class="parent-icon mr-1">
                    <i class="bx bx-palette"></i>
                </div>
                <div class="menu-title">Theme</div>
            </a>
            <ul class="submenu">
                <li>
                    <a href="#" class="theme-switcher-option" data-theme-value="light">
                        <span>Light</span>
                        <i class="bx bx-check theme-switcher-option-check"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="theme-switcher-option" data-theme-value="dark">
                        <span>Dark</span>
                        <i class="bx bx-check theme-switcher-option-check"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="theme-switcher-option" data-theme-value="system">
                        <span>System</span>
                        <i class="bx bx-check theme-switcher-option-check"></i>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</div>
