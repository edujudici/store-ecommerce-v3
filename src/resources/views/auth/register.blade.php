@extends('site.baseTemplate')

@section('content')
<div id="koShopperLogin" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('register')}}',
        linkTitle: 'Register'"></banner-area>
    @include('shopper.partials.template-shopper-register-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koShopperLogin'));
    </script>
@endsection
