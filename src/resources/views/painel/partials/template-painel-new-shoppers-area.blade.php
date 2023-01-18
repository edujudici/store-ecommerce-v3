<painel-new-shoppers-area></painel-new-shoppers-area>
<template id="template-painel-new-shoppers-area">
    <!--================New Shoppers Area =================-->
    <div class="card card-table-border-none" data-scroll-height="580">
        <div class="card-header justify-content-between ">
            <h2>Novos consumidores</h2>
        </div>
        <div class="card-body pt-0">
            <table class="table ">
                <tbody data-bind="foreach: shoppers">
                    <tr>
                        <td>
                            <div class="media">
                                <div class="media-body align-self-center">
                                    <h6 class="mt-0 text-dark font-weight-medium" data-bind="text: name + ' ' + surname"></h6>
                                </div>
                            </div>
                        </td>
                        <td><small data-bind="text: email"></small></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	<!--================End New Shoppers Area =================-->
</template>

<script type="text/javascript">

    function newShoppers(){[native/code]}
    newShoppers.urlData = "{{ route('api.dashboard.newShoppers') }}";

    newShoppers.newShoppersViewModel = function(params) {
        var self = this;

        self.shoppers = ko.observableArray();

        let callback = function(data) {
            if(!data.status) {
                Alert.error(data.message);
                return;
            }

            self.shoppers(data.response);

        };
        base.post(newShoppers.urlData, null, callback);
    }

	ko.components.register('painel-new-shoppers-area', {
	    template: { element: 'template-painel-new-shoppers-area'},
	    viewModel: newShoppers.newShoppersViewModel
	});
</script>
