<home-exclusive-deal-area></home-exclusive-deal-area>
<template id="template-home-exclusive-deal-area">
    <!-- Start exclusive deal Area -->
    <section class="category-area mb-5" data-bind="visible: products().length > 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h1>Promoção</h1>
                        <p>Encontre aqui as melhores ofertas para o que procura é muito fácil.</p>
                        <p>Super descontos, confira abaixo!</p>
                    </div>
                </div>
            </div>
            <div class="owl-carousel active-product-area section_gap exclusive">
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
                                            <h6 class="l-through"
                                                data-bind="text: oldPrice, visible: price != oldPrice"></h6>
                                            <h6 data-bind="text: price"></h6>
                                        </div>
                                        <div class="prd-bottom">
                                            <a class="social-info"
                                                data-bind="click: head.viewModel.addProductCart.bind($data, sku, 1)">
                                                <span class="ti-bag"></span>
                                                <p class="hover-text">Carrinho</p>
                                            </a>
                                            <a href="" class="social-info"
                                                data-bind="click: head.viewModel.addProductFavorite.bind($data, sku)">
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
    <!-- End exclusive deal Area -->
</template>

<script type="text/javascript">
    function homeProductExclusive(){[native/code]}
    homeProductExclusive.urlGetProducts = "{{ route('api.productsExclusives.index') }}";
    homeProductExclusive.urlProductDetail = "{{ route('site.shop.detail') }}/";

    homeProductExclusive.Product = function(obj) {
        let self = this;
        self.sku = obj.pro_sku;
        self.price = base.numeroParaMoeda(obj.product.pro_price);
        self.oldPrice = base.numeroParaMoeda(obj.product.pro_oldprice);
        self.image = obj.product.pro_secure_thumbnail ? obj.product.pro_secure_thumbnail : base.displayImage(obj.product.pro_image);
        self.description = obj.product.pro_description;
        self.detailUrl = homeProductExclusive.urlProductDetail+obj.pro_sku;
    }

    homeProductExclusive.ProductExclusiveViewModel = function() {
        let self = this;

        self.products = ko.observableArray();

        self.init = function() {
            let params = {
                'amount': 24,
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                ko.utils.arrayForEach(data.response, function(subList) {
                    let tempList = ko.utils.arrayMap(subList, function(obj) {
                        return new homeProductExclusive.Product(obj)
                    });

                    self.products.push(tempList);
                });

                if (self.products().length >= 2)
                {
                    setTimeout(function() {
                        homeProductExclusive.activeProduct();
                    }, 500);
                }
            };
            base.post(homeProductExclusive.urlGetProducts, params, callback, 'GET');
        }
        self.init();
    }
    homeProductExclusive.activeProduct = function() {
        $(".active-product-area.exclusive").owlCarousel({
            items:1,
            autoplay:true,
            autoplayTimeout: 5000,
            loop:true,
            nav:true,
            navText:["<img src='{{asset('assets/site/img/elements/prev.png')}}'>","<img src='{{asset('assets/site/img/elements/next.png')}}'>"],
            dots:false
        });
    }

	ko.components.register('home-exclusive-deal-area', {
	    template: { element: 'template-home-exclusive-deal-area'},
	    viewModel: homeProductExclusive.ProductExclusiveViewModel
	});
</script>
