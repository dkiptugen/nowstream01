<div class="wrapper">
    <nav class="sidebar sidebar-sticky">
        <span class="sidebar-content  js-simplebar">
            <a class="sidebar-brand text-center" href="{{ route('admin_dashboard') }}">
                <img src="{{ asset($logo) }}" alt="Logo" class="img-fluid" width="50" >
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Main
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin_dashboard') }}" class="  sidebar-link">
                       <i class='fas fa-home align-middle'></i><span class="align-middle">Dashboard</span>
                    </a>

                </li>

                @canany(['view_event','view_specific_event'])
                    <li class="sidebar-item">
                        <a href="{{ route('channel.event.index',1) }}" class="  sidebar-link">
                            <i class='fas fa-clock align-middle'></i><span class="align-middle">Events</span>
                        </a>

                    </li>
                @endcanany
                @can('view_channel_stream')
                    <li class="sidebar-item">
                        <a href="{{ route('channel.stream.index',1) }}" class="  sidebar-link">
                           <i class="fas fa-stream align-middle"></i><span class="align-middle">Streams</span>
                        </a>

                    </li>
                @endcan
                @can('view_channel_video')
                    <li class="sidebar-item">
                        <a href="{{ route('channel.video.index',1) }}" class="  sidebar-link">
                           <i class="fas fa-video align-middle"></i>
                            <span class="align-middle">Videos</span>
                        </a>

                    </li>
                @endcan

                <li class="sidebar-header">Revenue</li>
                @can('view_payment_method')
                    <li class="sidebar-item">
                        <a href="{{ route('payment_method.index') }}" class="  sidebar-link">
                            <i class="fas fa-door-open align-middle"></i><span class="align-middle">Payment Methods</span>
                        </a>

                    </li>
                @endcan
                @can('view_subscription')
                    <li class="sidebar-item">
                        <a href="{{ route('channel.subscription.index',1) }}" class="  sidebar-link">
                            <i class='fas fa-box align-middle'></i><span class="align-middle">Subscriptions</span>
                        </a>
                    </li>

                @endcan
                @can('view_transaction')
                    <li class="sidebar-item">
                        <a href="{{ route('channel.transaction.index',1) }}" class="  sidebar-link">
                            <i class='fas fa-dollar-sign  align-middle'></i>

                            <span class="align-middle">Transactions</span>
                        </a>
                    </li>
                @endcan
                <li class="sidebar-header">Stream Partners</li>
                <li class="sidebar-item">
                    <a href="{{ route('stream_partner.index') }}" class="  sidebar-link">
                        <i class="fas fa-folder-open align-middle"></i>

                        <span class="align-middle">Stream Partner Rates</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('stream_partner_rate.index') }}" class="  sidebar-link">
                       <i class="fas fa-user-check align-middle"></i>

                        <span class=" align-middle">Stream Partners</span>
                    </a>

                </li>
                <li class="sidebar-header">Accounts</li>
                <li class="sidebar-item">
                    <a href="{{ route('user.index') }}" class="  sidebar-link">
                        <i class='fas fa-user  align-middle'></i>

                        <span class=" align-middle">Users</span>
                    </a>

                </li>
                <li class="sidebar-item">
                    <a href="{{ route('role.index') }}" class="  sidebar-link">
                        <i class="fas fa-tools"></i>

                        <span class="menu-title">Roles</span>
                    </a>

                </li>
                <li class="sidebar-item">
                    <a href="{{ route('configuration.index') }}" class="  sidebar-link">
                        <i class="fas fa-hammer align-middle"></i>

                        <span class="align-middle">Configuration</span>
                    </a>

                </li>
                <li class="sidebar-header">Logs</li>

                <li class="sidebar-item">
                    <a href="{{ route('logs.index') }}" class="  sidebar-link">
                        <i class="fas fa-pen align-middle"></i>

                        <span class="align-middle">Activity Log</span>
                    </a>
                </li>
            </ul>



    </nav>

