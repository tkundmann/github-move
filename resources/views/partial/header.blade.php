{{-- ViewComposers\HeaderComposer --}}
<div class="header">
    <div class="container">
    @if (isset($site))
        <a class="logo" href="{{ route('main.home') }}">
            <img class="img-responsive" src="{{ secure_asset($site->logoUrl) }}">
        </a>
        @if (!$site->hasFeature(Feature::HIDE_TITLE))
            <h3 class="companyName">{{ $site->title or '' }}</h3>
        @endif
    @endif
    @if (!isset($site) && isset($context) && ContextHelper::isAdminContext($context))
        <a class="logo" href="{{ route('main.home') }}">
            <img class="img-responsive" src="{{ secure_asset('/img/logo/logo-sipi.png') }}">
        </a>
        <h3 class="companyName">@lang('main.layout.admin')</h3>
    @endif
    </div>
</div>