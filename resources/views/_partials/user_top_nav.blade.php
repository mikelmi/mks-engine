<ul class="nav navbar-nav">
    <!-- Authentication Links -->
    @if (Auth::guest())
        @if (settings('users.auth'))
            <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">@lang('auth.Sign In')</a></li>
        @endif
        @if (settings('users.registration'))
            <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">@lang('auth.Register')</a></li>
        @endif
    @else
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-user"></i> {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" role="menu">
                <a class="dropdown-item" href="{{ route('user.profile') }}">
                    <i class="fa fa-newspaper-o"></i> @lang('user.Profile')
                </a>
                <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i> @lang('auth.Logout')
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="post" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    @endif
</ul>