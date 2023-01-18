@extends('shared.masterpage')

@section('custom_tag')
    @include('painel.metaTag')
@endsection

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/painel/plugins/select2/css/select2.css') }}" />

    <!-- GOOGLE FONTS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" />

    <!-- PLUGINS CSS STYLE -->
    <link href="{{ asset('assets/painel/plugins/nprogress/nprogress.css') }}" />
    <!-- No Extra plugin used -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/painel/plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/painel/plugins/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/painel/plugins/toastr/toastr.min.css') }}" />
    <!-- SLEEK CSS -->
    <link rel="stylesheet" id="sleek-css" href="{{ asset('assets/painel/css/sleek.css') }}" />
    <!-- JQUERY UI -->
    <link rel="stylesheet" id="sleek-css" href="{{ asset('assets/painel/plugins/jquery-ui/css/jquery-ui.min.css') }}" />
    <!-- CROPPER -->
    <link href="{{ asset('assets/cropper/cropper.min.css') }}" rel="stylesheet" type="text/css" />
    <!--
        HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{ asset('assets/painel/plugins/nprogress/nprogress.js') }}"></script>

    @yield('specific_head')

@endsection

@section('maincontainer')

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">

    <script>
        NProgress.configure({ showSpinner: false });
        NProgress.start();
    </script>

    <div class="mobile-sticky-body-overlay"></div>

    <div id="toaster"></div>

    <div class="wrapper">
        <!-- Github Link -->
        <a href="https://github.com/tafcoder/sleek-dashboard" class="github-link">
            <svg width="70" height="70" viewBox="0 0 250 250" aria-hidden="true">
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="75%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#896def;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#482271;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path d="M 0,0 L115,115 L115,115 L142,142 L250,250 L250,0 Z" fill="url(#grad1)"></path>
            </svg>
            <i class="mdi mdi-github-circle"></i>
        </a>

        @include('painel.sidebar')

        <div class="page-wrapper">
            @include('painel.head')
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/painel/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/slimscrollbar/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/jekyll-search.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/charts/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/charts/knockout.chart.js') }}"></script>
    {{-- <script src="{{ asset('assets/painel/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/painel/plugins/jvectormap/jquery-jvectormap-world-mill.js') }}"></script> --}}
    <script src="{{ asset('assets/painel/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/painel/js/sleek.bundle.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/painel/plugins/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/cropper/cropper.min.js') }}"></script>
    <script src="{{ asset('assets/knockoutjs-3.5.0/knockout.cropper.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            /*======== 1. JEKYLL INSTANT SEARCH ========*/
            var searchInput = $('#search-input');
            if(searchInput.length != 0){
              SimpleJekyllSearch.init({
                searchInput: document.getElementById('search-input'),
                resultsContainer: document.getElementById('search-results'),
                dataSource: '/assets/painel/data/search.json',
                searchResultTemplate: '<li><div class="link"><a href="{{route("painel.dashboard.index")}}{link}">{label}</a></div><div class="location">{location}</div><\/li>',
                noResultsText: '<li>Nenhum resultado foi encontrado</li>',
                limit: 10,
                fuzzy: true,
              });
            }
        });
    </script>

    @yield('custom_script')

</body>

@endsection
