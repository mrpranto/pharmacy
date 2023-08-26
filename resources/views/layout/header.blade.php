<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form">
            <div class="d-flex justify-content-end">
                @yield('breadcrumb')
            </div>
        </form>

        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">

                    @if(session()->get('lang') == 'en')
                        <i class="flag-icon flag-icon-us mt-1" title="us"></i>
                        <span class="font-weight-medium ml-1 mr-1">English</span>
                    @else
                        <i class="flag-icon flag-icon-bd mt-1" title="bd"></i>
                        <span class="font-weight-medium ml-1 mr-1">বাংলা</span>
                    @endif
                </a>
                <div class="dropdown-menu" aria-labelledby="languageDropdown">

                    <a href="{{ route('set-lang', 'en') }}" class="dropdown-item py-2">
                        <i class="flag-icon flag-icon-us" title="us" id="us"></i> <span class="ml-1"> English </span>
                    </a>
                    <a href="{{ route('set-lang', 'bn') }}" class="dropdown-item py-2">
                        <i class="flag-icon flag-icon-bd" title="bd" id="bd"></i> <span class="ml-1"> বাংলা </span>
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                @if(session()->get('color-mode') == 'white')
                    <a class="nav-link" href="{{ route('set-color-mode', 'dark') }}">
                        <i data-feather="moon"></i>
                    </a>
                @elseif(session()->get('color-mode') == 'dark')
                    <a class="nav-link" href="{{ route('set-color-mode', 'white') }}">
                        <i data-feather="sun"></i>
                    </a>
                @else
                    <a class="nav-link" href="{{ route('set-color-mode', 'dark') }}">
                        <i data-feather="moon"></i>
                    </a>
                @endif
            </li>


            <li class="nav-item dropdown nav-messages">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i data-feather="mail"></i>
                </a>
<!--                <div class="dropdown-menu" aria-labelledby="messageDropdown">
                    <div class="dropdown-header d-flex align-items-center justify-content-between">
                        <p class="mb-0 font-weight-medium">9 New Messages</p>
                        <a href="javascript:;" class="text-muted">Clear all</a>
                    </div>
                    <div class="dropdown-body">
                        <a href="javascript:;" class="dropdown-item">
                            <div class="figure">
                                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>Leonardo Payne</p>
                                    <p class="sub-text text-muted">2 min ago</p>
                                </div>
                                <p class="sub-text text-muted">Project status</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="figure">
                                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>Carl Henson</p>
                                    <p class="sub-text text-muted">30 min ago</p>
                                </div>
                                <p class="sub-text text-muted">Client meeting</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="figure">
                                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>Jensen Combs</p>
                                    <p class="sub-text text-muted">1 hrs ago</p>
                                </div>
                                <p class="sub-text text-muted">Project updates</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="figure">
                                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>Amiah Burton</p>
                                    <p class="sub-text text-muted">2 hrs ago</p>
                                </div>
                                <p class="sub-text text-muted">Project deadline</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="figure">
                                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p>Yaretzi Mayo</p>
                                    <p class="sub-text text-muted">5 hr ago</p>
                                </div>
                                <p class="sub-text text-muted">New record</p>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-footer d-flex align-items-center justify-content-center">
                        <a href="javascript:;">View all</a>
                    </div>
                </div>-->
            </li>
            <li class="nav-item dropdown nav-notifications">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>
                    <div class="indicator">
                        <div class="circle"></div>
                    </div>
                </a>
<!--                <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                    <div class="dropdown-header d-flex align-items-center justify-content-between">
                        <p class="mb-0 font-weight-medium">6 New Notifications</p>
                        <a href="javascript:;" class="text-muted">Clear all</a>
                    </div>
                    <div class="dropdown-body">
                        <a href="javascript:;" class="dropdown-item">
                            <div class="icon">
                                <i data-feather="user-plus"></i>
                            </div>
                            <div class="content">
                                <p>New customer registered</p>
                                <p class="sub-text text-muted">2 sec ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="icon">
                                <i data-feather="gift"></i>
                            </div>
                            <div class="content">
                                <p>New Order Recieved</p>
                                <p class="sub-text text-muted">30 min ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="icon">
                                <i data-feather="alert-circle"></i>
                            </div>
                            <div class="content">
                                <p>Server Limit Reached!</p>
                                <p class="sub-text text-muted">1 hrs ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="icon">
                                <i data-feather="layers"></i>
                            </div>
                            <div class="content">
                                <p>Apps are ready for update</p>
                                <p class="sub-text text-muted">5 hrs ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item">
                            <div class="icon">
                                <i data-feather="download"></i>
                            </div>
                            <div class="content">
                                <p>Download completed</p>
                                <p class="sub-text text-muted">6 hrs ago</p>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-footer d-flex align-items-center justify-content-center">
                        <a href="javascript:;">View all</a>
                    </div>
                </div>-->
            </li>


            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <img src="{{ optional(auth()->user()->profilePicture)->full_url ?? asset('/images/avatar.png') }}"
                         alt="profile">
                    {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="figure mb-3">
                            <img style="width: 80px;height: 80px"
                                 src="{{ optional(auth()->user()->profilePicture)->full_url ?? asset('/images/avatar.png') }}"
                                 alt="">
                        </div>
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ auth()->user()->name }}</p>
                            <p class="email text-muted mb-3">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            <li class="nav-item">
                                <a href="{{ route('profile') }}?type=personal_info" class="nav-link">
                                    <i data-feather="user"></i>
                                    <span>{{ __t('profile') }}</span>
                                </a>
                            </li>
                            @if(auth()->user()->can('app.setting'))
                                <li class="nav-item">
                                    <a href="{{ route('setting') }}?type=app_setting" class="nav-link">
                                        <i data-feather="settings"></i>
                                        <span>{{ __t('setting') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="javascript:;" onclick="document.getElementById('logout').submit();"
                                   class="nav-link">
                                    <i data-feather="log-out"></i>
                                    <span>Log Out</span>
                                </a>
                                <form style="display: none" id="logout" method="post" action="{{ route('logout') }}">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
