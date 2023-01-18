@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koMercadoLivreAnswer">
    <!-- ko with: mercadoLivreAnswer -->
    <div class="row" data-bind="visible: $root.mercadoLivreAnswer" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Resposta Automática</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
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
    <div class="row" data-bind="visible: !mercadoLivreAnswer()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addMercadoLivreAnswer">Nova Resposta Automática</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Respostas Automáticas</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Resposta</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: mercadoLivreAnswers">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
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

    function homeMercadoLivreAnswer(){[native/code]}
    homeMercadoLivreAnswer.urlData = "{{ route('api.mercadolivre.answers.index') }}";
    homeMercadoLivreAnswer.urlSave = "{{ route('api.mercadolivre.answers.store') }}";
    homeMercadoLivreAnswer.urlDelete = "{{ route('api.mercadolivre.answers.destroy') }}";

    homeMercadoLivreAnswer.MercadoLivreAnswer = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.mea_id);
        self.description = ko.observable(obj.mea_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            }
        });

        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeMercadoLivreAnswer.viewModel.mercadoLivreAnswer(self);
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
                            homeMercadoLivreAnswer.viewModel.mercadoLivreAnswers.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeMercadoLivreAnswer.urlDelete, data, callback, 'DELETE');
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
                'description': self.description()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.mea_id);
                self.origin = data.response;
                homeMercadoLivreAnswer.viewModel.mercadoLivreAnswer(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.post(homeMercadoLivreAnswer.urlSave, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeMercadoLivreAnswer.MercadoLivreAnswer(self.origin),
                position = homeMercadoLivreAnswer.viewModel.mercadoLivreAnswers.indexOf(item);
                homeMercadoLivreAnswer.viewModel.mercadoLivreAnswers.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeMercadoLivreAnswer.viewModel.mercadoLivreAnswers.remove(item);
            } else {
                self.goBackData(item);
            }
            homeMercadoLivreAnswer.viewModel.mercadoLivreAnswer(null);
        }
    }

    homeMercadoLivreAnswer.ViewModel = function()
    {
        let self = this;

        self.mercadoLivreAnswer = ko.observable();
        self.mercadoLivreAnswers = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.mercadoLivreAnswers(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeMercadoLivreAnswer.MercadoLivreAnswer(obj);
                    }));
                }
            };
            base.post(homeMercadoLivreAnswer.urlData, null, callback, 'GET');
        };
        self.init();

        self.addMercadoLivreAnswer = function()
        {
            let mercadoLivreAnswer = new homeMercadoLivreAnswer.MercadoLivreAnswer({});
            self.mercadoLivreAnswers.push(mercadoLivreAnswer);
            mercadoLivreAnswer.edit();
        }
    }

	homeMercadoLivreAnswer.viewModel = new homeMercadoLivreAnswer.ViewModel();
    ko.applyBindings(homeMercadoLivreAnswer.viewModel, document.getElementById('koMercadoLivreAnswer'));

</script>
@endsection
