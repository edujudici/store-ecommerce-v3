<checkout-area></checkout-area>
<template id="template-checkout-area">
    <!--================Checkout Area =================-->
    <section class="checkout_area">
        <div class="container">

            @if (session('status'))
                <div class="order_details">
                    <h3 class="title_confirmation_error">
                        {{ session('status') }}
                    </h3>
                </div>
            @endif

            <div class="billing_details">

                <div class="row">
                    <div class="col-lg-6 mt-5">
                        <div class="col-lg-12">
                            <h3>Endereço de entrega</h3>
                            <div class="row">
                                <!-- ko with: address -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">Nome: *</label>
                                            <input type="text" id="name" class="form-control" name="name" data-bind="value: name() + ' ' + surname()" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Telefone: *</label>
                                            <input type="text" id="phone" class="form-control" name="phone" data-bind="masked: phone, mask: '(99) 9 9999-9999'">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="zipcode">CEP: *</label>
                                            <input type="text" id="zipcode" class="form-control" name="zipcode" data-bind="masked: zipcode, mask: '99999-999'">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="address">Endereço: *</label>
                                            <input type="text" id="address" class="form-control" name="address" data-bind="value: address">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="number">Número: *</label>
                                            <input type="text" id="number" class="form-control" name="number" data-bind="value: number">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="district">Bairro: *</label>
                                            <input type="text" id="district" class="form-control" name="district" data-bind="value: district">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="uf">UF: *</label>
                                            <input type="text" id="uf" class="form-control" name="uf" data-bind="value: uf">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">Cidade: *</label>
                                            <input type="text" id="city" class="form-control" name="city" data-bind="value: city">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="complement">Complemento: </label>
                                            <input type="text" id="complement" class="form-control" name="complement" data-bind="value: complement">
                                        </div>
                                    </div>
                                <!-- /ko -->

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-5">
                        <div class="row mb-5">
                            <div class="col-lg-12">
                                <h3 style="margin-bottom: 0">Detalhes do Pedido</h3>
                                <div class="order_details_table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col" width="70%">Produto</th>
                                                    <th scope="col" width="10%">Quantidade</th>
                                                    <th scope="col" width="20%">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- ko foreach: products -->
                                                    <tr>
                                                        <td>
                                                            <p data-bind="text: title"></p>
                                                        </td>
                                                        <td class="text-right">
                                                            <h5>x <span data-bind="text: amount"></span></h5>
                                                        </td>
                                                        <td class="text-right">
                                                            <p data-bind="text: base.numeroParaMoeda(subtotal)"></p>
                                                        </td>
                                                    </tr>
                                                <!-- /ko -->
                                                <tr>
                                                    <td>
                                                        <h4>Subtotal</h4>
                                                    </td>
                                                    <td>
                                                        <h5></h5>
                                                    </td>
                                                    <td class="text-right">
                                                        <p data-bind="text: base.numeroParaMoeda(subtotal)"></p>
                                                    </td>
                                                </tr>
                                                <tr data-bind="visible: voucherValue > 0">
                                                    <td>
                                                        <h4>Cupom desconto</h4>
                                                    </td>
                                                    <td>
                                                        <h5></h5>
                                                    </td>
                                                    <td class="text-right">
                                                        <p data-bind="text: '- ' + base.numeroParaMoeda(voucherValue)"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4>Frete</h4>
                                                    </td>
                                                    <td>
                                                        <h5></h5>
                                                    </td>
                                                    <td class="text-right">
                                                        <p data-bind="text: base.numeroParaMoeda(freightValue)"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 data-bind="text: freightService"></h4>
                                                    </td>
                                                    <td>
                                                        <h5></h5>
                                                    </td>
                                                    <td class="text-right">
                                                        <p data-bind="text: freightTime + ' dias'"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4>Total</h4>
                                                    </td>
                                                    <td>
                                                        <h5></h5>
                                                    </td>
                                                    <td class="text-right">
                                                        <h4 data-bind="text: base.numeroParaMoeda(total)"></h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="order_box">
                                                        <div class="creat_account">
                                                            <input type="checkbox" id="f-option4" name="selector" data-bind="checked: acceptTerms">
                                                            <label for="f-option4">Eu li e aceito os </label>
                                                            <a href="#" data-toggle="modal" data-target="#privacy-modal">termos & condições*</a>
                                                        </div>
                                                        <a class="primary-btn" href="#" data-bind="click: continuePayment">Prosseguir para o pagamento</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->
</template>

<script type="text/javascript">

    function checkoutArea(){[native/code]}
    checkoutArea.urlCheckout = "{{ route('site.checkout.store') }}";
    checkoutArea.urlSearchZipcode = "{{ route('site.zipcode.index') }}";
    checkoutArea.data = {!! $data !!};
    checkoutArea.sessionProducts = checkoutArea.data.response.products;
    checkoutArea.sessionSubtotal = checkoutArea.data.response.subtotal;
    checkoutArea.sessionFreight = checkoutArea.data.response.freightData;
    checkoutArea.sessionTotal = checkoutArea.data.response.total;
    checkoutArea.sessionVoucher = checkoutArea.data.response.voucher;
    checkoutArea.sessionVoucherValue = checkoutArea.data.response.voucherValue;
    checkoutArea.user = {!! json_encode(auth()->user()) !!};
    checkoutArea.userAddresses = {!! json_encode(auth()->user()->addresses->toArray()) !!};

    checkoutArea.Address = function(obj)
    {
        let self = this;

        self.name = ko.observable(obj.name).extend({
            required: {
                params: true,
                message: 'O campo nome é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo nome não pode ter mais que 255 caracteres.'
            }
        });
        self.surname = ko.observable(obj.surname).extend({
            required: {
                params: true,
                message: 'O campo sobrenome é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo sobrenome não pode ter mais que 255 caracteres.'
            }
        });
        self.phone = ko.observable(obj.phone).extend({
            required: {
                params: true,
                message: 'O campo telefone é obrigatório'
            },
            maxLength: {
                params: 16,
                message: 'O campo telefone não pode ter mais que 16 caracteres.'
            }
        });
        self.zipcode = ko.observable(obj.zipcode).extend({
            required: {
                params: true,
                message: 'O campo cep é obrigatório'
            },
            maxLength: {
                params: 9,
                message: 'O campo cep não pode ter mais que 9 caracteres.'
            }
        });
        self.address = ko.observable(obj.address).extend({
            required: {
                params: true,
                message: 'O campo endereço é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo endereço não pode ter mais que 255 caracteres.'
            }
        });
        self.number = ko.observable(obj.number).extend({
            required: {
                params: true,
                message: 'O campo numero é obrigatório'
            },
            maxLength: {
                params: 11,
                message: 'O campo numero não pode ter mais que 11 caracteres.'
            }
        });
        self.district = ko.observable(obj.district).extend({
            required: {
                params: true,
                message: 'O campo bairro é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo bairro não pode ter mais que 255 caracteres.'
            }
        });
        self.city = ko.observable(obj.city).extend({
            required: {
                params: true,
                message: 'O campo cidade é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo cidade não pode ter mais que 255 caracteres.'
            }
        });
        self.complement = ko.observable(obj.complement).extend({
            maxLength: {
                params: 255,
                message: 'O campo complemento não pode ter mais que 255 caracteres.'
            }
        });
        self.uf = ko.observable(obj.uf).extend({
            maxLength: {
                params: 2,
                message: 'O campo UF não pode ter mais que 2 caracteres.'
            }
        });

        self.errors = ko.validation.group(self);

        self.zipcode.subscribe(function(val) {
            if(val) {
                if (val.toString().replace(/[^0-9]/g, '') == '') {
                    self.zipcode(null);
                } else {
                    self.searchZipcode();
                }
            }
        });
        self.searchZipcode = function()
        {
            let
            zipcode = self.zipcode().toString().replace(/\D/g, ''),
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.address(data.response.street);
                self.district(data.response.district);
                self.city(data.response.city);
                self.uf(data.response.state);

            };
            base.post(checkoutArea.urlSearchZipcode + '/' + zipcode, null, callback, 'GET');
        }
    }

    checkoutArea.checkoutViewModel = function(params) {
        var self = this;

        self.subtotal;
        self.freightValue;
        self.freightTime;
        self.freightService;
        self.total;
        self.products;
        self.email;
        self.voucher;
        self.voucherValue;

        self.acceptTerms = ko.observable();
        self.address = ko.observable();

        self.setFieldsAddress = function()
        {
            let fields = {
                'name': checkoutArea.user.name,
                'surname': checkoutArea.user.surname
            };
            if (checkoutArea.userAddresses.length > 0) {
                let address = ko.utils.arrayFirst(checkoutArea.userAddresses, function(item) {
                    return item.adr_type === 'shipping';
                });
                if (address) {
                    fields.phone = address.adr_phone;
                    fields.zipcode = address.adr_zipcode;
                    fields.address = address.adr_address;
                    fields.number = address.adr_number;
                    fields.district = address.adr_district;
                    fields.city = address.adr_city;
                    fields.complement = address.adr_complement;
                    fields.uf = address.adr_uf;
                 }
            }
            self.address(new checkoutArea.Address(fields));
        }

        self.init = function()
        {
            self.subtotal = checkoutArea.sessionSubtotal;
            self.freightService = checkoutArea.sessionFreight.serviceName;
            self.freightTime = checkoutArea.sessionFreight.deliveryTime;
            self.freightValue = checkoutArea.sessionFreight.price
                ? checkoutArea.sessionFreight.price.replace(',','.')
                : null;
            self.total = checkoutArea.sessionTotal;
            self.products = ko.utils.arrayMap(Object.keys(checkoutArea.sessionProducts),
                function(key) {
                    let item = checkoutArea.sessionProducts[key];
                    item.subtotal = item.price * item.amount;
                    return item;
                })

            self.email = checkoutArea.user.email;
            self.voucher = checkoutArea.sessionVoucher;
            self.voucherValue = checkoutArea.sessionVoucherValue;

            self.setFieldsAddress();
        }
        self.init();

        self.continuePayment = function()
        {
            if (!self.acceptTerms()) {
                Alert.error('Obrigatório aceitar os termos e condições para finalizar o pedido');
                return;
            }
            if (self.address().errors().length > 0) {
                Alert.error('Preencher todos os campos obrigatórios do endereço');
                return;
            }

            let params = {
                'address': self.getParams(self.address(), 'shipping')
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                window.location = data.response;
            };
            base.post(checkoutArea.urlCheckout, params, callback);
        }

        self.getParams = function(obj, type)
        {
            return {
                'user_id': checkoutArea.user.id,
                'adr_name': obj.name(),
                'adr_surname': obj.surname(),
                'adr_phone': obj.phone().toString().replace(/[^0-9]/g, ''),
                'adr_zipcode': obj.zipcode().toString().replace(/[^0-9]/g, ''),
                'adr_address': obj.address(),
                'adr_number': obj.number(),
                'adr_district': obj.district(),
                'adr_city': obj.city(),
                'adr_complement': obj.complement(),
                'adr_uf': obj.uf(),
                'adr_type': type
            };
        }
    }

	ko.components.register('checkout-area', {
	    template: { element: 'template-checkout-area'},
	    viewModel: checkoutArea.checkoutViewModel
	});
</script>
