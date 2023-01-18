<painel-total-shoppers-area></painel-total-shoppers-area>
<template id="template-painel-total-shoppers-area">
    <!--================Total Shoppers Area =================-->
    <div class="card-body">
        <h2 class="mb-1" data-bind="text: total"></h2>
        <p>Usuários cadastrados</p>
        <div class="chartjs-wrapper">
            <canvas data-bind="chart: { type: 'bar', data: data, options: options }"></canvas>
        </div>
    </div>
    <!--================End Total Shoppers Area =================-->
</template>

<script type="text/javascript">
    function totalShoppers(){[native/code]}
    totalShoppers.total = "{{ route('api.dashboard.totalShoppers') }}";

    totalShoppers.totalShoppersViewModel = function(params) {
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
            base.post(totalShoppers.total, null, callback);
        };
        self.init();

        self.data = {
            labels: self.labels,
            datasets: [{
                label: "inscritos",
                data: self.totals,
                backgroundColor: "#4c84ff"
            }]
        };
        self.options = {
            observeChanges: true,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
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
                bodyFontSize: 15,
                backgroundColor: "rgba(256,256,256,0.95)",
                displayColors: false,
                borderColor: "rgba(220, 220, 220, 0.9)",
                borderWidth: 2
            }
        };
    }

	ko.components.register('painel-total-shoppers-area', {
	    template: { element: 'template-painel-total-shoppers-area'},
	    viewModel: totalShoppers.totalShoppersViewModel
	});
</script>
