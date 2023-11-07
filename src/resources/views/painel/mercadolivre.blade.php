@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koAccountML">
    <!-- ko with: accountML -->
    <div class="row" data-bind="visible: $root.accountML" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Gerenciar minha conta do Mercado Livre</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="card-header card-header-border-bottom" style="padding: 15px">
                                        <label class="switch switch-icon switch-primary switch-pill form-control-label">
                                            <input type="checkbox" class="switch-input form-check-input" value="off"
                                                data-bind="checked: accountEnabled">
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                        <h2 class="ml-2"
                                            data-bind="text: accountEnabled() ? 'Conta Habilitada' : 'Conta Desabilitada'">
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título"
                                        data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card card-default">
                                    <div class="card-header card-header-border-bottom" style="padding: 15px">
                                        <h2 class="mr-3">Gostaria de habilitar mensagem de pós venda?</h2>
                                        <label class="switch switch-icon switch-primary switch-pill form-control-label">
                                            <input type="checkbox" class="switch-input form-check-input" value="off"
                                                data-bind="checked: afterSalesEnabled">
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                    <div class="card-body" style="padding: 15px" data-bind="visible: afterSalesEnabled">
                                        <div class="form-group">
                                            <textarea class="form-control" id="description" rows="13"
                                                placeholder="Informe a mensagem de pós venda"
                                                data-bind="value: afterSalesMessage"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-secondary btn-default"
                                data-bind="click: cancel">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-default"
                                data-bind="click: save">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /ko -->
    <div class="row" data-bind="visible: !accountML()" style="display: none">
        <div class="col-lg-4 mb-2">
            <a href="{{$authorization}}" class="btn btn-primary btn-default">Sincronizar Nova Conta do Mercado Livre</a>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Contas do MercadoLivre</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th width="10%" scope="col">#</th>
                                <th width="20%" scope="col">Título</th>
                                <th width="20%" scope="col">Tem mensagem pós venda?</th>
                                <th width="20%" scope="col">Conta Ativa</th>
                                <th width="10%" scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: accountsML">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
                                <td><span data-bind="text: title"></span></td>
                                <td><span data-bind="text: afterSalesEnabled() ? 'Sim' : 'Não'"></span></td>
                                <td><span data-bind="text: accountEnabled() ? 'Sim' : 'Não'"></span></td>
                                <td class="center">
                                    <i class="mdi mdi-pencil" aria-hidden="true" data-bind="click: edit"></i>
                                    <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
                                </td>
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
    function homeAccount(){[native/code]}
    homeAccount.urlData = "{{ route('api.mercadolivre.accounts.index') }}";
    homeAccount.urlSave = "{{ route('api.mercadolivre.accounts.store') }}";
    homeAccount.urlDelete = "{{ route('api.mercadolivre.accounts.destroy') }}";

    homeAccount.ML = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.mel_id);
        self.title = ko.observable(obj.mel_title).extend({
            required: {
                params: true,
                message: 'O campo Título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo Título não pode ter mais que 255 caracteres.'
            }
        });
        self.afterSalesEnabled = ko.observable(obj.mel_after_sales_enabled || false);
        self.afterSalesMessage = ko.observable(obj.mel_after_sales_message);
        self.accountEnabled = ko.observable(obj.mel_enabled);
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeAccount.viewModel.accountML(self);
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
                            homeAccount.viewModel.accountsML.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeAccount.urlDelete, data, callback, 'DELETE');
                }
            );
        }

        self.save = function()
        {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            let params = {
                'id': self.id(),
                'mel_title': self.title(),
                'mel_after_sales_enabled': self.afterSalesEnabled() ? 1 : 0,
                'mel_after_sales_message': self.afterSalesEnabled() ? self.afterSalesMessage() : '',
                'mel_enabled': self.accountEnabled() ? 1 : 0,
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.mel_id);
                self.origin = data.response;
                homeAccount.viewModel.accountML(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.post(homeAccount.urlSave, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeAccount.ML(self.origin),
                position = homeAccount.viewModel.accountsML.indexOf(item);
                homeAccount.viewModel.accountsML.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeAccount.viewModel.accountML.remove(item);
            } else {
                self.goBackData(item);
            }
            homeAccount.viewModel.accountML(null);
        }
    }

    homeAccount.ViewModel = function()
    {
        let self = this;

        self.accountML = ko.observable();
        self.accountsML = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.accountsML(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeAccount.ML(obj);
                    }));
                }
            };
            base.post(homeAccount.urlData, null, callback, 'GET');
        };
        self.init();
    }

	homeAccount.viewModel = new homeAccount.ViewModel();
    ko.applyBindings(homeAccount.viewModel, document.getElementById('koAccountML'));

</script>
@endsection