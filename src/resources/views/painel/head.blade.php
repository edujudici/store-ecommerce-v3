<!-- Header -->
<header class="main-header " id="koHeader">
    <nav class="navbar navbar-static-top navbar-expand-lg">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>
        <!-- search form -->
        <div class="search-form d-none d-lg-inline-block">
            <div class="input-group">
                <button type="button" name="search" id="search-btn" class="btn btn-flat">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <input type="text" name="query" id="search-input" class="form-control"
                    placeholder="perguntas, pedidos, etc." autofocus autocomplete="off" />
            </div>
            <div id="search-results-container">
                <ul id="search-results"></ul>
            </div>
        </div>

        <div class="navbar-right ">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <button class="dropdown-toggle" data-toggle="dropdown">
                        <i class="mdi"
                            data-bind="css: {'mdi-bell-outline': totalNotifications() > 0, 'mdi-bell-off-outline': totalNotifications() == 0}"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header">Você tem <b data-bind="text: totalNotifications"></b> notificações
                        </li>
                        <li data-bind="visible: totalContacts() > 0">
                            <a href="{{ route('painel.contacts.index') }}">
                                <i class="mdi mdi-email"></i> Novos Contatos
                            </a>
                        </li>
                        <li data-bind="visible: totalNewsletters() > 0">
                            <a href="{{ route('painel.newsletters.index') }}">
                                <i class="mdi mdi-email-outline"></i> Novas Newsletters
                            </a>
                        </li>
                        <li data-bind="visible: totalOrders() > 0">
                            <a href="{{ route('painel.orders.index') }}">
                                <i class="mdi mdi-cart-outline"></i> Novos Pedidos Efetuados
                            </a>
                        </li>
                        <li data-bind="visible: totalCommentsProduct() > 0">
                            <a href="{{ route('painel.products.comments.index') }}">
                                <i class="mdi mdi-comment-text-multiple-outline"></i> Comentários de Produtos
                            </a>
                        </li>
                        <li data-bind="visible: totalCommentsOrder() > 0">
                            <a href="{{ route('painel.orders.comments.index') }}">
                                <i class="mdi mdi-comment-text-multiple"></i> Comentários de Pedido
                            </a>
                        </li>
                        <li data-bind="visible: totalShoppers() > 0">
                            <a href="{{ route('painel.users.index') }}">
                                <i class="mdi mdi-account-group"></i> Novos Clientes Cadastrados
                            </a>
                        </li>
                        <li data-bind="visible: totalApprovedPayments() > 0">
                            <a href="{{ route('painel.orders.index') }}">
                                <i class="mdi mdi-cash-multiple"></i> Novos Pagamentos Aprovados
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- User Account -->
                <li class="dropdown user-menu">
                    <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <img src="{{ asset('assets/painel/img/user/user.png') }}" class="user-image" alt="User Image" />
                        <span class="d-none d-lg-inline-block">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- User image -->
                        <li class="dropdown-header">
                            <img src="{{ asset('assets/painel/img/user/user.png') }}" class="img-circle"
                                alt="User Image" />
                            <div class="d-inline-block">
                                {{ Auth::user()->name }} <small class="pt-1">{{ Auth::user()->email }}</small>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('painel.users.index') }}">
                                <i class="mdi mdi-account-plus"></i> {{ __('Novo Administrador') }}
                            </a>
                        </li>
                        <li class="dropdown-footer">
                            <a href="{{ route('painel.logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout"></i> {{ __('Sair') }} </a>

                            <form id="logout-form" action="{{ route('painel.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script type="text/javascript">
    headerNotifications = {!! $notifications !!};

    Notification = function(obj) {
        let self = this;

        self.id = obj.id;
        self.order = obj.data.order;
        self.message = obj.data.message;
        self.date = obj.created_at;
        self.type = obj.type;
    }

    function HeaderViewModel() {
        var self = this;

        self.totalContacts = ko.observable(0);
        self.totalNewsletters = ko.observable(0);
        self.totalOrders = ko.observable(0);
        self.totalCommentsProduct = ko.observable(0);
        self.totalCommentsOrder = ko.observable(0);
        self.totalShoppers = ko.observable(0);
        self.totalApprovedPayments = ko.observable(0);

        self.notifications = ko.observableArray(ko.utils.arrayMap(headerNotifications, function(item) {
            return new Notification(item);
        }));

        self.plusByType = ko.computed(function() {
            if (self.notifications().length > 0) {
                ko.utils.arrayForEach(self.notifications(), function(item) {
                    const type = item.type.replace('App\\Notifications\\', '');
                    switch (type) {
                        case 'ContactNotification':
                            self.totalContacts(self.totalContacts() + 1)
                            break;
                        case 'NewsletterNotification':
                            self.totalNewsletters(self.totalNewsletters() + 1)
                            break;
                        case 'OrderNotification':
                            self.totalOrders(self.totalOrders() + 1)
                            break;
                        case 'ProductCommentNotification':
                            self.totalCommentsProduct(self.totalCommentsProduct() + 1)
                            break;
                        case 'OrderCommentNotification':
                            self.totalCommentsOrder(self.totalCommentsOrder() + 1)
                            break;
                        case 'UserNotification':
                            self.totalShoppers(self.totalShoppers() + 1)
                            break;
                        case 'OrderPaidNotification':
                            self.totalApprovedPayments(self.totalApprovedPayments() + 1)
                            break;
                        default:
                            Alert.error('Tipo de notificação não encontrado: ' + type);
                    }
                });
            }
        });

        self.totalNotifications = ko.computed(function() {
            return self.totalContacts() +
                self.totalNewsletters() +
                self.totalOrders() +
                self.totalCommentsProduct() +
                self.totalCommentsOrder() +
                self.totalShoppers() +
                self.totalApprovedPayments();
        });
    }

    var headerViewModel = new HeaderViewModel();
    ko.applyBindings(headerViewModel, document.getElementById('koHeader'));
</script>
