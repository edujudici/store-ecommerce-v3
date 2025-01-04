@extends('shared.masterpage')

@section('custom_tag')
    @include('site.metaTag')
@endsection

@section('custom_head')
    <!--
                                            CSS
                                            ============================================= -->
    <link rel="stylesheet" href="{{ asset('assets/site/css/linearicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/ion.rangeSlider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/ion.rangeSlider.skinFlat.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/whatsapp.css') }}" />

    @if (App::environment('production'))
        {{-- Aviso de consentimento de uso de cookies --}}
        <meta name="adopt-website-id" content="88e6b798-1416-4b0f-a7ec-89766e3213b8" />
        <script src="//tag.goadopt.io/injector.js?website_code=88e6b798-1416-4b0f-a7ec-89766e3213b8" class="adopt-injector">
        </script>
    @endif

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6Y3F1W6QVE"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6Y3F1W6QVE');
    </script>

    @yield('specific_head')
@endsection

@section('maincontainer')

    <body>

        @include('site.head')

        @yield('content')

        @include('site.footer')

        <script src="{{ asset('assets/site/js/vendor/jquery-2.2.4.min.js') }}"></script>
        <script src="{{ asset('assets/site/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/site/js/jquery.sticky.js') }}"></script>
        <script src="{{ asset('assets/site/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('assets/site/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/site/js/main.js') }}"></script>
        <script src="{{ asset('assets/site/js/jquery.maskedinput.js') }}"></script>

        @yield('custom_script')

    </body>
@endsection
