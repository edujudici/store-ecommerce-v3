@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koOrder">
    <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5" data-bind="with: order">
        <div class="d-flex justify-content-between">
            <h2 class="text-dark font-weight-medium">Pedido #<span data-bind="text: protocol"></span></h2>
            <div class="btn-group">
                <button class="btn btn-sm btn-secondary">
                    <i class="mdi mdi-content-save"></i> Save</button>
                <button class="btn btn-sm btn-secondary">
                    <i class="mdi mdi-printer"></i> Print</button>
            </div>
        </div>
        <div class="row pt-5 mb-3 border mt-2" data-bind="with: address">
            <div class="col-xl-4 col-lg-4">
                <p class="text-dark mb-2">Informações do pedido</p>
                <address>
                    <span class="text-dark">Comprador:</span> <span
                        data-bind="text: ora_name + ' ' + ora_surname"></span>
                    <br> <span class="text-dark">Telefone:</span> <span data-bind="text: ora_phone"></span>
                </address>
                <address>
                    <span class="text-dark">Data do Pedido:</span> <span
                        data-bind="text: base.dateTimeStringEn($parent.createdAt)"></span>
                    <br> <span class="text-dark">Forma de pagamento:</span> <span
                        data-bind="text: $parent.recentPayment.paidBy"></span>
                    <br> <strong class="text-dark">Previsão de entrega:</strong> <span
                        data-bind="text: base.monthStringEn($parent.promisedDate)"></span>
                </address>
            </div>
            <div class="col-xl-5 col-lg-6">
                <p class="text-dark mb-2">Informações de entrega</p>
                <address>
                    <span class="text-dark">Endereço:</span> <span data-bind="text: ora_address
                        + ', ' + ora_number"></span>
                    <br> <span class="text-dark">Bairro:</span> <span data-bind="text: ora_district"></span>
                    <br> <span class="text-dark">Cidade:</span> <span
                        data-bind="text: ora_city + ' / ' + ora_uf"></span>
                    <br> <span class="text-dark">CEP:</span> <span data-bind="text: ora_zipcode"></span>
                    <br> <span class="text-dark">Complemento:</span> <span data-bind="text: ora_complement"></span>
                </address>
            </div>
            <div class="col-xl-3 col-lg-4">
                <p class="text-dark mb-2">Informações de frete</p>
                <address>
                    <span class="text-dark">Serviço:</span> <span data-bind="text: $parent.freightservice"></span>
                    <br> <span class="text-dark">Prazo:</span> <span data-bind="text: $parent.freightTime"></span>
                    <br> <span class="text-dark">Preço:</span> <span data-bind="text: $parent.freightPrice"></span>
                </address>
            </div>
        </div>

        <div class="row border mb-3">
            <h2 class="text-dark font-weight-medium pl-3 pt-2">Produtos</h2>
            <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
                <thead>
                    <tr>
                        <th>#Sku</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: items">
                    <tr>
                        <td data-bind="text: product ? product.pro_sku : ori_pro_sku"></td>
                        <td data-bind="text: product ? product.pro_title : ori_title"></td>
                        <td data-bind="text: ori_amount"></td>
                        <td data-bind="text: base.numeroParaMoeda(ori_price)"></td>
                        <td data-bind="text: base.numeroParaMoeda(ori_amount * ori_price)"></td>
                    </tr>
                </tbody>
            </table>

            <div class="col-md-12 text-right">
                <ul class="list-unstyled mt-4">
                    <li class="mid pb-3 text-dark"> Subtotal:
                        <span class="d-inline-block text-default" data-bind="text: subtotal"></span>
                    </li>
                    <li class="mid pb-3 text-dark">Frete:
                        <span class="d-inline-block text-default" data-bind="text: freightPrice"></span>
                    </li>
                    <li class="mid pb-3 text-dark" data-bind="visible: voucherValue > 0">Desconto:
                        <span class="d-inline-block text-default"
                            data-bind="text: '- ' + base.numeroParaMoeda(voucherValue)"></span>
                    </li>
                    <li class="pb-3 text-dark">Total:
                        <span class="d-inline-block" data-bind="text: total"></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row border">
            <h2 class="text-dark font-weight-medium pl-3 pt-2">Histórico</h2>
            <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: histories">
                    <tr>
                        <td data-bind="text: base.dateTimeStringEn(created_at)"></td>
                        <td data-bind="text: $root.statusDescription(orh_collection_status)"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-2">
            <div class="col-md-6">
                <a href="#" class="btn  mt-2 btn-lg btn-pill btn-secondary btn-default"
                    data-bind="click: cancel">Voltar</a>
            </div>
            <div class="col-md-6">
                <div class="col-md-7 float-left">
                    <select class="form-control mt-3" data-bind="
                        options: $root.status,
                        optionsText: 'description',
                        optionsValue: 'id',
                        optionsCaption: 'Selecione a ação',
                        value: statusSelected, visible: !isComplete() && !isCancel()">
                    </select>
                </div>
                <div class="col-md-5 float-left text-right">
                    <a href="#" class="btn  mt-2 btn-lg btn-primary btn-pill"
                        data-bind="click: statusSave, visible: !isComplete() && !isCancel()">Salvar Status</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" data-bind="visible: !order()" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Pedidos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#protocolo</th>
                                <th class="d-none d-md-table-cell" scope="col">Status</th>
                                <th class="d-none d-md-table-cell" scope="col">Data Pedido</th>
                                <th scope="col">Data Prometida</th>
                                <th class="d-none d-md-table-cell" scope="col">Total</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: orders">
                            <tr data-bind="style: { 'background-color': actionColor }">
                                <td scope="row" data-bind="text: protocol"></td>
                                <td class="d-none d-md-table-cell"><span
                                        data-bind="text: $root.statusDescription(status())"></span></td>
                                <td class="d-none d-md-table-cell"><span
                                        data-bind="text: base.dateTimeStringEn(createdAt)"></span></td>
                                <td><span data-bind="text: base.monthStringEn(promisedDate)"></span></td>
                                <td class="d-none d-md-table-cell"><span data-bind="text: total"></span></td>
                                <td class="center">
                                    <i class="mdi mdi-eye" aria-hidden="true" data-bind="click: show"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination" data-bind="html: pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">
    function order(){[native/code]}
    order.urlData = "{{ route('api.orders.index') }}";
    order.urlOrderStatusSave = "{{ route('api.orders.store') }}";

    order.Status = function(obj)
    {
        let self = this;

        self.id = obj.id;
        self.description = obj.description;
    }

    order.Payment = function(obj) {
        let self = this;

        self.orderId = obj.ord_id;
        self.status = obj.orp_status;
        self.url = obj.orp_resource_url;
        self.type = ko.observable(obj.orp_payment_type_id);
        self.expiration = ko.observable(obj.orp_date_of_expiration);

        self.paidBy = ko.computed(function() {
            switch (self.type()) {
                case 'ticket':
                    return 'Boleto Bancário';
                case 'credit_card':
                    return 'Cartão de Crédito';
                case 'debit_card':
                    return 'Cartão de Débito';
                default:
                    return 'Não identificado';
            }
        });
    }

    order.Order = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = obj.ord_id;
        self.protocol = obj.ord_protocol;
        self.preferenceId = obj.ord_preference_id;
        self.subtotal = obj.ord_subtotal ? base.numeroParaMoeda(obj.ord_subtotal) : '';
        self.freightPrice = obj.ord_freight_price ? base.numeroParaMoeda(obj.ord_freight_price) : 'Grátis';
        self.freightservice = obj.ord_freight_service;
        self.freightTime = obj.ord_freight_time ? obj.ord_freight_time + ' dias' : '';
        self.voucherValue = obj.ord_voucher_value;
        self.total = obj.ord_total ? base.numeroParaMoeda(obj.ord_total) : '';
        self.createdAt = obj.created_at;
        self.address = obj.address;
        self.items = obj.items;
        self.histories = obj.histories;
        self.statusSelected = ko.observable();
        self.recentPayment = obj.payments && obj.payments.length > 0 ? new order.Payment(obj.payments[0]) : new order.Payment({});
        self.promisedDate = obj.ord_promised_date_recalculated || obj.ord_promised_date;

        self.loadLastStatus = function() {
            let lastStatus = self.histories[self.histories.length-1];
            return lastStatus.orh_collection_status;
        }
        self.status = ko.observable(self.loadLastStatus());

        self.show = function()
        {
            order.viewModel.order(self);
        }

        self.cancel = function(item)
        {
            order.viewModel.order(null);
        }

        self.statusSave = function()
        {
            if (!self.statusSelected()) {
                Alert.error('obrigatório selecionar uma ação');
                return;
            }

            let params = {
                'status': self.statusSelected(),
                'preferenceId': self.preferenceId,
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                Alert.success('Pedido concluído com sucesso.');
                setTimeout(function() {
                    location.reload()
                }, 500);
            };
            base.post(order.urlOrderStatusSave, params, callback);
        }

        self.isComplete = ko.computed(function() {
            let complete = ko.utils.arrayFirst(self.histories, function(item) {
                return item.orh_collection_status == 'complete';
            });
            return complete ? true : false;
        });
        self.isCancel = ko.computed(function() {
            let cancel = ko.utils.arrayFirst(self.histories, function(item) {
                return item.orh_collection_status == 'cancel';
            });
            return cancel ? true : false;
        });
        self.actionColor = ko.computed(function() {
            if (self.isComplete()) {
                return '#b2ffb2';
            } else if (self.isCancel()) {
                return '#ffc0af';
            }
        });
    }

    order.ViewModel = function()
    {
        let self = this;

        self.order = ko.observable();
        self.orders = ko.observableArray();
        self.pagination = ko.observable();
        self.status = ko.observableArray();
        self.allStatus = ko.observableArray();
        self.init = function()
        {
            let params = {
                'page': base.getParamUrl('page'),
            },
            callback = function(data) {
                if (data.status) {
                    self.pagination(data.response.pagination);
                    self.orders(ko.utils.arrayMap(data.response.orders, function(item) {
                        return new order.Order(item)
                    }));
                    self.status(ko.utils.arrayMap(Object.keys(data.response.status), function(item) {
                        return new order.Status({
                            'id': item,
                            'description': data.response.status[item]
                        })
                    }));
                    self.allStatus(ko.utils.arrayMap(Object.keys(data.response.allStatus), function(item) {
                        return new order.Status({
                            'id': item,
                            'description': data.response.allStatus[item]
                        })
                    }));
                }
            };
            base.post(order.urlData, params, callback, 'GET');
        };
        self.init();

        self.statusDescription = function(status)
        {
            let filteredStatus = ko.utils.arrayFirst(self.allStatus(), function(item) {
                return status && status.toLowerCase() === item.id.toLowerCase();
            });
            return filteredStatus ? filteredStatus.description : status;
        }
    }

	order.viewModel = new order.ViewModel();
    ko.applyBindings(order.viewModel, document.getElementById('koOrder'));

</script>
@endsection