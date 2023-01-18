@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koFaq">
    <!-- ko with: faq -->
    <div class="row" data-bind="visible: $root.faq" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Perguntas Frequentes</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título" data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-12">
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
    <div class="row" data-bind="visible: !faq()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addFaq">Novo Faq</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Perguntas Frequentes</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: faqs">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
                                <td><span data-bind="text: title"></span></td>
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

    function homeFaq(){[native/code]}
    homeFaq.urlData = "{{ route('api.faqs.index') }}";
    homeFaq.urlSave = "{{ route('api.faqs.store') }}";
    homeFaq.urlDelete = "{{ route('api.faqs.destroy') }}";

    homeFaq.Faq = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.faq_id);
        self.title = ko.observable(obj.faq_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.description = ko.observable(obj.faq_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            }
        });
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeFaq.viewModel.faq(self);
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
                            homeFaq.viewModel.faqs.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeFaq.urlDelete, data, callback, 'DELETE');
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
                'title': self.title(),
                'description': self.description()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.faq_id);
                self.origin = data.response;
                homeFaq.viewModel.faq(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.post(homeFaq.urlSave, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeFaq.Faq(self.origin),
                position = homeFaq.viewModel.faqs.indexOf(item);
                homeFaq.viewModel.faqs.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeFaq.viewModel.faqs.remove(item);
            } else {
                self.goBackData(item);
            }
            homeFaq.viewModel.faq(null);
        }
    }

    homeFaq.ViewModel = function()
    {
        let self = this;

        self.faq = ko.observable();
        self.faqs = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.faqs(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeFaq.Faq(obj);
                    }));
                }
            };
            base.post(homeFaq.urlData, null, callback, 'GET');
        };
        self.init();

        self.addFaq = function()
        {
            let faq = new homeFaq.Faq({});
            self.faqs.push(faq);
            faq.edit();
        }
    }

	homeFaq.viewModel = new homeFaq.ViewModel();
    ko.applyBindings(homeFaq.viewModel, document.getElementById('koFaq'));

</script>
@endsection
