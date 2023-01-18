@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koBrand">
    <!-- ko with: brand -->
    <div class="row" data-bind="visible: $root.brand" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Marca</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <upload-file params="file: file, image: image, size: [110, 70]"></upload-file>
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
    <div class="row" data-bind="visible: !brand()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addBrand">Nova Marca</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Marcas</h2>
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
                        <tbody data-bind="foreach: brands">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
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

    function homeBrand(){[native/code]}
    homeBrand.urlData = "{{ route('api.brands.index') }}";
    homeBrand.urlSave = "{{ route('api.brands.store') }}";
    homeBrand.urlDelete = "{{ route('api.brands.destroy') }}";

    homeBrand.Brand = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.bra_id);
        self.title = ko.observable(obj.bra_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.image = ko.observable(base.displayImage(obj.bra_image));
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
            homeBrand.viewModel.brand(self);
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
                            homeBrand.viewModel.brands.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeBrand.urlDelete, data, callback, 'DELETE');
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

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.bra_id);
                self.origin = data.response;
                homeBrand.viewModel.brand(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeBrand.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeBrand.Brand(self.origin),
                position = homeBrand.viewModel.brands.indexOf(item);
            homeBrand.viewModel.brands.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeBrand.viewModel.brands.remove(item);
            } else {
                self.goBackData(item);
            }
            homeBrand.viewModel.brand(null);
        }
    }

    homeBrand.ViewModel = function()
    {
        let self = this;

        self.brand = ko.observable();
        self.brands = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.brands(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeBrand.Brand(obj);
                    }));
                }
            };
            base.post(homeBrand.urlData, null, callback, 'GET');
        };
        self.init();

        self.addBrand = function()
        {
            let brand = new homeBrand.Brand({});
            self.brands.push(brand);
            brand.edit();
        }
    }

	homeBrand.viewModel = new homeBrand.ViewModel();
    ko.applyBindings(homeBrand.viewModel, document.getElementById('koBrand'));

</script>
@endsection
