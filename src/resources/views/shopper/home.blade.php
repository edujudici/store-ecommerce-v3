@extends('shopper.baseTemplate')

@section('shopperContent')
<home-area></home-area>
<template id="template-home-area">
    <!--================Home Menu Area =================-->
    <section class="blog_categorie_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <a href="{{ route('shopper.orders.index') }}">
                        <div class="categories_post">
                            <img src="{{ asset('assets/site/img/shopper/order.jpg') }}" alt="post">
                            <div class="categories_details">
                                <div class="categories_text">
                                    <h5>Pedidos</h5>
                                    <div class="border_line"></div>
                                    <p>Ver minhas compras</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3">
                    <a href="{{ route('shopper.favorites.index') }}">
                        <div class="categories_post">
                            <img src="{{ asset('assets/site/img/shopper/favorite.jpg') }}" alt="post">
                            <div class="categories_details">
                                <div class="categories_text">
                                    <h5>Favoritos</h5>
                                    <div class="border_line"></div>
                                    <p>Meus itens favoritos</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3">
                    <a href="{{ route('shopper.data.index') }}">
                        <div class="categories_post">
                            <img src="{{ asset('assets/site/img/shopper/account.jpg') }}" alt="post">
                            <div class="categories_details">
                                <div class="categories_text">
                                    <h5>Meus Dados</h5>
                                    <div class="border_line"></div>
                                    <p>Visualizar / Editar cadastro</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3">
                    <a href="{{ route('shopper.notifications.index') }}">
                        <div class="categories_post">
                            <img src="{{ asset('assets/site/img/shopper/notification.jpg') }}" alt="post">
                            <div class="categories_details">
                                <div class="categories_text">
                                    <h5>Notificações</h5>
                                    <div class="border_line"></div>
                                    <p>Avisos recebidos e não lidos</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3">
                    <a href="{{ route('shopper.vouchers.index') }}">
                        <div class="categories_post">
                            <img src="{{ asset('assets/site/img/shopper/voucher.jpg') }}" alt="post">
                            <div class="categories_details">
                                <div class="categories_text">
                                    <h5>Cupons</h5>
                                    <div class="border_line"></div>
                                    <p>Meus vales compras</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--================Home Menu Area =================-->
</template>
@endsection

@section('shopperScript')
    <script type="text/javascript">

        function home(){[native/code]}

        home.HomeAreaViewModel = function(params) {
            var self = this;
        }

        ko.components.register('home-area', {
            template: { element: 'template-home-area'},
            viewModel: home.HomeAreaViewModel
        });
    </script>
@endsection
