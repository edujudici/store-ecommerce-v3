<painel-recent-orders-area></painel-recent-orders-area>
<template id="template-painel-recent-orders-area">
    <!--================Recent Orders Area =================-->
    <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
            <h2>Pedidos recentes</h2>
        </div>
        <div class="card-body pt-0 pb-5">
            <table class="table card-table table-responsive table-responsive-large"
                style="width:100%">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th class="d-none d-md-table-cell">Data</th>
                        <th class="d-none d-md-table-cell">Frete</th>
                        <th class="d-none d-md-table-cell">Prazo</th>
                        <th class="d-none d-md-table-cell">Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: orders">
                    <tr>
                        <td data-bind="text: protocol"></td>
                        <td class="d-none d-md-table-cell" data-bind="text: base.dateTimeStringEn(createdAt)"></td>
                        <td class="d-none d-md-table-cell" data-bind="text: freightservice"></td>
                        <td class="d-none d-md-table-cell" data-bind="text: freightTime"></td>
                        <td class="d-none d-md-table-cell" data-bind="text: total"></td>
                        <td>
                            <span class="badge" data-bind="text: status, class: orderStatus"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	<!--================End Recent Orders Area =================-->
</template>

<script type="text/javascript">

    function recentOrders(){[native/code]}
    recentOrders.urlData = "{{ route('api.dashboard.recentOrders') }}";

    recentOrders.Order = function(obj)
    {
        let self = this;

        self.id = obj.ord_id;
        self.protocol = obj.ord_protocol;
        self.freightservice = obj.ord_freight_service;
        self.freightTime = obj.ord_freight_time ? obj.ord_freight_time + ' dias' : '';
        self.total = obj.ord_total ? base.numeroParaMoeda(obj.ord_total) : '';
        self.createdAt = obj.created_at;
        self.histories = obj.histories;
        self.loadLastStatus = function() {
            let lastStatus = self.histories[self.histories.length-1];
            return lastStatus.orh_collection_status;
        }
        self.status = ko.observable(self.loadLastStatus());

        self.orderStatus = ko.computed(function() {
            switch(self.status()) {
                case 'new':
                case 'payment_in_process':
                    return 'badge-warning';
                case 'production':
                case 'transport':
                    return 'badge-info';
                case 'complete':
                case 'paid':
                    return 'badge-success';
                case 'cancel':
                    return 'badge-danger';
                default:
                    '';
            }
        });
    }

    recentOrders.recentOrdersViewModel = function(params) {
        var self = this;

        self.orders = ko.observableArray();

        let callback = function(data) {
            if(!data.status) {
                Alert.error(data.message);
                return;
            }

            self.orders(ko.utils.arrayMap(data.response, function(item) {
                return new recentOrders.Order(item);
            }));

        };
        base.post(recentOrders.urlData, null, callback);
    }

	ko.components.register('painel-recent-orders-area', {
	    template: { element: 'template-painel-recent-orders-area'},
	    viewModel: recentOrders.recentOrdersViewModel
	});
</script>
