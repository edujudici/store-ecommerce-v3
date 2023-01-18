@extends('site.baseTemplate')

@section('content')
<div id="koPrivacy" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.privacy.index')}}',
        linkTitle: 'Política Privacidade'"></banner-area>
    @include('site.partials.template-privacy-area')
</div>
@endsection

@section('custom_script')
    <script type="text/javascript">
        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koPrivacy'));
    </script
@endsection
