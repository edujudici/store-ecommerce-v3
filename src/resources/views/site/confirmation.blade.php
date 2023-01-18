@extends('site.baseTemplate')

@section('content')
<div id="koConfirmation" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.payment.confirmation', ['success'])}}',
        linkTitle: 'Confirmação'"></banner-area>
    @include('site.partials.template-confirmation-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koConfirmation'));
    </script
@endsection
