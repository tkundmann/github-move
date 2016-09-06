{{-- ViewComposers\MenuComposer --}}
<div class="navbar navbar-default navbar-static-top navbar-custom"@if (isset($site) && !empty($site->color)) style="background-color: {{ $site->color }}"@endif>
    <div class="container">
        <div class="navbar-header">
            <!-- Hamburger menu -->
            <button type="button" class="navbar-toggle collapsed navbar-left" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">@lang('main.layout.toggle_navigation')</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left side of menu -->
            @if (isset($menu))
                <ul class="nav navbar-nav">
                    @foreach ($menu as $menuItem)
                        <li @if ($menuItem['active'])class="active"@endif><a href="{{ $menuItem['url'] }}"><i class="fa fa-btn {{ $menuItem['icon'] }}"></i>@lang($menuItem['label'])</a></li>
                    @endforeach
                </ul>
            @endif

            <!-- Right side of menu -->
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check() && (!isset($exception)))
                    <li>
                        <span>@lang('main.layout.logged_in_as'): {{ Auth::user()->name }}</span>
                    </li>
                    @if ($site && ContextHelper::isSiteContext($context))
                    <li>
                        <a href="{{ route('password.change') }}"><i class="fa fa-btn fa-refresh"></i>@lang('auth.password.change_password')</a>
                    </li>
                    @endif
                    @if (!$site && ContextHelper::isAdminContext($context))
                    <li>
                        <a href="{{ route('admin.account.edit', ['id' => Auth::user()->id]) }}"><i class="fa fa-btn fa-user"></i>@lang('common.edit')</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('main.layout.logout')</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>