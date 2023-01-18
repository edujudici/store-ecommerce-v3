@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koSeller">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Consultar Informações</h2>
                </div>
                <div class="card-body" style="height: 215px;">
                    <p class="mb-3">Informe o nickname para consultar as informações públicas de um parceiro</p>
                    <form class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Informe o nickname" data-bind="value: nickname">
                        </div>
                        <button type="button" class="btn btn-primary btn-default" data-bind="click: loadData">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Últimas consultas</h2>
                </div>
                <div class="card-body" style="position: relative; height: 215px; overflow: auto;">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nickname</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: sellers">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
                                <td><a href="#" data-bind="text: nickname, click: loadData"></a></td>
                                <td class="center">
                                    <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12" data-bind="with: data">
            <div class="card-header card-header-border-bottom">
                <h2 class="mb-3">Total de vendas</h2>
                <p>Visualize a reputação deste parceiro até o momento com as vendas realizadas no mercado livre.</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="media widget-media p-4 bg-white border">
                        <div class="icon rounded-circle mr-4 bg-danger">
                            <i class="mdi mdi-close text-white "></i>
                        </div>
                        <div class="media-body align-self-center">
                            <h4 class="text-primary mb-2" data-bind="text: canceled"></h4>
                            <p>Vendas canceladas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="media widget-media p-4 bg-white border">
                        <div class="icon bg-success rounded-circle mr-4">
                            <i class="mdi mdi-check text-white "></i>
                        </div>
                        <div class="media-body align-self-center">
                            <h4 class="text-primary mb-2" data-bind="text: complete"></h4>
                            <p>Vendas completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="media widget-media p-4 bg-white border">
                        <div class="icon rounded-circle bg-primary mr-4">
                            <i class="mdi mdi-check text-white "></i>
                        </div>
                        <div class="media-body align-self-center">
                            <h4 class="text-primary mb-2" data-bind="text: total"></h4>
                            <p>Total de vendas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header card-header-border-bottom">
                <h2 class="mb-3">Mais informações</h2>
                <p>Visualize algumas informações extras deste parceiro até o momento.</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="media widget-media p-4 bg-white border">
                        <div class="icon bg-success rounded-circle mr-4">
                            <i class="mdi mdi-check text-white "></i>
                        </div>
                        <div class="media-body align-self-center">
                            <h4 class="text-primary mb-2" data-bind="text: salesCompleted"></h4>
                            <p>Vendas ultimos: <span data-bind="text: salesPeriod"></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="media widget-media p-4 bg-white border">
                        <div class="icon rounded-circle bg-primary mr-4">
                            <i class="mdi mdi-check text-white "></i>
                        </div>
                        <div class="media-body align-self-center">
                            <h4 class="text-primary mb-2" data-bind="text: productsTotal"></h4>
                            <p>Total de produtos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">

    function homeSeller(){[native/code]}
    homeSeller.urlData = "{{ route('api.sellers.index') }}";
    homeSeller.urlDelete = "{{ route('api.sellers.destroy') }}";
    homeSeller.urlSearch = "{{ route('api.sellers.search') }}";

    homeSeller.Seller = function(obj)
    {
        let self = this;

        self.id = obj.sel_id;
        self.nickname = obj.sel_nickname;

        self.loadData = function()
        {
            homeSeller.viewModel.nickname(self.nickname);
            homeSeller.viewModel.loadData();
        }

        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    let data = {
                        id : item.id,
                    },
                    callback = function(data)
                    {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        } else {
                            homeSeller.viewModel.sellers.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeSeller.urlDelete, data, callback, 'DELETE');
                }
            );
        }
    }

    homeSeller.Data = function(obj)
    {
        let self = this;

        self.canceled = ko.observable(obj.seller.seller_reputation.transactions.canceled);
        self.complete = ko.observable(obj.seller.seller_reputation.transactions.completed);
        self.total = ko.observable(obj.seller.seller_reputation.transactions.total);
        self.salesPeriod = ko.observable(obj.seller.seller_reputation.metrics.sales.period);
        self.salesCompleted = ko.observable(obj.seller.seller_reputation.metrics.sales.completed);
        self.productsTotal = ko.observable(obj.paging.total);
    }

    homeSeller.ViewModel = function()
    {
        let self = this;

        self.sellers = ko.observableArray();
        self.nickname = ko.observable();
        self.data = ko.observable();

        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.sellers(ko.utils.arrayMap(data.response, function(item) {
                        return new homeSeller.Seller(item)
                    }));
                }
            };
            base.post(homeSeller.urlData, null, callback, 'GET');
        };
        self.init();

        self.loadData = function()
        {
            let params = {
                'nickname': self.nickname()
            },
            callback = function(data) {
                if (data.status) {
                    if (! data.response.seller) {
                        Alert.error('Nenhum parceiro foi encontrado com o nickname: ' + self.nickname());
                        return;
                    }
                    self.init();
                    self.data(new homeSeller.Data(data.response));
                }
            };
            base.post(homeSeller.urlSearch, params, callback);
        }
    }

	homeSeller.viewModel = new homeSeller.ViewModel();
    ko.applyBindings(homeSeller.viewModel, document.getElementById('koSeller'));

</script>
@endsection
