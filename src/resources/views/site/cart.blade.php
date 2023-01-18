@extends('site.baseTemplate')

@section('content')
<div id="koCart" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.cart.index')}}',
        linkTitle: 'Carrinho'"></banner-area>
    @include('site.partials.template-cart-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koCart'));
    </script
@endsection


