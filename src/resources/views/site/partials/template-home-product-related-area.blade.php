<home-product-related-area></home-product-related-area>
<template id="template-home-product-related-area">
    <!-- Start related-product Area -->
    <section class="related-product-area section_gap" data-bind="visible: productsRelated().length > 0">
        <div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6 text-center">
					<div class="section-title">
						<h1>Veja também</h1>
                        <p>Confira essa grande variedade de produtos em destaque também. Vale a pena!</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-9">
					<div class="row" data-bind="visible: productsRelated().length > 0">
                        <!-- ko foreach: productsRelated -->
						<div class="col-lg-4 col-md-4 col-sm-6 mb-20">
							<div class="single-related-product d-flex">
								<a data-bind="attr: {href: detailUrl}"><img alt="" data-bind="attr: {src: image}" width="70"></a>
								<div class="desc">
									<a href="#" class="title" data-bind="text: title, attr: {href: detailUrl}"></a>
									<div class="price">
                                        <h6 class="l-through" data-bind="text: oldPrice, visible: price != oldPrice"></h6>
										<h6 data-bind="text: price"></h6>
									</div>
								</div>
							</div>
                        </div>
                        <!-- /ko -->
					</div>
                </div>
				<div class="col-lg-3">
					<div class="ctg-right">
						<a href="{{route('site.shop.index')}}" target="_blank">
							<img class="img-fluid d-block mx-auto" src="{{asset('assets/site/img/c5.jpg')}}" alt="">
						</a>
					</div>
				</div>
			</div>
		</div>
    </section>
    <!-- End related-product Area -->
</template>

<script type="text/javascript">

    function homeProductRelated(){[native/code]}
    homeProductRelated.urlGetProductRelated = "{{ route('api.productsRelateds.index.format') }}";
    homeProductRelated.urlProductDetail = "{{ route('site.shop.detail') }}/";

    homeProductRelated.Product = function(obj) {
        let self = this;
        self.price = base.numeroParaMoeda(obj.pro_price);
        self.oldPrice = base.numeroParaMoeda(obj.pro_oldprice);
        self.image = obj.pro_secure_thumbnail ? obj.pro_secure_thumbnail : base.displayImage(obj.pro_image);
        self.title = obj.pro_title;
        self.detailUrl = homeProductRelated.urlProductDetail+obj.pro_sku;
    }

    homeProductRelated.ProductRelatedViewModel = function() {
        let self = this;

        self.productsRelated = ko.observableArray();
        self.init = function() {
            let params = {
                'sku': "{{ isset($sku) ? $sku : 'null'}}"
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.productsRelated(ko.utils.arrayMap(data.response.products, function(obj) {
                    return new homeProductRelated.Product(obj)
                }));
            };
            base.post(homeProductRelated.urlGetProductRelated, params, callback);
        }
        self.init();
    }

	ko.components.register('home-product-related-area', {
	    template: { element: 'template-home-product-related-area'},
	    viewModel: homeProductRelated.ProductRelatedViewModel
	});
</script>
