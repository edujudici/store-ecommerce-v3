<confirmation-area></confirmation-area>
<template id="template-confirmation-area">
    <!--================Order Details Area =================-->
	<section class="order_details">
		<div class="container">

            @if (session('status'))
                <div class="order_details">
                    <h3 class="title_confirmation">
                        {{ session('status') }}
                    </h3>
                </div>
            @endif

			<div class="row order_d_inner">
                <!-- ko with: order -->
                    <div class="col-lg-4">
                        <div class="details_item">
                            <h4>Detalhes de Pedido</h4>
                            <ul class="list">
                                <li><span class="title">Ordem</span> : <span data-bind="text: ord_protocol"></span></li>
                                <li><span class="title">Data</span> : <span data-bind="text: base.dateTimeStringEn(created_at)"></span></li>
                                <li><span class="title">Total</span> : <span data-bind="text: base.numeroParaMoeda(ord_total)"></span></li>
                                <li><span class="title">Previsão de entrega</span> : <span data-bind="text: base.monthStringEn(ord_promised_date)"></span></li>
                            </ul>
                        </div>
                    </div>
                <!-- /ko -->
                <!-- ko with: address -->
                    <div class="col-lg-8">
                        <div class="details_item">
                            <h4>Endereço de Entrega</h4>
                            <ul class="list">
                                <li><span class="title">Endereço</span> : <span data-bind="text: ora_address + ', ' + ora_number"></span></li>
                                <li><span class="title">Complemento</span> : <span data-bind="text: ora_complement"></span></li>
                                <li><span class="title">Cidade</span> : <span data-bind="text: ora_city + ' / ' + ora_uf"></span></li>
                                <li><span class="title">CEP </span> : <span data-bind="text: base.mascaraCep(ora_zipcode)"></span></li>
                            </ul>
                        </div>
                    </div>
                <!-- /ko -->
			</div>
			<div class="order_details_table mb-5">
				<h2>Detalhes da Compra</h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr class="text-center">
								<th scope="col">Produto</th>
								<th scope="col">Quantidade</th>
								<th scope="col">Total</th>
							</tr>
						</thead>
						<tbody>
                            <!-- ko foreach: products -->
                                <tr>
                                    <td>
                                        <p data-bind="text: ori_title"></p>
                                    </td>
                                    <td class="text-right">
                                        <h5>x <span data-bind="text: ori_amount"></span></h5>
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
                                    <h4>Desconto</h4>
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
                                    <p data-bind="text: base.numeroParaMoeda(freight)"></p>
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
                                    <p data-bind="text: base.numeroParaMoeda(total)"></p>
                                </td>
                            </tr>
                        </tbody>
					</table>
				</div>
			</div>

            <div class="check_title mb-5 text-center">
                <h2><a href="{{ route('shopper.orders.index') }}">Clique aqui</a> e consulte todos os seus pedidos</h2>
            </div>
		</div>
	</section>
	<!--================End Order Details Area =================-->
</template>

<script type="text/javascript">

    function confirmation(){[native/code]}

    confirmation.data = {!! $data !!};
    confirmation.order = confirmation.data.response.order;
    confirmation.address = confirmation.data.response.address;
    confirmation.items = confirmation.data.response.items;

    confirmation.ConfirmationViewModel = function() {
        let self = this;

        self.order = confirmation.order;
        self.subtotal = confirmation.order.ord_subtotal;
        self.voucherValue = confirmation.order.ord_voucher_value;
        self.freight = confirmation.order.ord_freight_price;
        self.total = confirmation.order.ord_total;
        self.address = confirmation.address;
        self.products = ko.utils.arrayMap(confirmation.items,
            function(item) {
                item.subtotal = item.ori_price * item.ori_amount;
                return item;
            });
    }

	ko.components.register('confirmation-area', {
	    template: { element: 'template-confirmation-area'},
	    viewModel: confirmation.ConfirmationViewModel
	});
</script>
