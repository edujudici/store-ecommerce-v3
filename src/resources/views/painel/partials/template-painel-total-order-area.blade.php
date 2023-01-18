<painel-total-order-area></painel-total-order-area>
<template id="template-painel-total-order-area">
    <!--================Total Order Area =================-->
    <div class="card card-default" data-scroll-height="675">
        <div class="card-header">
            <h2>Vendas do ano</h2>
        </div>
        <div class="card-body">
            <canvas data-bind="chart: { type: 'line', data: data, options: options }" class="chartjs"></canvas>
        </div>
        <div class="card-footer d-flex flex-wrap bg-white p-0">
            <div class="col-6 px-0">
                <div class="text-center p-4">
                    <h4 data-bind="text: total"></h4>
                    <p class="mt-2">Total de pedidos no ano</p>
                </div>
            </div>
            <div class="col-6 px-0">
                <div class="text-center p-4 border-left">
                    <h4 data-bind="text: base.numeroParaMoeda(revenue())"></h4>
                    <p class="mt-2">Total da receita no ano</p>
                </div>
            </div>
        </div>
    </div>
	<!--================End Total Order Area =================-->
</template>

<script type="text/javascript">

    function totalOrder(){[native/code]}
    totalOrder.total = "{{ route('api.dashboard.salesYear') }}";

    totalOrder.totalOrderViewModel = function(params) {
        var self = this;

        self.labels = ko.observableArray();
        self.totals = ko.observableArray();
        self.revenue = ko.observable(0);
        self.total = ko.observable(0);

        self.init = function()
        {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                ko.utils.arrayForEach(data.response, function(item) {
                    self.labels.push(base.monthDescription(item.month) + '/' + item.year);
                    self.totals.push(item.revenue);
                    self.total(self.total() + item.total);
                    self.revenue(self.revenue() + item.revenue);
                });
            };
            base.post(totalOrder.total, null, callback);
        };
        self.init();

        self.data = {
            labels: self.labels,
            datasets: [{
                label: "",
                backgroundColor: "transparent",
                borderColor: "rgb(82, 136, 255)",
                data: self.totals,
                lineTension: 0.3,
                pointRadius: 5,
                pointBackgroundColor: "rgba(255,255,255,1)",
                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                pointBorderWidth: 2,
                pointHoverRadius: 8,
                pointHoverBorderWidth: 1
            }]
        };
        self.options = {
            observeChanges: true,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            layout: {
                padding: {
                    right: 10
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: true,
                        color: "#eee",
                        zeroLineColor: "#eee",
                    },
                    ticks: {
                        callback: function (value) {
                            var ranges = [
                                { divider: 1e6, suffix: "M" },
                                { divider: 1e4, suffix: "k" }
                            ];
                            function formatNumber(n) {
                                for (var i = 0; i < ranges.length; i++) {
                                    if (n >= ranges[i].divider) {
                                        return (
                                        (n / ranges[i].divider).toString() + ranges[i].suffix
                                        );
                                    }
                                }
                                return n;
                            }
                            return formatNumber(value);
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data["labels"][tooltipItem[0]["index"]];
                    },
                    label: function (tooltipItem, data) {
                        return "R$" + data["datasets"][0]["data"][tooltipItem["index"]];
                    }
                },
                responsive: true,
                intersect: false,
                enabled: true,
                titleFontColor: "#888",
                bodyFontColor: "#555",
                titleFontSize: 12,
                bodyFontSize: 18,
                backgroundColor: "rgba(256,256,256,0.95)",
                xPadding: 20,
                yPadding: 10,
                displayColors: false,
                borderColor: "rgba(220, 220, 220, 0.9)",
                borderWidth: 2,
                caretSize: 10,
                caretPadding: 15
            }
        }
    }

	ko.components.register('painel-total-order-area', {
	    template: { element: 'template-painel-total-order-area'},
	    viewModel: totalOrder.totalOrderViewModel
	});
</script>
