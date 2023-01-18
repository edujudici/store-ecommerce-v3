@extends('site.baseTemplate')

@section('specific_head')
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/site/css/jquery.mCustomScrollbar.min.css') }}"/>
@endsection

@section('content')
<div id="koShop" class="page-content">
    <div class="overlay"></div>
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.shop.index')}}',
        linkTitle: 'Produtos'"></banner-area>
    @include('site.partials.template-shop-area')
    @include('site.partials.template-home-product-related-area')
</div>
@endsection

@section('custom_script')

    <!-- Popper.JS -->
    <script src="{{ asset('assets/site/js/popper.min.js') }}"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="{{ asset('assets/site/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
            head.viewModel.navSelected('shop');
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koShop'));
    </script
@endsection


