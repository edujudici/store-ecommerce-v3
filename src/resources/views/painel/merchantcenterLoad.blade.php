@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koMerchantCenterLoad">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Sincronizar todos os produtos</h2>
                </div>
                <div class="card-body">
                    <p class="mb-3">Clique aqui para sincronizar todos os produtos da conta selecionada do Merchant Center.</p>
                    <button type="button" class="btn btn-primary btn-default" data-bind="click: loadProducts">Adicionar produtos em lote</button>
                    <button type="button" class="btn btn-primary btn-default">Alterar produtos em lote</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Histórico de Cargas de Produtos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell" scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Total de items</th>
                                <th scope="col">Data da Carga</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: loads">
                            <tr>
                                <td class="d-none d-md-table-cell" scope="row" data-bind="text: loh_id"></td>
                                <td><span data-bind="text: loh_account_title"></span></td>
                                <td><span data-bind="text: loh_total"></span></td>
                                <td><span data-bind="text: base.dateTimeStringEn(created_at)"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">
    function loadHistory() {
        [native / code]
    }
    loadHistory.urlData = "{{ route('api.load.product.history') }}";
    loadHistory.urlGetMlAccounts = "{{ route('api.mercadolivre.accounts.index') }}";
    loadHistory.loadProducts = "{{ route('api.load.multiple.products') }}";

    loadHistory.ViewModel = function() {
        let self = this;

        self.sku = ko.observable();
        self.loads = ko.observableArray();
        self.mlAccounts = ko.observableArray();
        self.mlAccountSelected = ko.observable();

        self.loadMlAccounts = function() {
            let params = {
                    'mel_enabled': true,
                },
                callback = function(data) {
                    if (!data.status) {
                        Alert.error(data.message);
                        return;
                    }
                    self.mlAccounts(data.response);
                };
            base.post(loadHistory.urlGetMlAccounts, params, callback, 'GET');
        }

        self.init = function() {
            let callback = function(data) {
                if (data.status) {
                    self.loads(data.response);
                }
            };
            base.post(loadHistory.urlData, null, callback, 'GET');

            self.loadMlAccounts();
        };
        self.init();

        self.loadProducts = function() {
            if (!self.mlAccountSelected()) {
                Alert.error('Obrigatório selecionar uma conta do Mercado Livre');
                return;
            }

            let params = {
                    'mlAccountId': self.mlAccountSelected().mel_id,
                    'mlAccountTitle': self.mlAccountSelected().mel_title
                },
                callback = function(data) {
                    if (!data.status) {
                        Alert.error(data.message);
                        return;
                    }
                    Alert.info('Uma nova carga de produtos esta sendo processada.');
                    setTimeout(function() {
                        location.reload()
                    }, 500);
                };
            base.post(loadHistory.loadProducts, params, callback);
        }
    }

    loadHistory.viewModel = new loadHistory.ViewModel();
    ko.applyBindings(loadHistory.viewModel, document.getElementById('koMerchantCenterLoad'));
</script>
@endsection