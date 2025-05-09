@extends('site.baseTemplate')

@section('content')
<div id="koShopperLogin" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('login')}}',
        linkTitle: 'Cadastro / Login'"></banner-area>
    @include('shopper.partials.template-shopper-login-area')
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
