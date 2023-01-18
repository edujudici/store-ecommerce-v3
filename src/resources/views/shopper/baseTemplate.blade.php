@extends('site.baseTemplate')

@section('content')
<div id="koShopperArea" class="page-content">
    <banner-area params="
        subLink: '{{route('site.home.index')}}',
        subLinkTitle: 'Home',
        link: '{{route('shopper.index')}}',
        linkTitle: 'Minha área'"></banner-area>

    <!--================Home Box Area =================-->
	<section class="login_box_area section_gap_bottom">
		<div class="container">
			<div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="sidebar-filter mt-0">
                        <div class="common-filter" style="padding-bottom: 20px">
                            <div class="head pl-0 mt-0">Olá, {{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    <div class="sidebar-categories">
                        <div class="head">Bem vindo a sua area!</div>
                        <ul class="main-categories dismiss-web">
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.index') }}">
                                    <span class="ti-home pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>Início</span>
                                </a>
                            </li>
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.orders.index') }}">
                                    <span class="ti-bag pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>Pedidos</span>
                                </a>
                            </li>
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.favorites.index') }}">
                                    <span class="ti-heart pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>favoritos</span>
                                </a>
                            </li>
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.data.index') }}">
                                    <span class="ti-agenda pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>Meus Dados</span>
                                </a>
                            </li>
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.notifications.index') }}">
                                    <span class="ti-alert pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>Notficações</span>
                                </a>
                            </li>
                            <li class="main-nav-list">
                                <a href="{{ route('shopper.vouchers.index') }}">
                                    <span class="ti-gift pr-1" style="font-size: x-large; vertical-align: middle;"></span>
                                    <span>Cupons</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('shopperContent')
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</section>
    <!--================End Home Box Area =================-->

</div>
@endsection

@section('custom_script')
    @yield('shopperScript')

    <script type="text/javascript">

        function ViewModel() {
            var self = this;
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel, document.getElementById('koShopperArea'));
    </script>
@endsection
