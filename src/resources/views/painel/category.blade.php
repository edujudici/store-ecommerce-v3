@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koCategory">
    <!-- ko with: category -->
    <div class="row" data-bind="visible: $root.category" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Categoria</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <upload-file params="file: file, image: image, size: [350, 190]"></upload-file>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título" data-bind="value: title">
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
    <div class="row" data-bind="visible: !category()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addCategory">Nova Categoria</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Categorias</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: categories">
                            <tr>
                                <td scope="row" data-bind="text: idSecondary ? idSecondary : id"></td>
                                <td><span data-bind="text: title"></span></td>
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

    function homeCategory(){[native/code]}
    homeCategory.urlData = "{{ route('api.categories.index') }}";
    homeCategory.urlSave = "{{ route('api.categories.store') }}";
    homeCategory.urlDelete = "{{ route('api.categories.destroy') }}";

    homeCategory.Category = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.cat_id);
        self.idSecondary = obj.cat_id_secondary;
        self.title = ko.observable(obj.cat_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.image = ko.observable(base.displayImage(obj.cat_image));
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
            homeCategory.viewModel.category(self);
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
                            homeCategory.viewModel.categories.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeCategory.urlDelete, data, callback, 'DELETE');
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
            if (self.idSecondary) {
                formData.append('cat_id_secondary', self.idSecondary);
            }
            formData.append('cat_title', self.title());

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.cat_id);
                self.origin = data.response;
                homeCategory.viewModel.category(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeCategory.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeCategory.Category(self.origin),
                position = homeCategory.viewModel.categories.indexOf(item);
            homeCategory.viewModel.categories.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeCategory.viewModel.categories.remove(item);
            } else {
                self.goBackData(item);
            }
            homeCategory.viewModel.category(null);
        }
    }

    homeCategory.ViewModel = function()
    {
        let self = this;

        self.category = ko.observable();
        self.categories = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.categories(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeCategory.Category(obj);
                    }));
                }
            };
            base.post(homeCategory.urlData, null, callback, 'GET');
        };
        self.init();

        self.addCategory = function()
        {
            let category = new homeCategory.Category({});
            self.categories.push(category);
            category.edit();
        }
    }

	homeCategory.viewModel = new homeCategory.ViewModel();
    ko.applyBindings(homeCategory.viewModel, document.getElementById('koCategory'));

</script>
@endsection
