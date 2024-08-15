<shop-area></shop-area>
<template id="template-shop-area">
    <a id="sidebarCollapse" class="primary-btn freight-calculate-button" href="#">Filtrar</a>
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-8 col-sm-8 col-md-3 sidebar-mobile">
                <div id="dismiss" class="dismiss-mobile">
                    <i class="fa fa-arrow-left"></i>
                </div>
                <div class="sidebar-header">
                    <h3>Filtros</h3>
                </div>
                <div class="sidebar-categories mt-50">
                    <div class="head">Pesquisar</div>
                    <div class="form-group main-categories mt-3">
                        <input type="text" class="form-control" id="seach" name="seach"
                            placeholder="Digite sua pesquisa" onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Digite sua pesquisa'" data-bind="value: searchFiltered">
                    </div>
                </div>
                <div class="sidebar-categories">
                    <div class="head">Ordenar</div>
                    <div class="form-group main-categories mt-3">
                        <select class="form-control" data-bind="
                            options: filterOrdered,
                            optionsText: 'title',
                            optionsValue: 'id',
                            value: filterOrderedSelected">
                        </select>
                    </div>
                </div>
                <div class="sidebar-categories">
                    <div class="head">Preços</div>
                    <ul class="main-categories" data-bind="foreach: filterPrices">
                        <li class="main-nav-list"
                            data-bind="style: {'background-color': vm.filterPriceSelected() == id ? '#cccccc4a' : ''}">
                            <a data-bind="click: filterPrice">
                                <span data-bind="text: description"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-categories">
                    <div class="head">Categorias</div>
                    <ul class="main-categories" style="position: relative; max-height: 505px; overflow: auto;"
                        data-bind="foreach: categories">
                        <li class="main-nav-list"
                            data-bind="style: {'background-color': vm.filterCategorySelected() == idSelected ? '#cccccc4a' : ''}">
                            <a data-bind="click: filterCategory">
                                <span data-bind="text: title"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-9">

                <!-- Start Filter Bar -->
                <div class="row filter-bar d-flex flex-wrap align-items-center">
                    <div class="sorting mr-auto">
                        <select class="form-control" data-bind="
                            options: filterAmounts,
                            optionsText: 'title',
                            optionsValue: 'id',
                            value: filterAmountSelected">
                        </select>
                    </div>
                    <!-- ko if: pagination() !== '' -->
                    <div class="pagination" data-bind="html: pagination"></div>
                    <!-- /ko -->
                </div>
                <!-- End Filter Bar -->

                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">

                    <div class="row" data-bind="visible: products().length == 0" style="display: none">
                        <div class="col-12 col-sm-12 col-lg-12 col-md-12">
                            <h1 style="text-align: center;">Nenhum produto encontrado</h1>
                        </div>
                    </div>

                    <div class="row" data-bind="foreach: products">
                        <!-- single product -->
                        <div class="col-6 col-sm-6 col-lg-3 col-md-4" style="border: 0.1px solid #ccc;  ">
                            <div class="single-product">
                                <div class="product-details">
                                    <a data-bind="attr: {href: detailUrl}">
                                        <div class="container-product">
                                            <img alt="" data-bind="attr: {src: image}">
                                        </div>
                                        <h6 data-bind="text: title, attr: {title: title}"></h6>
                                    </a>
                                    <div class="price">
                                        <h6 class="l-through" data-bind="text: oldPrice, visible: price != oldPrice">
                                        </h6>
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
                </section>
                <!-- End Best Seller -->

                <!-- ko if: pagination() !== '' -->
                <!-- Start Filter Bar -->
                <div class="row filter-bar d-flex flex-wrap align-items-center mb-3">
                    <div class="sorting mr-auto">
                    </div>
                    <div class="pagination" data-bind="html: pagination"></div>
                </div>
                <!-- End Filter Bar -->
                <!-- /ko -->
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    function shopArea(){[native/code]}
    shopArea.urlGetCategories = "{{ route('api.categories.index') }}";
    shopArea.urlGetProducts = "{{ route('api.products.index') }}";
    shopArea.urlProductDetail = "{{ route('site.shop.detail') }}/";
    shopArea.search = '{!! $search !!}';

    shopArea.Category = function(obj, vm) {
        let self = this;
        self.id = obj.cat_id;
        self.idSecondary = obj.cat_id_secondary;
        self.title = obj.cat_title;
        self.vm = vm;
        self.idSelected = self.id == -1
            ? undefined
            : obj.cat_id_secondary ? obj.cat_id_secondary : self.id;

        self.filterCategory = function()
        {
            self.vm.filterCategorySelected(self.idSelected);
        }
    }

    shopArea.Price = function(obj, vm) {
        let self = this;
        self.id = obj.price;
        self.description = obj.description;
        self.vm = vm;

        self.filterPrice = function()
        {
            self.vm.filterPriceSelected(self.id);
        }
    }

    shopArea.Product = function(obj) {
        let self = this;
        self.sku = obj.pro_sku;
        self.price = base.numeroParaMoeda(obj.pro_price);
        self.oldPrice = base.numeroParaMoeda(obj.pro_oldprice);
        self.image = obj.pro_secure_thumbnail ? obj.pro_secure_thumbnail : base.displayImage(obj.pro_image);
        self.title = obj.pro_title;
        self.detailUrl = shopArea.urlProductDetail+obj.pro_sku;
    }

    shopArea.ShopAreaViewModel = function(params)
    {
        var self = this;

        self.categories = ko.observableArray();
        self.products = ko.observableArray();
        self.pagination = ko.observable();

        self.page = ko.observable(1);
        self.filterOrdered = ko.observableArray();
        self.filterAmounts = ko.observableArray();
        self.filterPrices = ko.observableArray();
        self.filterOrderedSelected = ko.observable();
        self.filterAmountSelected = ko.observable();
        self.filterCategorySelected = ko.observable(base.getParamUrl('category'));
        self.filterPriceSelected = ko.observable();
        self.searchFiltered = ko.observable(shopArea.search);

        self.getCategories = function()
        {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.categories.push(new shopArea.Category({
                    'cat_id': -1,
                    'cat_title': 'Todos'
                }, self));

                ko.utils.arrayForEach(data.response, function(obj) {
                    self.categories.push(new shopArea.Category(obj, self))
                });
            };
            base.post(shopArea.urlGetCategories, null, callback, 'GET');
        }

        self.getProducts = function()
        {
            let params = {
                'page': self.page(),
                'amount': self.filterAmountSelected(),
                'order': self.filterOrderedSelected(),
                'category': self.filterCategorySelected(),
                'price': self.filterPriceSelected(),
                'search': self.searchFiltered(),
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.pagination(data.response.pagination);
                self.products(ko.utils.arrayMap(data.response.products, function(obj) {
                    return new shopArea.Product(obj)
                }));
                $('#dismiss').click();
                $(window).scrollTop(300);

                self.eventFilter();
            };
            base.post(shopArea.urlGetProducts, params, callback, 'GET');
        }

        self.getFilterOrdereds = function()
        {
            return [
                {'id': 'sold', 'title': 'Mais Vendido'},
                {'id': 'asc', 'title': 'Menor preço'},
                {'id': 'desc', 'title': 'Maior preço'},
            ];
        }

        self.getFilterAmounts = function()
        {
            return [
                {'id': 12, 'title': 'Exibir 12'},
                {'id': 24, 'title': 'Exibir 24'},
                {'id': 48, 'title': 'Exibir 48'},
            ];
        }

        self.getFilterPrices = function()
        {
            let prices = [
                {'price': 50, 'description': 'De R$1 até R$50'},
                {'price': 100, 'description': 'De R$1 até R$100'},
                {'price': 200, 'description': 'De R$1 até R$200'},
                {'price': -1, 'description': 'Todos os valores'},
            ];
            return ko.utils.arrayMap(prices, function(item) {
                return new shopArea.Price(item, self)
            });
        }

        self.eventFilter = function()
        {
            $('.page-link').on('click', function(event) {
                event.preventDefault();

                var url = $(this).attr('href');
                let page = base.getParameterByName('page', url);

                self.filter(page);

            });

            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#dismiss, .overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
            });
        }

        self.init = function()
        {
            self.getCategories();
            self.getProducts();
            self.filterOrdered(self.getFilterOrdereds())
            self.filterAmounts(self.getFilterAmounts())
            self.filterPrices(self.getFilterPrices())
        }
        self.init();

        self.filter = function(page)
        {
            self.page(page);
            self.getProducts();
        }

        self.searchFiltered.subscribe(function(value) {
            self.filter(self.page());
        });
        let previousAmount = self.filterAmountSelected();
        self.filterAmountSelected.subscribe(function(value) {
            if (previousAmount !== undefined) {
                self.filter(self.page());
            }

            previousAmount = value;
        })
        let previousOrdered = self.filterOrderedSelected();
        self.filterOrderedSelected.subscribe(function(value) {
            if (previousOrdered !== undefined) {
                self.filter(self.page());
            }

            previousOrdered = value;
        })
        self.filterCategorySelected.subscribe(function(value) {
            self.filter(self.page());
        })
        self.filterPriceSelected.subscribe(function(value) {
            self.filter(self.page());
        })
    }

	ko.components.register('shop-area', {
	    template: { element: 'template-shop-area'},
	    viewModel: shopArea.ShopAreaViewModel
	});
</script>
