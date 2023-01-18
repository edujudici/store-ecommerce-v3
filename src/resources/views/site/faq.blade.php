@extends('site.baseTemplate')

@section('content')
<div id="koFaq" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.faq.index')}}',
        linkTitle: 'Perguntas Frequentes'"></banner-area>
    @include('site.partials.template-faq-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
            head.viewModel.navSelected('faq');
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koFaq'));
    </script
@endsection


