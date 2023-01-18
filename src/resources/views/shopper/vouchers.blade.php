@extends('shopper.baseTemplate')

@section('shopperContent')

<voucher-area></voucher-area>
<template id="template-voucher-area">
    <div class="login_form_inner" style="padding: 20px 0;">
        <h2 class="text-center mb-4">Meus cupons</h2>

        <!-- ko if: vouchers().length == 0 -->
        <p>Nenhum cupom encontrado</p>
        <!-- /ko -->

        <div class="order-area" data-bind="foreach: vouchers">
            <div class="order-item">
                <div class="order-item-header text-right" style="display: inherit">
                    <span>Gerado em <strong class="acc-delivery-prevision-days delivered" data-bind="text: base.monthStringEn(created_at)"></strong></span>
                </div>
                <div class="order-item-body">
                    <div class="order-item-product">
                        <div class="order-item-product-info ml-0">
                            <span class="ti-gift pr-1" style="font-size: xx-large;"></span>
                            <span data-bind="text: 'Cupom de desconto no valor de ' + base.numeroParaMoeda(vou_value) + ' utilize o código: ' + vou_code"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('shopperScript')
    <script type="text/javascript">

        function voucher(){[native/code]}
        voucher.urlGetVouchers = "{{ route('api.vouchers.findByUser') }}";

        voucher.VoucherAreaViewModel = function(params) {
            var self = this;

            self.vouchers = ko.observableArray();

            self.loadVouchers = function() {
                let params = {
                    'uuid': '{{ auth()->user()->uuid }}'
                },
                callback = function(data) {
                    if(!data.status) {
                        Alert.error(data.message);
                        return;
                    }

                    self.vouchers(data.response);
                };
                base.post(voucher.urlGetVouchers, params, callback);
            }
            self.loadVouchers();
        }

        ko.components.register('voucher-area', {
            template: { element: 'template-voucher-area'},
            viewModel: voucher.VoucherAreaViewModel
        });
    </script>
@endsection
