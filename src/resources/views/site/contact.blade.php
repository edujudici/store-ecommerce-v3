@extends('site.baseTemplate')

@section('content')
<div id="koContact" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('site.contact.index')}}',
        linkTitle: 'Contato'"></banner-area>
    @include('site.partials.template-contact-area')
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
            head.viewModel.navSelected('contact');
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koContact'));
    </script
@endsection


