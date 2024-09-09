@extends('site.baseTemplate')

@section('content')
<div id="koExchange" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.exchange.index')}}',
        linkTitle: 'Política de Trocas e Devoluções'"></banner-area>
    @include('site.partials.template-exchange-area')
</div>
@endsection

@section('custom_script')
    <script type="text/javascript">
        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koExchange'));
    </script
@endsection
