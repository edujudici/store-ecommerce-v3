<detail-area></detail-area>
<template id="template-detail-area">
    <!--================Single Product Area =================-->
	<div data-bind="with: product">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="s_Product_carousel" data-bind="foreach: pictures">
						<div class="single-prd-item">
							<img class="img-fluid" alt="" data-bind="attr: {src: pic_image ? base.displayImage(pic_image) : pic_secure_url}"
                                style="max-height: 500px; width: auto !important; margin: 0 auto;">
						</div>
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3 data-bind="text: title"></h3>
						<h2 data-bind="text: price"></h2>
                        <div class="row">
                            <div class="col-3 col-lg-2">
                                <span>Categoria: </span>
                            </div>
                            <div class="col-9 col-lg-10">
                                <span data-bind="text: category"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 col-lg-2">
                                <span>Status: </span>
                            </div>
                            <div class="col-9 col-lg-10">
                                <span>Disponível</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="mb-4" data-bind="text: description"></p>
                            </div>
                        </div>
						<div class="product_count">
							<label for="qty">Quantidade:</label>
							<input type="number" name="qty" id="sst" value="1" title="Quantidade:" class="input-text qty" data-bind="value: quantity">
                            <!-- ko if: inventory == 1 -->
                                <small class="ml-2"> (<span data-bind="text: inventory"></span>) item disponível</small>
                            <!-- /ko -->
                            <!-- ko if: inventory > 1 -->
                                <small class="ml-2"> (<span data-bind="text: inventory"></span>) itens disponíveis</small>
                            <!-- /ko -->
                        </div>
						<div class="card_area d-flex align-items-center">
							<a class="primary-btn" data-bind="click: head.viewModel.addProductCart.bind($data, sku, quantity())">Adicionar ao carrinho</a>
							{{-- <a class="icon_btn" href="#"><i class="lnr lnr lnr-diamond"></i></a> --}}
							<a class="icon_btn" data-bind="click: head.viewModel.addProductFavorite.bind($data, sku)"><i class="lnr lnr lnr-heart"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->
</template>

<script type="text/javascript">

    function detail(){[native/code]}
    detail.urlGetProduct = "{{ route('api.products.show') }}";
    detail.urlGetPictures = "{{ route('api.pictures.index') }}";
    detail.productLongDesc = ko.observable();

    detail.Product = function(obj) {
        let self = this;
        self.sku = obj.pro_sku;
        self.price = base.numeroParaMoeda(obj.pro_price);
        self.oldPrice = base.numeroParaMoeda(obj.pro_oldprice);
        self.title = obj.pro_title;
        self.description = obj.pro_description;
        self.longDesc = obj.pro_description_long;
        self.category = obj.category ? obj.category.cat_title : obj.category_m_l ? obj.category_m_l.cat_title : '';
        self.pictures = ko.observableArray();
        self.quantity = ko.observable(1);
        self.inventory = obj.pro_inventory;

        detail.productLongDesc(self.longDesc);

        self.loadPictures = function() {
            let params = {
                'sku': self.sku,
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.pictures(data.response);

                setTimeout(function() {
                    self.activeCarousel();
                }, 500);
            };
            base.post(detail.urlGetPictures, params, callback, 'GET');
        }
        self.loadPictures();

        self.activeCarousel = function() {
            let isLoop = self.pictures().length > 1 ? true : false;

            $(".s_Product_carousel").owlCarousel({
                items:1,
                autoplay:false,
                autoplayTimeout: 5000,
                loop: isLoop,
                nav:false,
                dots:true
            });
        }
    }

    detail.DetailAreaViewModel = function(params) {
        var self = this;

        self.product = ko.observable();

        self.init = function() {
            let params = {
                'sku': '{{$sku}}',
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.product(new detail.Product(data.response));
            };
            base.post(detail.urlGetProduct, params, callback, 'GET');
        }
        self.init();
    }

	ko.components.register('detail-area', {
	    template: { element: 'template-detail-area'},
	    viewModel: detail.DetailAreaViewModel
	});
</script>
