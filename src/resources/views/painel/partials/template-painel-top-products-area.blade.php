<painel-top-products-area></painel-top-products-area>
<template id="template-painel-top-products-area">
    <!--================Top Products Area =================-->
    <div class="card card-default" data-scroll-height="580">
        <div class="card-header justify-content-between mb-4">
            <h2>Produtos mais vendidos</h2>
        </div>
        <div class="card-body py-0">
            <!-- ko foreach: products-->
                <div class="media d-flex mb-5">
                    <div class="media-image align-self-center mr-3 rounded">
                        <a href="#"><img alt="Produto" data-bind="attr: {src: image}" width="120" height="80"></a>
                    </div>
                    <div class="media-body align-self-center">
                        <a href="#">
                            <h6 class="mb-3 text-dark font-weight-medium" data-bind="text: 'sku: ' + sku"></h6>
                        </a>
                        <p class="float-md-right"><span class="text-dark mr-2" data-bind="text: amount"></span>Vendas</p>
                        <p class="d-none d-md-block" data-bind="text: description"></p>
                        <p class="mb-0">
                            <del data-bind="text: oldPrice"></del>
                            <span class="text-dark ml-3" data-bind="text: price"></span>
                        </p>
                    </div>
                </div>
            <!-- /ko -->
        </div>
    </div>
	<!--================End Top Products Area =================-->
</template>

<script type="text/javascript">

    function topProducts(){[native/code]}
    topProducts.urlData = "{{ route('api.dashboard.topProducts') }}";

    topProducts.Product = function(obj) {
        let self = this;

        self.sku = obj.ori_pro_sku;
        self.priceTotal = ko.observable(obj.price);
        self.amount = ko.observable(obj.amount);
        self.description = ko.observable(obj.product ? obj.product.pro_description : '');
        self.image = obj.product
            ? obj.product.pro_secure_thumbnail
                ? obj.product.pro_secure_thumbnail
                : base.displayImage(obj.product.pro_image)
            : '';
        self.price = obj.product ? base.numeroParaMoeda(obj.product.pro_price) : 0;
        self.oldPrice = obj.product ? base.numeroParaMoeda(obj.product.pro_oldprice) : 0;
    }

    topProducts.topProductsViewModel = function(params) {
        var self = this;

        self.products = ko.observableArray();

        let callback = function(data) {
            if(!data.status) {
                Alert.error(data.message);
                return;
            }

            self.products(ko.utils.arrayMap(data.response, function(item) {
                return new topProducts.Product(item);
            }));

        };
        base.post(topProducts.urlData, null, callback);
    }

	ko.components.register('painel-top-products-area', {
	    template: { element: 'template-painel-top-products-area'},
	    viewModel: topProducts.topProductsViewModel
	});
</script>
