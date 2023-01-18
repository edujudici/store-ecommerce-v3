<home-product-area></home-product-area>
<template id="template-home-product-area">
    <!-- start product Area -->
    <section class="category-area mb-5" data-bind="visible: products().length > 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h1>Produtos Recentes</h1>
                        <p>Aqui você encontra tudo que tem de novidade sendo lançada a todo momento para você!</p>
                        <p>Confira abaixo e aproveite!</p>
                    </div>
                </div>
            </div>
            <div class="owl-carousel active-product-area section_gap">
                <!-- ko foreach: products -->
                    <!-- single product slide -->
                    <div class="single-product-slider">
                        <div class="container">
                            <div class="row" data-bind="foreach: $data">
                                <div class="col-6 col-sm-6 col-lg-3 col-md-4" style="border: 0.1px solid #ccc;  ">
                                    <div class="single-product">
                                        <div class="product-details">
                                            <a data-bind="attr: {href: detailUrl}">
                                                <div class="container-product">
                                                    <img alt="" data-bind="attr: {src: image}">
                                                </div>
                                                <h6 data-bind="text: description, attr: {title: description}"></h6>
                                            </a>
                                            <div class="price">
                                                <h6 class="l-through" data-bind="text: oldPrice, visible: price != oldPrice"></h6>
                                                <h6 data-bind="text: price"></h6>
                                            </div>
                                            <div class="prd-bottom">
                                                <a class="social-info" data-bind="click: head.viewModel.addProductCart.bind($data, sku, 1)">
                                                    <span class="ti-bag"></span>
                                                    <p class="hover-text">Carrinho</p>
                                                </a>
                                                <a href="" class="social-info" data-bind="click: head.viewModel.addProductFavorite.bind($data, sku)">
                                                    <span class="lnr lnr-heart"></span>
                                                    <p class="hover-text">Favoritar</p>
                                                </a>
                                                <a class="social-info dismiss-web" data-bind="attr: {href: detailUrl}">
                                                    <span class="lnr lnr-move"></span>
                                                    <p class="hover-text">Detalhes</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- /ko -->
            </div>
        </div>
    </section>
    <!-- end product Area -->
</template>

<script type="text/javascript">

    function homeProduct(){[native/code]}
    homeProduct.urlGetProducts = "{{ route('api.products.index.format') }}";
    homeProduct.urlProductDetail = "{{ route('site.shop.detail') }}/";

    homeProduct.Product = function(obj) {
        let self = this;
        self.sku = obj.pro_sku;
        self.price = base.numeroParaMoeda(obj.pro_price);
        self.oldPrice = base.numeroParaMoeda(obj.pro_oldprice);
        self.image = obj.pro_secure_thumbnail ? obj.pro_secure_thumbnail : base.displayImage(obj.pro_image);
        self.description = obj.pro_description;
        self.detailUrl = homeProduct.urlProductDetail+obj.pro_sku;
    }

    homeProduct.ProductViewModel = function() {
        let self = this;

        self.products = ko.observableArray();

        self.init = function() {
            let params = {
                'order': 'recent',
                'amount': 8,
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                ko.utils.arrayForEach(data.response, function(subList) {
                    let tempList = ko.utils.arrayMap(subList, function(obj) {
                        return new homeProduct.Product(obj)
                    });

                    self.products.push(tempList);
                });

                if (self.products().length >= 2)
                {
                    setTimeout(function() {
                        homeProduct.activeProduct();
                    }, 500);
                }
            };
            base.post(homeProduct.urlGetProducts, params, callback, 'GET');
        }
        self.init();
    }
    homeProduct.activeProduct = function() {
        $(".active-product-area").owlCarousel({
            items:1,
            autoplay:false,
            autoplayTimeout: 5000,
            loop:true,
            nav:true,
            navText:["<img src='{{asset('assets/site/img/elements/prev.png')}}'>","<img src='{{asset('assets/site/img/elements/next.png')}}'>"],
            dots:false
        });
    }

	ko.components.register('home-product-area', {
	    template: { element: 'template-home-product-area'},
	    viewModel: homeProduct.ProductViewModel
	});
</script>
