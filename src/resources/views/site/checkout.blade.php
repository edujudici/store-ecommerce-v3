@extends('site.baseTemplate')

@section('content')
<div id="koCheckout" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.checkout.index')}}',
        linkTitle: 'Checkout'"></banner-area>
    @include('site.partials.template-checkout-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koCheckout'));
    </script
@endsection


