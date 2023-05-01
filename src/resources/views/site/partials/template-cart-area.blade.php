<cart-area></cart-area>
<template id="template-cart-area">
    <!--================Cart Area =================-->
    <section class="cart_area" data-bind="visible: products().length == 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Seu carrinho esta vazio!</h1>
                        <a href="{{route('site.shop.index')}}">
                            <p>Ir para listagem de produtos</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="cart_area" data-bind="visible: products().length > 0">
        <div class="container">
            <div class="card">
                <div class="card-header bg-dark text-light">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    Meu carrinho
                    <a href="{{route('site.shop.index')}}" class="btn btn-outline-info btn-sm pull-right">Continuar comprando</a>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">

                        <!-- ko foreach: products-->
                        <!-- PRODUCT -->
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-2 text-center">
                                <img class="img-responsive" data-bind="attr: {src: image}" alt="prewiew" height="80">
                            </div>
                            <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                                <h4 class="product-name"><strong data-bind="text: title"></strong></h4>
                            </div>
                            <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                                <div class="col-5 col-sm-5 col-md-6 text-md-right" style="padding-top: 5px">
                                    <h6><strong data-bind="text: base.numeroParaMoeda(total())"> <span class="text-muted">x</span></strong></h6>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4">
                                    <div class="quantity">
                                        <input type="button" value="+" class="plus" data-bind="click: increaseAmount">
                                        <input type="number" step="1" max="99" min="1" title="Qty" class="qty"
                                            size="4" data-bind="value: amount">
                                        <input type="button" value="-" class="minus" data-bind="click: decreaseAmount">
                                    </div>
                                </div>
                                <div class="col-3 col-sm-3 col-md-2 text-right">
                                    <button type="button" class="btn btn-outline-danger btn-xs" data-bind="click: deleteProductCart">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- END PRODUCT -->
                        <!-- /ko -->
                    <div class="row">
                        <div class="col-sm-12 col-md-7 freight-calculate">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h2 class="text-center">
                                        <span class="ti-location-pin"></span>
                                        <br>
                                        Calcular frete!
                                    </h2>
                                    <div class="cupon_text d-flex justify-content-center">
                                        <input class="freight-calculate-input" type="text" placeholder="Informe o CEP" name="zipcode" data-bind="value: zipcode">
                                        <a class="primary-btn freight-calculate-button" href="#" data-bind="click: freightCalculate">Buscar</a>
                                    </div>
                                    <div class="text-center">
                                        <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/" target="_blank">
                                            Não sei o cep
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12">
                                    <!-- ko foreach: freightServices -->
                                    <div class="d-flex">
                                        <div class="primary-radio ml-2 mr-2 mt-1">
                                            <input type="radio" name="freightGroup" data-bind="attr: {id: code}, value: code, checked: cartAreaViewModel.freightSelected">
                                            <label style="border: 1px solid #1468a7" data-bind="attr: {for: code}"></label>
                                        </div>
                                        <p>
                                            <span data-bind="text: serviceName"></span> - Em até
                                            <span data-bind="text: deliveryTime"></span>
                                            <!-- ko if: price != 0 -->
                                            dia(s), por R$ <span data-bind="text: price"></span>
                                            <!-- /ko -->
                                            <!-- ko if: price == 0 -->
                                            dia(s) úteis - GRÁTIS
                                            <!-- /ko -->
                                        </p>
                                    </div>
                                    <!-- /ko -->
                                    <!-- ko if: isLoadingFreight-->
                                    <div class="d-flex">
                                        Carregando opções de frete ...
                                    </div>
                                    <!-- /ko -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h2 class="text-center">
                                        <span class="ti-gift"></span>
                                        <br>
                                        Vale de desconto!
                                    </h2>
                                    <div class="cupon_text d-flex justify-content-center">
                                        <input class="freight-calculate-input" type="text" placeholder="Informe o código" name="voucher" data-bind="value: voucher">
                                        <a class="primary-btn freight-calculate-button" href="#" data-bind="click: applyVoucher">Aplicar</a>
                                    </div>
                                    <p class="mt-3 text-info text-justify" data-bind="visible: voucherValue() > 0">
                                        <span class="ti-alert"></span>
                                        Descontos serão aplicados somente nos produtos, valores de frete serão cobrados normalmente.
                                    </p>
                                    <p class="mt-3 text-info text-justify" data-bind="visible: voucherMessage">
                                        <span class="ti-alert"></span>
                                        Um novo vale com o valor <span data-bind="text: base.numeroParaMoeda(voucherValue() - subtotal())"></span> será gerado e disponibilizado na área 'Cupons'.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 text-right">
                            <span>
                                Subtotal: <b data-bind="text: base.numeroParaMoeda(subtotal())"></b>
                            </span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 text-right" data-bind="visible: voucherValue() > 0">
                            <span>
                                Descontos: - <b data-bind="text: base.numeroParaMoeda(voucherValue())"></b>
                            </span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 text-right">
                            <span>
                                Entrega: <b data-bind="text: base.numeroParaMoeda(freightPrice())"></b>
                            </span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 text-right">
                            <span>
                                Total: <b data-bind="text: base.numeroParaMoeda(total())"></b>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div>
                        <a class="primary-btn freight-calculate-button" href="#" data-bind="click: checkout">Prosseguir para o checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
</template>

<script type="text/javascript">

    function cartArea(){[native/code]}
    cartArea.urlGetProductsCart = "{{ route('site.cart.data') }}";
    cartArea.urlGetPostOfficeCodes = "{{ route('api.freight.codes') }}";
    cartArea.urlDeleteProductCart = "{{ route('site.cart.destroy') }}";
    cartArea.urlFreightCalculate = "{{ route('api.freight.calculate') }}";
    cartArea.urlUpdateCart = "{{ route('site.cart.update') }}";
    cartArea.urlCheckout = "{{ route('site.checkout.index') }}";
    cartArea.urlVoucherValid = "{{ route('api.vouchers.valid') }}";

    cartArea.Product = function(obj, vm) {
        let self = this;
        self.sku = obj.sku;
        self.title = obj.title;
        self.image = obj.thumbnail ? obj.thumbnail : base.displayImage(obj.image);
        self.price = obj.price;
        self.amount = ko.observable(obj.amount);
        self.vm = vm;

        self.total = ko.computed(function() {
            return self.amount() * self.price;
        });

        self.amount.subscribeChanged(function (newValue, oldValue) {
            if (parseInt(newValue) > parseInt(oldValue)) {
                head.viewModel.totalItems(head.viewModel.totalItems() + 1);
            } else {
                head.viewModel.totalItems(head.viewModel.totalItems() - 1);
            }
        });

        self.deleteProductCart = function(item) {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) {
                        if (parseInt(self.amount()) == 0) {
                            self.increaseAmount();
                        }
                        return;
                    }

                    let params = {
                        'sku': self.sku,
                    },
                    callback = function(data) {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        }
                        Alert.info('Produto removido do carrinho com sucesso.');
                        self.vm.products.remove(item);
                        head.viewModel.totalItems(head.viewModel.totalItems() - self.amount());
                    };
                    base.post(cartArea.urlDeleteProductCart, params, callback, 'DELETE');
                }
            );
        }

        self.increaseAmount = function() {
            self.amount(parseInt(self.amount()) +1);
        }
        self.decreaseAmount = function() {
            if (parseInt(self.amount()) > 0) {
                self.amount(parseInt(self.amount()) -1);
            }
            if (parseInt(self.amount()) == 0) {
                self.deleteProductCart(self);
            }
        }
    }

    cartArea.Freight = function(obj) {
        var self = this;

        self.code = obj.Codigo;
        self.price = obj.Valor;
        self.deliveryTime = obj.PrazoEntrega;
        self.serviceName = obj.ServicoNome;
    }

    cartArea.CartAreaViewModel = function() {
        var self = this;

        self.products = ko.observableArray().extend({
            required: {
                params: true,
                message: 'Necessário ter pelo menos um produto no carrinho'
            }
        });
        self.freightServices = ko.observableArray();
        self.freightSelected = ko.observable().extend({
            required: {
                params: true,
                message: 'Obrigatório selecionar ao menos uma opção de frete'
            }
        });
        self.isLoadingFreight = ko.observable(false);
        self.zipcode = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo cep é obrigatório',
                onlyIf: function() {
                    return !self.freightSelected();
                }
            }
        });
        self.voucher = ko.observable();
        self.voucherValue = ko.observable(0);
        self.voucherMessage = ko.observable(false);
        self.errors = ko.validation.group(self);

        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.products(ko.utils.arrayMap(Object.keys(data.response.products), function(id) {
                    return new cartArea.Product(data.response.products[id], self);
                }));
                self.zipcode(data.response.zipcode);
                self.freightSelected(data.response.freightData.code);
                self.freightServices(data.response.freightServices);
                if (data.response.freightServices.length === 0) {
                    self.freightServices.push(new cartArea.Freight({
                        'Codigo': -1,
                        'Valor': 0,
                        'PrazoEntrega': 4,
                        'ServicoNome': 'Retirar no local',
                    }));
                }
                self.voucher(data.response.voucher);
                self.voucherValue(data.response.voucherValue);

            };
            base.post(cartArea.urlGetProductsCart, null, callback, 'GET');
        }
        self.init();

        self.subtotal = ko.computed(function() {
            return self.products().reduce(function(total, product) {
                return total += parseFloat(product.total())
            }, 0);
        });

        self.freightPrice = ko.computed(function() {
            if (self.freightSelected()) {
                let freigt = ko.utils.arrayFirst(self.freightServices(), function(item) {
                    return item.code == self.freightSelected();
                });
                return freigt ? parseFloat(freigt.price.toString().replace(',', '.')) : 0;
            }
            return 0;
        });

        self.total = ko.computed(function() {
            self.voucherMessage(false);

            let total = self.subtotal() - self.voucherValue();
            if (total < 0) {
                self.voucherMessage(true);
                total = 0.00;
            }

            total += self.freightPrice();

            return total;
        });

        self.freightCalculate = function() {

            if (!self.zipcode()) {
                Alert.error('Obrigatório informar um cep.');
                return;
            }

            self.freightServices.splice(1, self.freightServices().length-1) ;
            self.isLoadingFreight(true);

            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                ko.utils.arrayForEach(Object.keys(data.response), function(codeName) {
                    let params = {
                        'zipcode': self.zipcode(),
                        'serviceCode': data.response[codeName]
                    },
                    callback = function(data) {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        }
                        self.isLoadingFreight(false);

                        if (data.response) {
                            if (data.response.Erro !== '0') {
                                Alert.error(data.response.MsgErro);
                                return;
                            }
                            self.freightServices.push(new cartArea.Freight(data.response));
                        }
                    };
                    base.post(cartArea.urlFreightCalculate, params, callback);
                });
            };
            base.post(cartArea.urlGetPostOfficeCodes, null, callback);
        }

        self.voucher.subscribe(function(value) {
            if (!value) {
                self.voucherValue(0);
            }
        });

        self.applyVoucher = function() {
            if (!self.voucher()) {
                Alert.error('Obrigatório informar um vale.');
                return;
            }

            let params = {
                'code': self.voucher()
            },
            callback = function(data) {
                if(!data.status) {
                    self.voucher(null);
                    Alert.error(data.message);
                    return;
                }
                self.voucherValue(data.response.vou_value);
                Alert.success('Vale adicionado com sucesso!');
            };
            base.post(cartArea.urlVoucherValid, params, callback);
        }

        self.checkout = function() {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            let params = {
                'products': ko.utils.arrayMap(self.products(), function(item) {
                    return {
                        'sku': item.sku,
                        'price': item.price,
                        'amount': item.amount(),
                        'total': item.total(),
                    }
                }),
                'zipcode': self.zipcode(),
                'freightSelected': self.freightSelected(),
                'subtotal': self.subtotal(),
                'voucher': self.voucher(),
                'voucherValue': self.voucherValue(),
                'totalItems': head.viewModel.totalItems(),
                'freightServices': ko.utils.arrayMap(self.freightServices(), function(item) {
                    return {
                        'code': item.code,
                        'price': item.price,
                        'deliveryTime': item.deliveryTime,
                        'serviceName': item.serviceName,
                    }
                }),
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                window.location = cartArea.urlCheckout;
            };
            base.post(cartArea.urlUpdateCart, params, callback, 'PUT');
        }
    }

    let cartAreaViewModel = new cartArea.CartAreaViewModel();

	ko.components.register('cart-area', {
	    template: { element: 'template-cart-area'},
	    viewModel: { instance: cartAreaViewModel }
    });

    ko.subscribable.fn.subscribeChanged = function (callback) {
        var oldValue;
        this.subscribe(function (_oldValue) {
            oldValue = _oldValue;
        }, this, 'beforeChange');

        this.subscribe(function (newValue) {
            callback(newValue, oldValue);
        });
    };
</script>
