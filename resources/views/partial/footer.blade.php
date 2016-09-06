{{-- ViewComposers\FooterComposer --}}
<div class="footer">
    <hr class="margin-top-none">
    <div class="container margin-bottom-lg">
        <div class="col-md-12">
            <div class="col-md-10">
                <div>© <?php echo date('Y'); ?> @lang('main.layout.footer.copyright')</div>
                @if (env('APP_DEBUG'))<div>v{{ Constants::VERSION }}</div>@endif
            </div>
            <div class="col-md-2">
                @if (isset($context))
                    <p><a href="{{ route('main.termsAndNotices') }}">@lang('main.layout.footer.terms_and_notices')</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
