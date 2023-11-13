@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koFeature">
    <!-- ko with: feature -->
    <div class="row" data-bind="visible: $root.feature" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Característica</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <upload-file params="file: file, image: image, size: [50, 38]"></upload-file>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título"
                                        data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <input type="text" class="form-control" id="description"
                                        placeholder="Informe a descrição" data-bind="value: description">
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
    <div class="row" data-bind="visible: !feature()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addFeature">Nova
                Característica</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Características</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell" scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: features">
                            <tr>
                                <td class="d-none d-md-table-cell" scope="row" data-bind="text: id"></td>
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
    function homeFeature(){[native/code]}
    homeFeature.urlData = "{{ route('api.features.index') }}";
    homeFeature.urlSave = "{{ route('api.features.store') }}";
    homeFeature.urlDelete = "{{ route('api.features.destroy') }}";

    homeFeature.Feature = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.fea_id);
        self.title = ko.observable(obj.fea_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.description = ko.observable(obj.fea_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo descrição não pode ter mais que 255 caracteres.'
            }
        });
        self.image = ko.observable(base.displayImage(obj.fea_image));
        self.file  = ko.observableArray().extend({
            required: {
                message: "O campo imagem é obrigatório",
                onlyIf: function() {
                    return !self.id();
                }
            }
        });
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeFeature.viewModel.feature(self);
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
                            homeFeature.viewModel.features.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeFeature.urlDelete, data, callback, 'DELETE');
                }
            );
        }

        self.save = function()
        {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            if (!self.id()) {
                let hasImageError = base.imagesAllCropped(self.file());
                if (hasImageError) {
                    Alert.error('Uma ou mais imagem não foram cortadas');
                    return;
                }
            }

            let formData = new FormData();
            if (self.id()) {
                formData.append('id', self.id());
            }
            if (self.file().length > 0) {
                formData.append('file', self.file()[0].fileCropped);
            }
            formData.append('title', self.title());
            formData.append('description', self.description());

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.fea_id);
                self.origin = data.response;
                homeFeature.viewModel.feature(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeFeature.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeFeature.Feature(self.origin),
                position = homeFeature.viewModel.features.indexOf(item);
            homeFeature.viewModel.features.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeFeature.viewModel.features.remove(item);
            } else {
                self.goBackData(item);
            }
            homeFeature.viewModel.feature(null);
        }
    }

    homeFeature.ViewModel = function()
    {
        let self = this;

        self.feature = ko.observable();
        self.features = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.features(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeFeature.Feature(obj);
                    }));
                }
            };
            base.post(homeFeature.urlData, null, callback, 'GET');
        };
        self.init();

        self.addFeature = function()
        {
            let feature = new homeFeature.Feature({});
            self.features.push(feature);
            feature.edit();
        }
    }

	homeFeature.viewModel = new homeFeature.ViewModel();
    ko.applyBindings(homeFeature.viewModel, document.getElementById('koFeature'));

</script>
@endsection