@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koProduct">
    <!-- ko with: product -->
    <div class="row" data-bind="visible: $root.product" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Produto</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <upload-file params="file: file, image: image, size: [220, 240], multiple: true"></upload-file>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select id="categoria" class="form-control" data-bind="
                                        options: $root.categories,
                                        optionsText: 'cat_title',
                                        optionsValue: 'cat_id',
                                        optionsCaption: 'Selecione a categoria',
                                        value: categoryId">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="price">Preço</label>
                                    <input type="text" class="form-control" id="price" placeholder="Informe o preço" data-bind="value: price">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="old-price">Preço Original</label>
                                    <input type="text" class="form-control" id="old-price" placeholder="Informe o preço original" data-bind="value: oldPrice">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título" data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="inventory">Qtde Estoque</label>
                                    <input type="number" class="form-control" id="inventory" placeholder="Informe o total no estoque" data-bind="value: inventory">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Descrição Breve</label>
                                    <textarea class="form-control" id="description" rows="3" placeholder="Informe uma descrição simplificada do produto" data-bind="value: description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="longDesc">Descrição Completa</label>
                                    <textarea class="form-control" id="longDesc" rows="6" placeholder="Informe uma descrição mais detalhada do produto" data-bind="value: longDesc"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="price"></label>
                                    <label class="control control-checkbox">É um item promocional?
                                        <input type="checkbox" name="exclusive" data-bind="checked: isProductExclusive" />
                                        <div class="control-indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-default">
                                    <div class="card-header card-header-border-bottom">
                                        <h2>Produtos Relacionados</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input class="form-control"
                                                type="text"
                                                data-bind="
                                                    autocomplete: productRelated,
                                                    source: homeProduct.urlGetProductsByName,
                                                    render: productRelatedTransform"/>
                                        </div>

                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Id</th>
                                                    <th scope="col">Título</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody data-bind="foreach: productsRelated">
                                                <tr>
                                                    <td><span data-bind="text: sku"></span></td>
                                                    <td><span data-bind="text: title"></span></td>
                                                    <td class="center">
                                                        <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-default">
                                    <div class="card-header card-header-border-bottom">
                                        <h2>Especificações do Produto</h2>
                                    </div>
                                    <div class="card-body">
                                        <!-- ko with: specification -->
                                        <div class="row" style="display: flex">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="key">Chave</label>
                                                    <input type="text" class="form-control" id="key" placeholder="Informe a chave" data-bind="value: key">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="value">Valor</label>
                                                    <input type="text" class="form-control" id="value" placeholder="Informe o valor" data-bind="value: value">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <button type="submit" class="btn btn-primary mb-2 mt-30" data-bind="click: $parent.addSpecification">Adicionar</button>
                                            </div>
                                        </div>
                                        <!-- /ko -->

                                        <table class="table table-hover ">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Chave</th>
                                                    <th scope="col">Valor</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody data-bind="foreach: specifications">
                                                <tr>
                                                    <td><span data-bind="text: key"></span></td>
                                                    <td><span data-bind="text: value"></span></td>
                                                    <td class="center">
                                                        <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    <div class="row" data-bind="visible: !product()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addProduct">Novo Produto</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Produtos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#sku</th>
                                <th scope="col">Título</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Preço Original</th>
                                <th scope="col">Promocional</th>
                                <th scope="col">Estoque</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: products">
                            <tr>
                                <td scope="row" data-bind="text: sku"></td>
                                <td><span data-bind="text: title"></span></td>
                                <td><span data-bind="text: price"></span></td>
                                <td><span data-bind="text: oldPrice"></span></td>
                                <td><span data-bind="text: isProductExclusive() ? 'Sim' : 'Não'"></span></td>
                                <td><span data-bind="text: inventory"></span></td>
                                <td class="center">
                                    <i class="mdi mdi-pencil" aria-hidden="true" data-bind="click: edit"></i>
                                    <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination" data-bind="html: pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">

    function homeProduct(){[native/code]}
    homeProduct.urlData = "{{ route('api.products.index') }}";
    homeProduct.urlSave = "{{ route('api.products.store') }}";
    homeProduct.urlDelete = "{{ route('api.products.destroy') }}";
    homeProduct.urlGetCategories = "{{ route('api.categories.index') }}";
    homeProduct.urlGetSpecifications = "{{ route('api.productsSpecifications.index') }}";
    homeProduct.urlGetProductRelated = "{{ route('api.productsRelateds.index') }}";
    homeProduct.urlGetProductsByName = "{{ route('api.products.findByName') }}";
    homeProduct.urlGetPictures = "{{ route('api.pictures.index') }}";

    homeProduct.Specification = function(obj)
    {
        let self = this;

        self.key = ko.observable(obj.prs_key);
        self.value = ko.observable(obj.prs_value);
        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    homeProduct.viewModel.product().specifications.remove(item);
                    Alert.success('Clique em enviar para completar essa ação.');
                }
            );
        }
    }

    homeProduct.Related = function(obj)
    {
        let self = this;

        self.sku = obj.pro_sku;
        self.title = obj.pro_title;
        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    homeProduct.viewModel.product().productsRelated.remove(item);
                    Alert.success('Clique em enviar para completar essa ação.');
                }
            );
        }
    }

    homeProduct.Product = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.pro_id);
        self.sku = ko.observable(obj.pro_sku);
        self.categoryId = ko.observable(obj.cat_id);
        self.isProductExclusive = ko.observable(obj.exclusive_deal ? true : false);
        self.price = ko.observable(obj.pro_price).extend({
            required: {
                params: true,
                message: 'O campo preço é obrigatório'
            },
            number: {
                params: true,
                message: 'O campo preço não é numérico.'
            }
        });
        self.oldPrice = ko.observable(obj.pro_oldprice).extend({
            required: {
                params: true,
                message: 'O campo preço original é obrigatório'
            },
            number: {
                params: true,
                message: 'O campo preço original não é numérico.'
            }
        });
        self.title = ko.observable(obj.pro_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.description = ko.observable(obj.pro_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo descrição não pode ter mais que 255 caracteres.'
            }
        });
        self.longDesc = ko.observable(obj.pro_description_long);
        self.inventory = ko.observable(obj.pro_inventory);
        self.image = ko.observableArray(obj.pictures);
        self.file  = ko.observableArray().extend({
            required: {
                message: "O campo imagem é obrigatório",
                onlyIf: function() {
                    return !self.id();
                }
            }
        });
        self.productRelated = ko.observableArray();
        self.productsRelated = ko.observableArray();
        self.specification = ko.observable(new homeProduct.Specification({}));
        self.specifications = ko.observableArray(obj.specifications);
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeProduct.viewModel.product(self);
            self.loadSpecifications();
            self.loadProductsRelated();
            self.loadPictures();
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
                            homeProduct.viewModel.products.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeProduct.urlDelete, data, callback, 'DELETE');
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
            if (self.categoryId()) {
                formData.append('cat_id', self.categoryId());
            }
            if (self.file().length > 0) {
                ko.utils.arrayForEach(self.file(), function(obj) {
                    formData.append('file[]', obj.fileCropped);
                });
            }
            if (self.isProductExclusive()) {
                formData.append('isProductExclusive', self.isProductExclusive());
            }
            if (self.specifications().length > 0) {
                let data = ko.utils.arrayMap(self.specifications(), function(obj) {
                    return {
                        'prs_key': obj.key(),
                        'prs_value': obj.value(),
                    }
                });
                base.createFormData(formData, 'specifications', data);
            }
            if (self.productsRelated().length > 0) {
                let data = ko.utils.arrayMap(self.productsRelated(), function(obj) {
                    return {
                        'pro_sku_related': obj.sku,
                    }
                });
                base.createFormData(formData, 'products', data);
            }
            formData.append('pro_title', self.title());
            formData.append('pro_description', self.description());
            formData.append('pro_description_long', self.longDesc());
            formData.append('pro_inventory', self.inventory());
            formData.append('pro_price', self.price());
            formData.append('pro_oldprice', self.oldPrice());

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.pro_id);
                self.sku(data.response.pro_sku);
                self.origin = data.response;
                homeProduct.viewModel.product(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeProduct.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeProduct.Product(self.origin),
                position = homeProduct.viewModel.products.indexOf(item);
            homeProduct.viewModel.products.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeProduct.viewModel.products.remove(item);
            } else {
                self.goBackData(item);
            }
            homeProduct.viewModel.product(null);
        }

        self.addSpecification = function()
        {
            self.specifications.push(self.specification());
            self.specification(new homeProduct.Specification({}));
        }

        self.loadSpecifications = function()
        {
            if (self.specifications().length > 0 || !self.id()) {
                return;
            }
            let params = {
                'sku': self.sku(),
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.specifications(ko.utils.arrayMap(data.response, function(obj) {
                    return new homeProduct.Specification(obj);
                }));
                self.origin.specifications = self.specifications();
            };
            base.post(homeProduct.urlGetSpecifications, params, callback, 'GET');
        }

        self.loadProductsRelated = function()
        {
            if (self.productsRelated().length > 0 || !self.id()) {
                return;
            }
            let params = {
                'sku': self.sku(),
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.productsRelated(ko.utils.arrayMap(data.response, function(obj) {
                    return new homeProduct.Related(obj);
                }));
                self.origin.productsRelated = self.productsRelated();
            };
            base.post(homeProduct.urlGetProductRelated, params, callback, 'GET');
        }

        self.loadPictures = function()
        {
            if (self.image().length > 0 || !self.id()) {
                return;
            }
            let params = {
                'sku': self.sku(),
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.image(ko.utils.arrayMap(data.response, function(obj) {
                    return base.displayImage(obj.pic_image || obj.pic_secure_url)
                }));
                self.origin.pictures = self.image();
            };
            base.post(homeProduct.urlGetPictures, params, callback, 'GET');
        }

        self.productRelatedTransform = function(data)
        {
            if (data.status) {
                return ko.utils.arrayMap(data.response, function(item) {
                    return item.pro_sku + ' - ' + item.pro_title;
                });
            }
        }

        self.productRelated.subscribe(function(value) {
            if (value) {
                let valueSplit = value.split(' - '),
                    obj = {
                        'pro_sku': valueSplit[0],
                        'pro_title': valueSplit[1]
                    };

                let exists = ko.utils.arrayFirst(self.productsRelated(), function(item) {
                    return item.sku == obj.pro_sku
                });
                if (exists) {
                    Alert.error('Produto já relacionado');
                    return;
                }

                self.productsRelated.push(new homeProduct.Related(obj));
            }
        });
    }

    homeProduct.ViewModel = function()
    {
        let self = this;

        self.product = ko.observable();
        self.products = ko.observableArray();
        self.categories = ko.observableArray();
        self.pagination = ko.observable();
        self.init = function()
        {
            let params = {
                'page': base.getParamUrl('page'),
            },
            callback = function(data) {
                if (data.status) {
                    self.loadCategories();
                    self.pagination(data.response.pagination);
                    self.products(ko.utils.arrayMap(data.response.products, function(obj) {
                        return new homeProduct.Product(obj);
                    }));
                }
            };
            base.post(homeProduct.urlData, params, callback, 'GET');
        };
        self.init();

        self.addProduct = function()
        {
            let product = new homeProduct.Product({});
            product.loadProductsRelated();
            self.product(product);
            self.products.push(product);
        }

        self.loadCategories = function()
        {
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.categories(data.response);
            };
            base.post(homeProduct.urlGetCategories, null, callback, 'GET');
        }
    }

	homeProduct.viewModel = new homeProduct.ViewModel();
    ko.applyBindings(homeProduct.viewModel, document.getElementById('koProduct'));
</script>
@endsection
