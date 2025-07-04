<style>

.status-dot {
        height: 7px;
        width: 7px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light navss">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <span class="ml-2 text-white" style="font-size: 12px">
        <div class="d-flex align-items-center">
            <span class="status-dot mb-0"></span>
            <strong class="text-white">Active</strong>
        </div>
        <b>SY: @php
            $sydesc = DB::table('sy')->where('isactive', 1)->first();
        @endphp {{ $sydesc->sydesc }} |
        @php
            $semester = DB::table('semester')->where('isactive', 1)->first();
        @endphp {{ $semester->semester }}</b>
    </span>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        {{-- <li class="nav-item dropdown user user-menu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-primary navbar-badge" id="notifbell_count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationBellHolder">
                        <a href="/notificationv2/index" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
            </ul>
        </li> --}}

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge" id="notification_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>


        <li class="nav-item">
            <a href="#" id="logout" class="nav-link">
                <!-- <i class="nav-icon fa fa-power-off"></i> -->
                <span class="logoutshow" id="logoutshow"> Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
