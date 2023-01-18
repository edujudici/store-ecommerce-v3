@extends('shopper.baseTemplate')

@section('shopperContent')

<order-area></order-area>
<template id="template-order-area">
    <div class="login_form_inner" style="padding: 20px 0;">
        <h2 class="text-center mb-4">Meus Pedidos</h2>

        <p class="text-left pl-3 pr-3">
            Utilize a área de comentários do pedido para enviar informações diretamente ao vendedor.
        </p>
        <p class="text-left pl-3 pr-3">
            Em caso de algum pedido selecionado a opção de retirar local, visualize os comentários para que consiga seguir as instruções da retirada.
        </p>

        <!-- ko if: orders().length == 0 -->
        <p>Nenhum pedido foi encontrado</p>
        <!-- /ko -->

        <div class="order-area" data-bind="foreach: orders">
            <div class="order-item">
                <div class="order-item-header order-item-header-mobile">
                    <span>Pedido - <strong data-bind="text: id"></strong></span>
                    <!-- ko if: status === 'complete' && deliveryDate -->
                        <span>concluído em <strong class="acc-delivery-prevision-days delivered" data-bind="text: base.monthStringEn(deliveryDate)"></strong></span>
                    <!-- /ko -->
                    <!-- ko if: status !== 'complete' && status !== 'cancel' && status !== 'new' && status !== 'payment_in_process' -->
                        <span>previsão de entrega <strong class="acc-delivery-prevision-days delivered" data-bind="text: base.monthStringEn(promisedDate())"></strong></span>
                    <!-- /ko -->
                    <!-- ko if: status === 'cancel' -->
                        <span>pedido <strong class="acc-delivery-prevision-days delivered">cancelado</strong></span>
                    <!-- /ko -->
                    <!-- ko if: status === 'new' || status === 'payment_in_process' -->
                        <span>pedido <strong class="acc-delivery-prevision-days delivered">aguardando pagamento</strong></span>
                    <!-- /ko -->
                </div>
                <div class="order-item-body" data-bind="foreach: items">
                    <div class="order-item-product">
                        <a data-bind="attr: {href: order.urlProductDetail+product.pro_sku}">
                            <figure>
                                <img class="order-item-product-image" data-bind="attr: {src: product.pro_secure_thumbnail ? product.pro_secure_thumbnail : base.displayImage(product.pro_image), alt: ori_title}">
                            </figure>
                        </a>
                        <div class="order-item-product-info">
                            <span data-bind="attr: {alt: ori_title, title: ori_title}">
                                <a data-bind="attr: {href: order.urlProductDetail+product.pro_sku}, text: ori_title"></a>
                            </span>
                            <p class="text-left">
                                <strong><span data-bind="text: ori_amount"></span> unidade(s) - <span data-bind="text: base.numeroParaMoeda(ori_price)"></span></strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row pb-3" data-bind="visible: status == null">
                    <div class="col-12 col-sm-12 col-md-12">
                        Pedido inválido! Para efetuar o pagamento
                        <a target="_blank" data-bind="attr: {href: preferenceInitPoint}">Clique aqui</a>
                    </div>
                </div>

                <a class="primary-btn" href="javascript:void" data-bind="click: propDetails">
                    <!-- ko if: !isDetailsVisible() -->+ Mostrar detalhes <!-- /ko -->
                    <!-- ko if: isDetailsVisible -->- Esconder detalhes <!-- /ko -->
                </a>

                <!--================Product Description Area =================-->
                <section class="product_description_area mt-4 pb-0" data-bind="visible: isDetailsVisible">
                    <div class="p-2">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" role="tab" aria-controls="detail"
                                    aria-selected="true" data-bind="attr: {href: '#detail-'+id}">Detalhes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" role="tab" aria-controls="contact"
                                    aria-selected="false" data-bind="attr: {href: '#comment-'+id}">Comentários</a>
                            </li>
                        </ul>
                        <div class="tab-content p-2">
                            <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab" data-bind="attr: {id: 'detail-'+id}">
                                <div class="section-top-border text-left">
                                    <h3 class="text-center mb-0">Pedido / Entrega</h3>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <div class="track">

                                                <!-- ko if: status === 'cancel' -->
                                                <div class="step" data-bind="class: statusNew"> <span class="icon"> <i class="fa fa-gift"></i> </span> <span class="text">Pedido recebido</span> </div>
                                                <div class="step" data-bind="class: statusCancel"> <span class="icon"> <i class="fa fa-close"></i> </span> <span class="text">Pedido cancelado</span> </div>
                                                <!-- /ko -->

                                                <!-- ko if: status === 'new' || status === 'payment_in_process' -->
                                                <div class="step" data-bind="class: statusNew"> <span class="icon"> <i class="fa fa-gift"></i> </span> <span class="text">Pedido recebido</span> </div>
                                                <div class="step" data-bind="class: statusPaymentInProcess"> <span class="icon"> <i class="fa fa-money"></i> </span> <span class="text">Aguardando pagamento</span> </div>
                                                <!-- /ko -->

                                                <!-- ko if: status !== 'new' && status !== 'cancel' && status != 'payment_in_process' -->
                                                <div class="step" data-bind="class: statusPaid"> <span class="icon"> <i class="fa fa-money"></i> </span> <span class="text">Pagamento aprovado</span> </div>
                                                <div class="step" data-bind="class: statusProduction"> <span class="icon"> <i class="fa fa-archive"></i> </span> <span class="text">Em produção</span> </div>
                                                <div class="step" data-bind="class: statusTransport"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text">Em transporte</span> </div>
                                                <div class="step" data-bind="class: statusComplete"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Concluído</span> </div>
                                                <!-- /ko -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-sm-left col-sm-12 text-md-left col-md-6">
                                            <h4 class="text-center">Detalhes do Pedido</h4>
                                            <ul class="list pl-md-4">
                                                <li><span class="title">Data</span> : <span data-bind="text: base.dateTimeStringEn  (createDate)"></span></li>
                                                <li><span class="title">Forma de Pagamento</span> :
                                                    <!-- ko if: recentPayment.type() === 'ticket' && recentPayment.displayUrl() -->
                                                        <a target="_blank" data-bind="attr: {href: recentPayment.url}"><span data-bind="text: recentPayment.paidBy"></span></a>
                                                    <!-- /ko -->
                                                    <!-- ko if: recentPayment.type() !== 'ticket' || !recentPayment.displayUrl() -->
                                                        <span data-bind="text: recentPayment.paidBy"></span>
                                                    <!-- /ko -->
                                                </li>
                                                <li><span class="title">Subtotal</span> : <span data-bind="text: base.numeroParaMoeda(subtotal)"></span></li>
                                                <li><span class="title">Frete</span> : <span data-bind="text: base.numeroParaMoeda(freightPrice)"></span></li>
                                                <li data-bind="visible: voucherValue > 0"><span class="title">Desconto</span> : <span data-bind="text: '- ' + base.numeroParaMoeda(voucherValue)"></span></li>
                                                <li><span class="title">Total</span> : <span data-bind="text: base.numeroParaMoeda(total)"></span></li>
                                                <!-- ko if: status !== 'new' -->
                                                <li><strong class="title">Previsão de Entrega</strong> : <strong data-bind="text: base.monthStringEn(promisedDate())"></strong></li>
                                                <!-- /ko -->
                                                <!-- ko if: status === 'new' -->
                                                <li>
                                                    <strong class="title">Efetuar pagamento</strong> :
                                                    <a data-bind="attr: {href: preferenceInitPoint}">Clique aqui</a>
                                                </li>
                                                <!-- /ko -->
                                            </ul>
                                        </div>
                                        <div class="col-12 text-sm-left col-sm-12 text-md-left col-md-6" data-bind="with: address">
                                            <h4 class="text-center">Endereço de Entrega</h4>
                                            <ul class="list pl-md-4">
                                                <li><span class="title">Endereço</span> : <span data-bind="text: ora_address + ', ' + ora_number"></span></li>
                                                <li><span class="title">Complemento</span> : <span data-bind="text: ora_complement"></span></li>
                                                <li><span class="title">Cidade</span> : <span data-bind="text: ora_city + ' / ' + ora_uf"></span></li>
                                                <li><span class="title">CEP </span> : <span data-bind="text: base.mascaraCep(ora_zipcode)"></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab" data-bind="attr: {id: 'comment-'+id}">
                                <div class="row">
                                    <div class="col-lg-12">

                                        <!-- ko if: comments().length == 0 -->
                                        <div class="comment_list">
                                            <p class="text-center">Seja o primeiro a comentar.</p>
                                        </div>
                                        <!-- /ko -->

                                        <div class="comment_list" data-bind="foreach: comments">
                                            <!-- ko if: orc_question -->
                                            <div class="review_item border-bottom">
                                                <div class="media">
                                                    <div class="d-flex">
                                                        <img src="{{ asset('assets/site/img/guest-user.jpg') }}" width="70" alt="Imagem do usuário">
                                                    </div>
                                                    <div class="media-body text-left">
                                                        <h4 data-bind="text: orc_name"></h4>
											            <h5 data-bind="text: base.monthStringEn(created_at)"></h5>
                                                        {{-- <a class="reply_btn" href="#">Reply</a> --}}
                                                    </div>
                                                </div>
                                                <pre class="text-left pt-3" data-bind="text: orc_question" style="white-space: pre-wrap"></pre>
                                            </div>
                                            <!-- /ko -->
                                            <!-- ko if: orc_answer -->
                                            <div class="review_item reply border-bottom">
                                                <div class="media">
                                                    <div class="d-flex">
                                                        <img src="{{ asset('assets/site/img/user-logo.png') }}" width="70" alt="Império do Mdf">
                                                    </div>
                                                    <div class="media-body text-left">
                                                        <h4>Império do Mdf</h4>
                                                        <h5 data-bind="text: base.monthStringEn(orc_answer_date)"></h5>
                                                        {{-- <a class="reply_btn" href="#">Reply</a> --}}
                                                    </div>
                                                </div>
                                                <pre class="text-left pt-3" data-bind="text: orc_answer" style="white-space: pre-wrap"></pre>

                                                <!-- ko if: orc_image -->
                                                    <div class="text-left pt-3">
                                                        <a data-bind="attr: {href: base.displayImage(orc_image)}" download>Clique aqui para visualizar anexo</a>
                                                    </div>
                                                <!-- /ko -->
                                            </div>
                                            <!-- /ko -->
                                        </div>
                                    </div>
                                    <div class="col-lg-12" data-bind="with: comment">
                                        <div class="review_box pt-5">
                                            <h4>Realizar um comentário</h4>
                                            <form class="row contact_form">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Nome" data-bind="value: name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="2" placeholder="Mensagem" data-bind="value: question"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn primary-btn" data-bind="click: send">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--================End Product Description Area =================-->
            </div>
        </div>

        <a href="javascript:void" data-bind="click: loadMoreItems" style="color: #6e6e6e;font-size: large;">
            Desejo carregar mais pedidos
        </a>
    </div>
</template>
@endsection

@section('shopperScript')
    <script type="text/javascript">

        function order(){[native/code]}
        order.urlGetOrders = "{{ route('api.orders.index') }}";
        order.urlProductDetail = "{{ route('site.shop.detail') }}/";
        order.urlGetComments = "{{ route('api.orders.comments.index') }}";
        order.urlSaveComment = "{{ route('api.orders.comments.store') }}";

        order.Comment = function(obj, vm) {
            let self = this;

            self.name = ko.observable('{{ auth()->user()->name }}');
            self.question = ko.observable();
            self.vm = vm;

            self.send = function() {
                let params = {
                    'id': self.vm.id,
                    'orc_name': self.name(),
                    'orc_question': self.question()
                },
                callback = function(data) {
                    if(!data.status) {
                        Alert.error(data.message);
                        return;
                    }
                    Alert.success('Comentário enviado com sucesso.');
                    self.vm.comments(data.response);
                    self.vm.comment(new order.Comment({}, self.vm));
                };
                base.post(order.urlSaveComment, params, callback);
            }
        }

        order.Payment = function(obj) {
            let self = this;

            self.orderId = obj.ord_id;
            self.status = obj.orp_status;
            self.url = obj.orp_resource_url;
            self.type = ko.observable(obj.orp_payment_type_id);
            self.expiration = ko.observable(obj.orp_date_of_expiration);
            self.displayUrl = ko.computed(function() {
                let today = new Date(),
                    expiration = new Date(self.expiration()),
                    todayFormatada = new Date((today.getFullYear() + "-" + ((today.getMonth() + 1)) + "-" + (today.getDate()))),
                    expirationFormatada = new Date((expiration.getFullYear() + "-" + ((expiration.getMonth() + 1)) + "-" + (expiration.getDate())));

                return expirationFormatada >= todayFormatada;
            });

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

        order.Order = function(obj) {
            let self = this;

            self.id = obj.ord_protocol;
            self.deliveryDate = obj.ord_delivery_date;
            self.createDate = obj.order_date;
            self.subtotal = obj.ord_subtotal;
            self.freightPrice = obj.ord_freight_price;
            self.voucherValue = obj.ord_voucher_value;
            self.total = obj.ord_total;
            self.preferenceInitPoint = obj.ord_preference_init_point;
            self.items = obj.items;
            self.histories = obj.histories;
            self.address = obj.address;
            self.isDetailsVisible = ko.observable(false);
            self.comments = ko.observableArray();
            self.comment = ko.observable(new order.Comment({}, self));
            self.statusNew = ko.observable();
            self.statusPaymentInProcess = ko.observable();
            self.statusPaid = ko.observable();
            self.statusProduction = ko.observable();
            self.statusTransport = ko.observable();
            self.statusComplete = ko.observable();
            self.statusCancel = ko.observable();
            self.recentPayment = obj.payments && obj.payments.length > 0 ? new order.Payment(obj.payments[0]) : new order.Payment({});
            self.promisedDate = ko.observable(obj.ord_promised_date_recalculated || obj.ord_promised_date);

            self.loadLastStatus = function() {
                let lastStatus = self.histories[self.histories.length-1];
                return lastStatus.orh_collection_status;
            }
            self.status = self.loadLastStatus();

            self.fillTracking = function() {
                switch (self.status) {
                    case 'cancel':
                        self.statusNew('active');
                        self.statusCancel('active');
                        break;
                    case 'new':
                    case 'payment_in_process':
                        self.statusNew('active');
                        break;
                    case 'paid':
                        self.statusPaid('active');
                        break;
                    case 'production':
                        self.statusPaid('active');
                        self.statusProduction('active');
                        break;
                    case 'transport':
                        self.statusPaid('active');
                        self.statusProduction('active');
                        self.statusTransport('active');
                        break;
                    case 'complete':
                        self.statusPaid('active');
                        self.statusProduction('active');
                        self.statusTransport('active');
                        self.statusComplete('active');
                        break;
                    default:
                        self.statusNew('active');
                }
            }
            self.fillTracking();

            self.propDetails = function() {
                self.isDetailsVisible(!self.isDetailsVisible());
            }

            self.loadComments = function() {
                let params = {
                    'id': self.id,
                },
                callback = function(data) {
                    if(!data.status) {
                        Alert.error(data.message);
                        return;
                    }

                    self.comments(data.response);
                };
                base.post(order.urlGetComments, params, callback, 'GET');
            }
            self.loadComments()
        }

        order.OrderAreaViewModel = function(params) {
            var self = this;

            self.page = ko.observable(1);
            self.orders = ko.observableArray();

            self.loadOrders = function() {
                let params = {
                    'page': self.page(),
                    'uuid': '{{ auth()->user()->uuid }}'
                },
                callback = function(data) {
                    if(!data.status) {
                        Alert.error(data.message);
                        return;
                    }

                    ko.utils.arrayForEach(data.response.orders, function(item) {
                        self.orders.push(new order.Order(item));
                    });
                };
                base.post(order.urlGetOrders, params, callback, 'GET');
            }
            self.loadOrders();

            self.loadMoreItems = function() {
                self.loadOrders(self.page(self.page()+1));
            }
        }

        ko.components.register('order-area', {
            template: { element: 'template-order-area'},
            viewModel: order.OrderAreaViewModel
        });
    </script>
@endsection
