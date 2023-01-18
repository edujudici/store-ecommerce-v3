<!-- Start Header Area -->
<header class="header_area sticky-header" id="koHead">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="/"><img alt="" data-bind="attr: {src: base.displayImage(company.com_image)}"></a>
                @if(auth()->user())
                    <a href="{{route('shopper.notifications.index')}}" class="navbar-toggler pr-2" title="Notificações">
                        <span class="ti-bell"></span>
                        <span data-bind="text: head.viewModel.totalNotifications()"></span>
                    </a>
                    <a href="{{route('shopper.favorites.index')}}" class="navbar-toggler pr-2" title="Favoritos">
                        <span class="ti-heart"></span>
                        <span data-bind="text: head.viewModel.totalItemsFavorite()"></span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="navbar-toggler pr-2" title="Entrar">
                        <span class="ti-user"></span>
                    </a>
                @endif
                <a href="{{route('site.cart.index')}}" class="navbar-toggler pr-2" title="Carrinho">
                    <span class="ti-bag"></span>
                    <span data-bind="text: head.viewModel.totalItems()"></span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item" data-bind="css: {'active': navSelected() == 'home'}"><a class="nav-link" href="{{route('site.home.index')}}">Home</a></li>
                        <li class="nav-item" data-bind="css: {'active': navSelected() == 'shop'}"><a class="nav-link" href="{{route('site.shop.index')}}">Produtos</a></li>
                        <li class="nav-item" data-bind="css: {'active': navSelected() == 'faq'}"><a class="nav-link" href="{{route('site.faq.index')}}">FAQ</a></li>
                        <li class="nav-item" data-bind="css: {'active': navSelected() == 'contact'}"><a class="nav-link" href="{{route('site.contact.index')}}">Contato</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="ti-user"></span>
                                @if(auth()->user())
                                <span>{!! auth()->user()->name !!}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                @if(auth()->user())
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.index') }}">Início</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.orders.index') }}">Pedidos</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.data.index') }}">Meus Dados</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.favorites.index') }}">Favoritos</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.notifications.index') }}">Notificações</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('shopper.vouchers.index') }}">Cupons</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Sair</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Entrar</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item nav-mobile">
                            <a href="{{route('site.cart.index')}}" class="cart nav-link" title="Carrinho">
                                <span class="ti-bag"></span>
                                <span data-bind="text: head.viewModel.totalItems()"></span>
                            </a>
                        </li>
                        <li class="nav-item nav-mobile" data-bind="visible: head.viewModel.totalItemsFavorite() > 0">
                            <a href="{{route('shopper.favorites.index')}}" class="cart nav-link" title="Favoritos">
                                <span class="ti-heart"></span>
                                <span data-bind="text: head.viewModel.totalItemsFavorite()"></span>
                            </a>
                        </li>

                        @if (auth()->user())
                            <li class="nav-item submenu dropdown nav-mobile">
                                <a href="#" class="cart nav-link" title="Notificações">
                                    <span class="ti-bell"></span>
                                    <span data-bind="text: head.viewModel.totalNotifications()"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right notification-area">
                                    <li class="dropdown-header">Você tem {{ auth()->user()->unreadNotifications->count() }} notificações</li>
                                    @foreach(auth()->user()->unreadNotifications()->take(5)->get() as $notification)
                                        @if($notification->type == 'App\Notifications\OrderCommentNotification'
                                            || $notification->type == 'App\Notifications\OrderNotification')
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{route('shopper.orders.index', $notification->data['order'])}}">
                                                <i class="ti-plus"></i> {{ $notification->data['message'] }}
                                                <span><i class="ti-alarm-clock"></i> {{ parseDateToPt($notification->created_at) }}</span>
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('shopper.notifications.index')}}">
                                            Ver todos
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li></li>
                        <li class="nav-item">
                            <button class="search" title="Pesquisar"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="search_input" id="search_input_box">
        <div class="container">
            <form class="d-flex justify-content-between" action="/shop">
                <input type="text" class="form-control" id="search_input" placeholder="Pesquisar" name="search_input">
                <button type="submit" class="btn"></button>
                <span class="lnr lnr-cross" id="close_search" title="Fechar pesquisa"></span>
            </form>
        </div>
    </div>
</header>
<!-- End Header Area -->

<script type="text/javascript">

    function head(){[native/code]}
    head.cartTotal = {{ $cartTotal }};
    head.favoriteTotal = {{ $favoriteTotal }};
    head.notificationTotal = {{ auth()->user() ? auth()->user()->unreadNotifications->count() : 0 }};
    head.urlAddProductCart = "{{ route('site.cart.store') }}";
    head.urlAddProductFavorite = "{{ route('shopper.favorite.store') }}";

    head.ViewModel = function()
    {
        let self = this;
        self.navSelected = ko.observable();

        self.totalItems = ko.observable(head.cartTotal);
        self.totalItemsFavorite = ko.observable(head.favoriteTotal);
        self.totalNotifications = ko.observable(head.notificationTotal);

        self.addProductCart = function(sku, amountAux) {
            let amount = undefined === amountAux ? 1 : amountAux,
            params = {
                'sku': sku,
                'amount': amount
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                Alert.info('Produto adicionado ao carrinho com sucesso.');
                self.totalItems(self.totalItems() + amount);
            };
            base.post(head.urlAddProductCart, params, callback);
        }

        self.addProductFavorite = function(sku) {
            params = {
                'sku': sku
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                Alert.info('Produto favoritado com sucesso.');
                self.totalItemsFavorite(self.totalItemsFavorite() + 1);
            };
            base.post(head.urlAddProductFavorite, params, callback);
        }
    }

    head.viewModel = new head.ViewModel();
    ko.applyBindings(head.viewModel, document.getElementById('koHead'));
</script>
