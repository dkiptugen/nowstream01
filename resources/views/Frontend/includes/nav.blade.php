<div class="navbar-wrap main-menu d-none d-lg-flex">
    <ul class="navigation">
        <!-- <li class="active menu-item-has-children"><a href="#">Home</a>
                                        <ul class="submenu">
                                            <li class="active"><a href="index.html">Home One</a></li>
                                            <li><a href="index-2.html">Home Two</a></li>
                                        </ul>
                                    </li> -->


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
            <a class="nav-link" href=" {{ url('radios') }}">
                <div class="parent-icon"><i class="bx bx-video-recording"></i>
                </div>
                <div class="menu-title">radios</div>
            </a>

        </li>
        <!-- <li class="nav-item">
                        <a class="nav-link" href=" {{ url('continue') }}">
                            <div class="parent-icon"> <i class="bx bx-video"></i>
                            </div>
                            <div class="menu-title">Continue Watching</div>
                        </a>

                    </li> -->
        <li class="nav-item"> <a class="nav-link" href=" {{ url('podcasts') }}">
                <div class="parent-icon"><i class='bx bx-microphone'></i> </div>
                <div class="menu-title">Podcasts</div>
            </a> 
        </li>
        <li class="nav-item"> <a class="nav-link" href=" {{ url('tvs') }}">
                <div class="parent-icon"><i class='bx bx-microphone'></i> </div>
                <div class="menu-title">Live TV</div>
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
</div>