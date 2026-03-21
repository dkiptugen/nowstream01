<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar" aria-label="Primary">
        <div class="sidebar-content  js-simplebar">
            <a class="sidebar-brand" href="{{ route('backend.admin_dashboard') }}" aria-label="Nowstream admin home">
                <img src="{{ asset($logo) }}?=1" alt="Nowstream admin logo" class="img-fluid w-100"  >
            </a>

            <ul class="sidebar-nav" role="list">
                <li class="sidebar-header" role="presentation">
                    Main
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('backend.admin_dashboard') }}" class="  sidebar-link">
                       <i class='fas fa-home align-middle'></i><span class="align-middle">Dashboard</span>
                    </a>

                </li>
                @can('view_microsite')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.microsite.index') }}" class="  sidebar-link">
                           <i class="fas fa-lightbulb align-middle"></i>
                            <span class="align-middle">Microsites</span>
                        </a>
                    </li>
                @endcan

                @canany(['view_event','view_specific_event'])
                    <li class="sidebar-item">
                        <a href="{{ route('backend.event.index') }}" class="  sidebar-link">
                            <i class='fas fa-clock align-middle'></i><span class="align-middle">Events</span>
                        </a>

                    </li>
                @endcanany
                @can('view_category')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.category.index') }}" class="  sidebar-link">
                           <i class="fas fa-folder-open align-middle"></i>
                            <span class="align-middle">Categories</span>
                        </a>
                    </li>
                @endcan
                @can('view_stream')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.stream.index') }}" class="  sidebar-link">
                           <i class="fas fa-stream align-middle"></i><span class="align-middle">Streams</span>
                        </a>

                    </li>
                @endcan
                @can('view_video')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.video.index') }}" class="  sidebar-link">
                           <i class="fas fa-video align-middle"></i>
                            <span class="align-middle">Videos</span>
                        </a>

                    </li>
                @endcan
                @can('view_tv')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.tv.index') }}" class="  sidebar-link">
                           <i class="fas fa-tv align-middle"></i>
                            <span class="align-middle">TVs</span>
                        </a>

                    </li>
                @endcan
                @can('view_radio')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.radio.index') }}" class="  sidebar-link">
                           <i class="fas fa-microphone align-middle"></i>
                            <span class="align-middle">Radios</span>
                        </a>

                    </li>
                @endcan
                @can('view_podcast')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.podcast.index') }}" class="  sidebar-link">
                           <i class="fas fa-volume-up align-middle"></i>
                            <span class="align-middle">Podcasts</span>
                        </a>

                    </li>
                @endcan

                <li class="sidebar-header" role="presentation">Revenue</li>
                @can('view_payment_method')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.payment_method.index') }}" class="  sidebar-link">
                            <i class="fas fa-door-open align-middle"></i><span class="align-middle">Payment Methods</span>
                        </a>

                    </li>
                @endcan

                @can('view_transaction')
                    <li class="sidebar-item">
                        <a href="{{ route('backend.transaction.index') }}" class="  sidebar-link">
                            <i class='fas fa-dollar-sign  align-middle'></i>

                            <span class="align-middle">Transactions</span>
                        </a>
                    </li>
                @endcan

                <li class="sidebar-header" role="presentation">Accounts</li>
                <li class="sidebar-item">
                    <a href="{{ route('backend.user.index') }}" class="  sidebar-link">
                        <i class='fas fa-user  align-middle'></i>

                        <span class=" align-middle">Users</span>
                    </a>

                </li>
                <li class="sidebar-item">
                    <a href="{{ route('backend.role.index') }}" class="  sidebar-link">
                        <i class="fas fa-tools"></i>

                        <span class="menu-title">Roles</span>
                    </a>

                </li>
                <li class="sidebar-item">
                    <a href="{{ route('backend.configuration.index') }}" class="  sidebar-link">
                        <i class="fas fa-hammer align-middle"></i>

                        <span class="align-middle">Configuration</span>
                    </a>

                </li>
                <li class="sidebar-header" role="presentation">Logs</li>

                <li class="sidebar-item">
                    <a href="{{ route('backend.logs.index') }}" class="  sidebar-link">
                        <i class="fas fa-pen align-middle"></i>

                        <span class="align-middle">Activity Log</span>
                    </a>
                </li>
            </ul>



    </nav>
