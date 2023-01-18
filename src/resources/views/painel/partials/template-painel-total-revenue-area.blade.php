<painel-total-revenue-area></painel-total-revenue-area>
<template id="template-painel-total-revenue-area">
    <!--================Total Revenue Area =================-->
    <div class="card-body">
        <h2 class="mb-1" data-bind="text: total"></h2>
        <p>Total de pedidos</p>
        <div class="chartjs-wrapper">
            <canvas data-bind="chart: { type: 'line', data: data, options: options }"></canvas>
        </div>
    </div>
	<!--================End Total Revenue Area =================-->
</template>

<script type="text/javascript">

    function totalRevenue(){[native/code]}
    totalRevenue.total = "{{ route('api.dashboard.totalRevenue') }}";

    totalRevenue.totalRevenueViewModel = function(params) {
        var self = this;

        self.labels = ko.observableArray();
        self.totals = ko.observableArray();
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
                    self.totals.push(item.total);
                    self.total(self.total() + item.total);
                });
            };
            base.post(totalRevenue.total, null, callback);
        };
        self.init();

        self.data = {
            labels: self.labels,
            datasets: [{
                label: "Ref R$",
                lineTension: 0,
                pointRadius: 4,
                pointBackgroundColor: "rgba(255,255,255,1)",
                pointBorderWidth: 2,
                fill: true,
                backgroundColor: "#f4f4f4",
                borderColor: "#29cc97",
                borderWidth: 2,
                data: self.totals
            }]
        }
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
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        display: false, // hide main x-axis line
                        beginAtZero: true
                    },
                    barPercentage: 1.8,
                    categoryPercentage: 0.2
                }],
                yAxes: [{
                    gridLines: {
                        drawBorder: false, // hide main y-axis line
                        display: false
                    },
                    ticks: {
                        display: false,
                        beginAtZero: true
                    }
                }]
            },
            tooltips: {
                titleFontColor: "#888",
                bodyFontColor: "#555",
                titleFontSize: 12,
                bodyFontSize: 14,
                backgroundColor: "rgba(256,256,256,0.95)",
                displayColors: true,
                borderColor: "rgba(220, 220, 220, 0.9)",
                borderWidth: 2
            }
        }
    }

	ko.components.register('painel-total-revenue-area', {
	    template: { element: 'template-painel-total-revenue-area'},
	    viewModel: totalRevenue.totalRevenueViewModel
	});
</script>
