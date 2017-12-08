<!DOCTYPE html>
<html lang="en">

    <head>

        <title>@lang('main.layout.application_title')</title>

        <!--[if (!IE)|(gt IE 8)]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><!--<![endif]-->
        <!--[if lte IE 8]><meta http-equiv="X-UA-Compatible" content="IE=8" /><![endif]-->

        <meta id="copyright" name="copyright" content="Copyright 2016 Sipi Asset Recovery" />
        <meta id="description" name="description" content="For the best prices on all used & refurbished desktop PCs, laptops, computer parts & peripherals, shop Belmont Technology. Quality inspected & low prices!" />
        <meta id="keywords" name="keywords" content="Belmont, Sipi, Assets Recovery, Technology Remarketing, refurbished, computer parts, peripherals" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
        <meta charset="utf-8">

        <link rel="icon" type="image/x-icon" href="{{ secure_asset("/img/favicon.ico") }}" >
        <link rel="shortcut icon" type="image/x-icon" href="{{ secure_asset("/img/favicon.ico") }}"/>

        <!-- CSS / Prerequisites -->

        <!-- Bootstrap  -->
        <link href="{{ secure_asset("/lib/bootstrap/css/bootstrap.css") }}" rel="stylesheet">
        <!-- Bootstrap Theme -->
        <link href="{{ secure_asset("/lib/bootstrap/css/bootstrap-theme.css") }}" rel="stylesheet">
        <!-- Bootstrap Jasny -->
        <link href="{{ secure_asset("/lib/bootstrap-jasny/css/bootstrap-jasny.css") }}" rel="stylesheet">
        <!-- Bootstrap Colorpicker -->
        <link href="{{ secure_asset("/lib/bootstrap-colorpicker/css/bootstrap-colorpicker.css") }}" rel="stylesheet">
        <!-- Bootstrap Datepicker -->
        <link href="{{ secure_asset("/lib/bootstrap-datepicker/css/bootstrap-datepicker.css") }}" rel="stylesheet">
        <!-- Bootstrap Select -->
        <link href="{{ secure_asset("/lib/bootstrap-select/css/bootstrap-select.css") }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{ secure_asset("/lib/font-awesome/css/font-awesome.css") }}" rel="stylesheet">
        <!-- Lato -->
        <link href="{{ secure_asset("/lib/lato/css/lato.css") }}" rel="stylesheet">
        <!-- Toastr -->
        <link href="{{ secure_asset("/lib/toastr/css/toastr.css") }}" rel="stylesheet">

        <!-- CSS / Application -->

        <link href="{{ secure_asset("/css/util.css") }}" rel="stylesheet">
        <link href="{{ secure_asset("/css/main.css") }}" rel="stylesheet">
        <link href="{{ secure_asset("/css/header.css") }}" rel="stylesheet">
        <link href="{{ secure_asset("/css/menu.css") }}" rel="stylesheet">

    </head>

    <body>

        <header id="header">
            @include('partial.header')
        </header>

        <nav id="menu">
            @include('partial.menu')
        </nav>

        <main id="main">
            @yield('content')
        </main>

        <footer id="footer">
            @include('partial.footer')
        </footer>

        <!-- JS / Prerequisites -->

        <script src="{{ secure_asset("/lib/jquery/js/jquery.js") }}"></script>
        <script src="{{ secure_asset("/lib/jquery-browser/js/jquery-browser.js") }}"></script>
        <script src="{{ secure_asset("/lib/jquery-stickyTableHeaders/js/jquery-stickyTableHeaders.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootstrap/js/bootstrap.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootstrap-jasny/js/bootstrap-jasny.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootstrap-colorpicker/js/bootstrap-colorpicker.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootstrap-select/js/bootstrap-select-custom.js") }}"></script>
        <script src="{{ secure_asset("/lib/toastr/js/toastr.js") }}"></script>
        <script src="{{ secure_asset("/lib/bootbox/bootbox.js") }}"></script>

        <!-- JS / Application -->

        <script src="{{ secure_asset("/js/helpers.js") }}"></script>

        @if (session('toast')){{--
     --}}<script>
            toastr.error('{{ session('toast') }}', 'Error!', { positionClass: 'toast-center' })
        </script>
        @endif{{--
    --}}<script>
            $('.alert.animate').fadeIn(2000);
            setTimeout(function() {
                $('.alert.animate').fadeOut(2000);
            }, 5000);
            $('.alert.animate-slowly').fadeIn(2000);
            setTimeout(function() {
                $('.alert.animate-slowly').fadeOut(2000);
            }, 15000);
        </script>

        <script>
            $(function () {
                $('.single-click').click(function () {
                    $(this).addClass('disabled');

                    var element = $(this);
                    setTimeout(function() {
                        element.removeClass('disabled');
                    }, 5000);
                });

                $('.single-click').removeClass('disabled');
            });

            function goBack() {
                @if (isset($context))
                $.get('{{route('main.back', [Constants::CONTEXT_PARAMETER => $context]) }}', function (data) {
                    if (data) {
                        location.href = data;
                    }
                    else {
                        window.history.back();
                    }
                });
                @else
                window.history.back();
                @endif
            }
        </script>
        @yield('js')

    </body>

</html>
