<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle" href="#">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">

                {{-- Mobile toggle --}}
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                {{-- Desktop toggle --}}
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <div class="d-inline-flex align-items-center">
                        <div class="user-avatar me-2">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-semibold navbar-user-name">{{ Auth::user()->name }}</div>
                            <div class="navbar-user-email">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </a>

                {{-- Dropdown menu --}}
                <div class="dropdown-menu dropdown-menu-end navbar-user-dropdown">
                    <div class="px-3 py-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold navbar-user-name">{{ Auth::user()->name }}</div>
                                <span class="role-badge">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                                </span>
                            </div>
                        </div>
                        <div class="navbar-user-last-login">
                            <i class="align-middle me-1" data-feather="clock"></i>
                            Login terakhir: {{ Auth::user()->last_login_at?->diffForHumans() ?? 'Baru saja' }}
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="align-middle me-2" data-feather="log-out"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>