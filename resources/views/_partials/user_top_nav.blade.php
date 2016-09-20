<ul class="nav navbar-nav pull-right">
    <!-- Authentication Links -->
    @if (Auth::guest())
        <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">@lang('auth.Sign In')</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">@lang('auth.Register')</a></li>
    @else
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-user"></i> {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" role="menu">
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