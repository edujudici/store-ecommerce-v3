<!--
    ====================================
    ——— LEFT SIDEBAR WITH FOOTER
    =====================================
-->
<aside class="left-sidebar bg-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="{{ route('painel.dashboard.index') }}">
                <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="30"
                    height="33" viewBox="0 0 30 33">
                    <g fill="none" fill-rule="evenodd">
                        <path class="logo-fill-blue" fill="#7DBCFF" d="M0 4v25l8 4V0zM22 4v25l8 4V0z" />
                        <path class="logo-fill-white" fill="#FFF" d="M11 4v25l8 4V0z" />
                    </g>
                </svg>
                <span class="brand-name">Painel Admin</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-scrollbar" style="padding-bottom: 20px">
            <!-- sidebar menu -->
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <li class="has-sub active">
                    <a class="sidenav-item-link" href="{{ route('painel.dashboard.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="{{ route('painel.orders.index') }}">
                        <i class="mdi mdi-cart"></i>
                        <span class="nav-text">Pedidos</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#communication" aria-expanded="false" aria-controls="communication">
                        <i class="mdi mdi-comment"></i>
                        <span class="nav-text">Comunicação</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="communication" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="has-sub">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#comments" aria-expanded="false" aria-controls="comments">
                                    <span class="nav-text">Comentários</span> <b class="caret"></b>
                                </a>
                                <ul class="collapse" id="comments">
                                    <div class="sub-menu">
                                        <li>
                                            <a href="{{ route('painel.orders.comments.index') }}">
                                                Pedidos
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('painel.products.comments.index') }}">
                                                Produtos
                                                {{-- <span class="badge badge-success">new</span> --}}
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.contacts.index') }}">
                                    <span class="nav-text">E-mails</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.newsletters.index') }}">
                                    <span class="nav-text">Newsletters</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#mercadolivre" aria-expanded="false" aria-controls="mercadolivre">
                        <i class="mdi mdi-account-multiple-plus-outline"></i>
                        <span class="nav-text">Mercado Livre</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="mercadolivre" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.dashboard.index') }}">
                                    <span class="nav-text">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.accounts.index') }}">
                                    <span class="nav-text">Contas</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.comments.index') }}">
                                    <span class="nav-text">Perguntas</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.answers.index') }}">
                                    <span class="nav-text">Respostas Automáticas</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.sellers.index') }}">
                                    <span class="nav-text">Consultar Parceiros</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.mercadolivre.products.load') }}">
                                    <span class="nav-text">Carregar Produtos</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link"
                                    href="{{ route('painel.mercadolivre.comments.history') }}">
                                    <span class="nav-text">Perguntas Sincronizadas</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#freight" aria-expanded="false" aria-controls="freight">
                        <i class="mdi mdi-truck"></i>
                        <span class="nav-text">Configurar Frete</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="freight" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.melhorenvio.index') }}">
                                    <span class="nav-text">Melhor Envio</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#google" aria-expanded="false" aria-controls="google">
                        <i class="mdi mdi-google"></i>
                        <span class="nav-text">Google Shopping</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="google" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.google.index') }}">
                                    <span class="nav-text">Configurar Produtos</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#website" aria-expanded="false" aria-controls="website">
                        <i class="mdi mdi-image-filter-none"></i>
                        <span class="nav-text">Gerenciar Site</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="website" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#products" aria-expanded="false" aria-controls="products">
                                    <span class="nav-text">Produtos</span> <b class="caret"></b>
                                </a>
                                <ul class="collapse" id="products">
                                    <div class="sub-menu">
                                        <li>
                                            <a href="{{ route('painel.categories.index') }}">
                                                Categorias
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('painel.products.index') }}">
                                                Produtos
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.banners.index') }}">
                                    <span class="nav-text">Banners</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.faqs.index') }}">
                                    <span class="nav-text">Perguntas Frequentes</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.features.index') }}">
                                    <span class="nav-text">Características</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.brands.index') }}">
                                    <span class="nav-text">Marcas</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('painel.companies.index') }}">
                                    <span class="nav-text">Dados da Empresa</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="{{ route('painel.vouchers.index') }}">
                        <i class="mdi mdi-gift"></i>
                        <span class="nav-text">Cupons</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="{{ route('painel.users.index') }}">
                        <i class="mdi mdi-account"></i>
                        <span class="nav-text">Usuários</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
