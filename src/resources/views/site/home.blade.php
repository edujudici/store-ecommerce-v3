@extends('site.baseTemplate')

@section('content')
<div id="koHome" class="page-content">
    @include('site.partials.template-home-banner-area')
    @include('site.partials.template-home-feature-area')
    @include('site.partials.template-home-product-exclusive-area')
    @include('site.partials.template-home-product-area')
    @include('site.partials.template-home-category-area')
    @include('site.partials.template-home-product-visited')
    @include('site.partials.template-home-brand-area')
    @include('site.partials.template-home-product-related-area')
</div>
@endsection

@section('custom_script')

{{-- <script src="{{ asset('assets/site/js/countdown.js') }}"></script> --}}

<script type="text/javascript">
    function ViewModel() {
        var self = this;
        head.viewModel.navSelected('home');
    }

    var viewModel = new ViewModel();
    ko.applyBindings(viewModel, document.getElementById('koHome'));
</script>
@endsection
