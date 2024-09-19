@extends('painel.baseTemplate')

@section('content')
    <div class="content" id="koGoogleLoad">
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header card-header-border-bottom">
                        <h2>Sincronizar todos os produtos</h2>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Clique aqui para sincronizar todos os produtos da conta selecionada do Google.</p>
                        <button type="button" class="btn btn-primary btn-default"
                            data-bind="click: loadProducts.bind($data, 'insert')">Adicionar
                            produtos em lote</button>
                        <button type="button" class="btn btn-primary btn-default"
                            data-bind="click: loadProducts.bind($data, 'update')">Alterar
                            produtos em lote</button>
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
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Total de items</th>
                                    <th scope="col">Data da Carga</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: loads">
                                <tr>
                                    <td class="d-none d-md-table-cell" scope="row" data-bind="text: pgh_id"></td>
                                    <td><span data-bind="text: pgh_account_title"></span></td>
                                    <td><span data-bind="text: pgh_total"></span></td>
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
        function googleLoad() {
            [native / code]
        }
        googleLoad.urlLoadHistoryData = "{{ route('api.google.products.index.history') }}";
        googleLoad.loadProducts = "{{ route('api.google.products.storeMultiple') }}";

        googleLoad.ViewModel = function() {
            let self = this;

            self.sku = ko.observable();
            self.loads = ko.observableArray();
            self.mlAccounts = ko.observableArray();
            self.mlAccountSelected = ko.observable();

            self.init = function() {
                let callback = function(data) {
                    if (data.status) {
                        self.loads(data.response);
                    }
                };
                base.post(googleLoad.urlLoadHistoryData, null, callback, 'GET');
            };
            self.init();

            self.loadProducts = function(type) {
                Alert.confirm(
                    'Você realmente deseja realizar essa ação?',
                    'Cadastrar / Alterar',
                    function(resp) {

                        if (!resp) return;

                        let params = {
                                type: type,
                            },
                            callback = function(data) {
                                if (!data.status) {
                                    Alert.error(data.message);
                                    return;
                                }

                                Alert.info('Os produtos estão sendo processados com sucesso.');
                                setTimeout(function() {
                                    location.reload()
                                }, 500);
                            };
                        base.post(googleLoad.loadProducts, params, callback);
                    }
                );
            }
        }

        googleLoad.viewModel = new googleLoad.ViewModel();
        ko.applyBindings(googleLoad.viewModel, document.getElementById('koGoogleLoad'));
    </script>
@endsection
