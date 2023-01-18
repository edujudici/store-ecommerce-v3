<painel-orders-overview-area></painel-orders-overview-area>
<template id="template-painel-orders-overview-area">
    <!--================Orders Overview Area =================-->
    <div class="card card-default" data-scroll-height="675">
        <div class="card-header justify-content-center">
            <h2>Visão geral dos pedidos</h2>
        </div>
        <div class="card-body">
            <canvas data-bind="chart: { type: 'doughnut', data: data, options: options }"></canvas>
        </div>
        <div class="card-footer d-flex flex-wrap bg-white p-0">
            <div class="col-6">
                <div class="py-4 px-4">
                    <ul class="d-flex flex-column justify-content-between">
                        <li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
                            style="color: #4c84ff"></i>Pedidos Incompletos</li>
                        <li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
                            style="color: #8061ef"></i>Pedidos Cancelados</li>
                    </ul>
                </div>
            </div>
            <div class="col-6 border-left">
                <div class="py-4 px-4">
                    <ul class="d-flex flex-column justify-content-between">
                        <li><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #80e1c1 "></i>Pedidos Finalizados</li>
                        <li><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #ffd54d"></i>Pedidos Pendentes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--================End Orders Overview Area =================-->
</template>

<script type="text/javascript">
    function ordersOverview(){[native/code]}
    ordersOverview.total = "{{ route('api.dashboard.ordersOverview') }}";

    ordersOverview.ordersOverviewViewModel = function(params) {
        var self = this;

        self.cancelled = ko.observable();
        self.finished = ko.observable();
        self.incomplete = ko.observable();
        self.pending = ko.observable();

        self.init = function()
        {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.cancelled(data.response.cancelled);
                self.finished(data.response.finished);
                self.incomplete(data.response.incomplete);
                self.pending(data.response.pending);
            };
            base.post(ordersOverview.total, null, callback);
        };
        self.init();

        self.data = {
            labels: ["imcompletos", "finalizados", "cancelados", "pendentes"],
            datasets: [{
                label: ["imcompletos", "finalizados", "cancelados", "pendentes"],
                data: [self.incomplete, self.finished, self.cancelled, self.pending],
                backgroundColor: ["#4c84ff", "#29cc97", "#8061ef", "#fec402"],
                borderWidth: 1
                // borderColor: ['#4c84ff','#29cc97','#8061ef','#fec402']
                // hoverBorderColor: ['#4c84ff', '#29cc97', '#8061ef', '#fec402']
            }]
        };
        self.options = {
            observeChanges: true,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            cutoutPercentage: 75,
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return "Pedidos : " + data["labels"][tooltipItem[0]["index"]];
                    },
                    label: function (tooltipItem, data) {
                        return data["datasets"][0]["data"][tooltipItem["index"]];
                    }
                },
                titleFontColor: "#888",
                bodyFontColor: "#555",
                titleFontSize: 12,
                bodyFontSize: 14,
                backgroundColor: "rgba(256,256,256,0.95)",
                displayColors: true,
                borderColor: "rgba(220, 220, 220, 0.9)",
                borderWidth: 2
            }
        };
    }

	ko.components.register('painel-orders-overview-area', {
	    template: { element: 'template-painel-orders-overview-area'},
	    viewModel: ordersOverview.ordersOverviewViewModel
	});
</script>
