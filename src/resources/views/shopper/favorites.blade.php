@extends('shopper.baseTemplate')

@section('shopperContent')

<favorite-area></favorite-area>
<template id="template-favorite-area">
    <div class="login_form_inner" style="padding: 20px 0;">
        <h2 class="text-center mb-4">Meus favoritos</h2>

        <!-- ko if: favorites().length == 0 -->
        <p>Nenhum produto favorito encontrado</p>
        <!-- /ko -->

        <div class="order-area" data-bind="foreach: favorites">
            <div class="order-item">
                <div class="order-item-header">
                    <span>favoritado em <strong class="acc-delivery-prevision-days delivered" data-bind="text: base.monthStringEn(date)"></strong></span>
                    <span><a class="icon_btn" data-bind="click: deleteFavorite"><i class="lnr lnr lnr-cross-circle"></i></a></span>
                </div>
                <div class="order-item-body">
                    <div class="order-item-product">
                        <figure>
                            <img class="order-item-product-image" data-bind="attr: {src: thumbnail, alt: title}">
                        </figure>
                        <div class="order-item-product-info">
                            <span data-bind="text: title"></span>
                            <p class="text-left">
                                <strong><span data-bind="text: amount"></span> unidade(s) - <span data-bind="text: base.numeroParaMoeda(price)"></span></strong>
                            </p>
                        </div>
                    </div>
                    <a class="primary-btn" target="_blank" data-bind="attr: {href: favorite.urlProductDetail+sku}">Visualizar Item</a>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('shopperScript')
    <script type="text/javascript">

        function favorite(){[native/code]}
        favorite.urlProductDetail = "{{ route('site.shop.detail') }}/";
        favorite.items = {!! json_encode(session('favorite.products', [])) !!};
        favorite.urlDeleteFavorite = "{{ route('shopper.favorite.destroy') }}";

        favorite.Favorite = function(obj, vm) {
            let self = this;

            self.sku = obj.sku;
            self.title = obj.title;
            self.amount = obj.amount;
            self.image = obj.image;
            self.price = obj.price;
            self.thumbnail = obj.thumbnail;
            self.date = obj.date;
            self.vm = vm;

            self.deleteFavorite = function(item) {
                Alert.confirm(
                    'Você realmente quer deletar este item?',
                    'Exclusão',
                    function(resp) {

                        if (!resp) return;

                        let params = {
                            'sku': self.sku,
                        },
                        callback = function(data) {
                            if(!data.status) {
                                Alert.error(data.message);
                                return;
                            }
                            Alert.info('Produto desfavoritado com sucesso.');
                            self.vm.favorites.remove(item);
                            head.viewModel.totalItemsFavorite(head.viewModel.totalItemsFavorite() - 1);
                        };
                        base.post(favorite.urlDeleteFavorite, params, callback, 'DELETE');
                    }
                );
            }
        }

        favorite.FavoriteAreaViewModel = function(params) {
            var self = this;

            self.favorites = ko.observableArray(ko.utils.arrayMap(Object.keys(favorite.items), function(id) {
                return new favorite.Favorite(favorite.items[id], self);
            }));
        }

        ko.components.register('favorite-area', {
            template: { element: 'template-favorite-area'},
            viewModel: favorite.FavoriteAreaViewModel
        });
    </script>
@endsection
