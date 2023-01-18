@extends('site.baseTemplate')

@section('content')
<div id="koShopperLogin" class="page-content">
    @include('shopper.partials.template-shopper-register-banner-area')
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
