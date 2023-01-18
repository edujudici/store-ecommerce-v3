@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koVoucher">
    <!-- ko with: voucher -->
    <div class="row" data-bind="visible: $root.voucher" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Voucher</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="value">Valor</label>
                                    <input type="text" class="form-control" id="value" placeholder="Informe o valor" data-bind="value: value">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="expiration-date">Data expiração</label>
                                    <input type="text" class="form-control" id="expiration-date" placeholder="Informe a data de expiração" data-bind="value: expirationDate">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" data-bind="
                                        options: $root.status,
                                        optionsText: 'description',
                                        optionsValue: 'id',
                                        value: status">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" rows="6" placeholder="Informe a descrição" data-bind="value: description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-secondary btn-default" data-bind="click: cancel">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-default" data-bind="click: save">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /ko -->
    <div class="row" data-bind="visible: !voucher()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addVoucher">Novo Vale</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Vouchers</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#Código</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Status</th>
                                <th scope="col">Data de expiração</th>
                                <th scope="col">Data de aplicação</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: vouchers">
                            <tr>
                                <td scope="row" data-bind="text: code"></td>
                                <td scope="row" data-bind="text: value"></td>
                                <td><span data-bind="text: status"></span></td>
                                <td><span data-bind="text: base.monthStringEn(expirationDate())"></span></td>
                                <td><span data-bind="text: base.monthStringEn(appliedDate())"></span></td>
                                <td><span data-bind="text: description"></span></td>
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

    function voucher(){[native/code]}
    voucher.urlData = "{{ route('api.vouchers.index') }}";
    voucher.urlSave = "{{ route('api.vouchers.store') }}";
    voucher.urlDelete = "{{ route('api.vouchers.destroy') }}";

    voucher.Status = function(obj)
    {
        let self = this;

        self.id = obj.id;
        self.description = obj.description;
    }

    voucher.Voucher = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = obj.vou_id;
        self.code = ko.observable(obj.vou_code);
        self.expirationDate = ko.observable(obj.vou_expiration_date);
        self.appliedDate = ko.observable(obj.vou_applied_date);
        self.description = ko.observable(obj.vou_description);
        self.status = ko.observable(obj.vou_status);
        self.value = ko.observable(obj.vou_value).extend({
            required: {
                params: true,
                message: 'O campo valor é obrigatório'
            }
        });
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            voucher.viewModel.voucher(self);
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
                            voucher.viewModel.vouchers.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(voucher.urlDelete, data, callback, 'DELETE');
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
                'id': self.id,
                'vou_value': self.value(),
                'vou_expiration_date': self.expirationDate(),
                'vou_description': self.description(),
                'vou_status': self.status()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id = data.response.vou_id;
                self.code(data.response.vou_code);
                self.origin = data.response;
                voucher.viewModel.voucher(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.post(voucher.urlSave, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new voucher.Voucher(self.origin),
                position = voucher.viewModel.vouchers.indexOf(item);
                voucher.viewModel.vouchers.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id) {
                voucher.viewModel.vouchers.remove(item);
            } else {
                self.goBackData(item);
            }
            voucher.viewModel.voucher(null);
        }
    }

    voucher.ViewModel = function()
    {
        let self = this;

        self.voucher = ko.observable();
        self.vouchers = ko.observableArray();
        self.status = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.vouchers(ko.utils.arrayMap(data.response.vouchers, function(obj) {
                        return new voucher.Voucher(obj);
                    }));
                    self.status(ko.utils.arrayMap(Object.keys(data.response.status), function(item) {
                        return new voucher.Status({
                            'id': item,
                            'description': data.response.status[item]
                        })
                    }));
                }
            };
            base.post(voucher.urlData, null, callback, 'GET');
        };
        self.init();

        self.addVoucher = function()
        {
            let tempVoucher = new voucher.Voucher({});
            self.vouchers.push(tempVoucher);
            tempVoucher.edit();
        }
    }

	voucher.viewModel = new voucher.ViewModel();
    ko.applyBindings(voucher.viewModel, document.getElementById('koVoucher'));

</script>
@endsection
