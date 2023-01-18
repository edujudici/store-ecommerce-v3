@extends('site.baseTemplate')

@section('content')
<div id="koDetail" class="page-content">
    <banner-area params="
        subLink: '{{route('site.shop.index')}}',
        subLinkTitle: 'Shop',
        link: '{{route('site.shop.detail', [$sku])}}',
        linkTitle: 'Detalhes do Produto'"></banner-area>
    @include('site.partials.template-detail-area')
    @include('site.partials.template-detail-description-area')
    @include('site.partials.template-home-product-related-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koDetail'));
    </script
@endsection


