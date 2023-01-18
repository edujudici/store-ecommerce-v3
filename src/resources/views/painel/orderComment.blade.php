@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koComment">
    <!-- ko with: comment -->
    <div class="row" data-bind="visible: $root.comment" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2 class="col-lg-12 pl-0">Resposta de comentário para a ordem</h2>
                    <span class="col-lg-12 p-3" data-bind="text: order.ord_protocol"></span>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <upload-file params="file: file, image: image, size: [500, 500]"></upload-file>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="answer">Pergunta</label>
                                    <textarea class="form-control" id="answer" rows="6" data-bind="value: question" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="answer">Resposta</label>
                                    <textarea class="form-control" id="answer" rows="6" placeholder="Informe a resposta" data-bind="value: answer"></textarea>
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
    <div class="row" data-bind="visible: !comment()" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Comentários de Produtos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Pergunta</th>
                                <th scope="col">Data</th>
                                <th scope="col">Respondido</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: comments">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
                                <td><span data-bind="text: name"></span></td>
                                <td><span data-bind="text: question"></span></td>
                                <td><span data-bind="text: base.dateTimeStringEn(questionDate)"></span></td>
                                <td><span data-bind="text: answer() ? 'Sim' : 'Não'" ></span></td>
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

    function commentArea(){[native/code]}
    commentArea.urlData = "{{ route('api.orders.comments.indexAll') }}";
    commentArea.urlSave = "{{ route('api.orders.comments.store') }}";
    commentArea.urlDelete = "{{ route('api.orders.comments.destroy') }}";

    commentArea.Comment = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.orc_id);
        self.orderId = obj.ord_id;
        self.name = obj.orc_name;
        self.question = obj.orc_question;
        self.questionDate = obj.created_at;
        self.answer = ko.observable(obj.orc_answer).extend({
            required: {
                params: true,
                message: 'O campo resposta é obrigatório'
            }
        });
        self.answerDate = obj.orc_answer_date;
        self.order = obj.order;
        self.image = ko.observable(base.displayImage(obj.orc_image));
        self.file  = ko.observableArray();
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            commentArea.viewModel.comment(self);
        }

        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    let data = {
                        id : item.id
                    },
                    callback = function(data)
                    {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        } else {
                            commentArea.viewModel.comments.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(commentArea.urlDelete, data, callback, 'DELETE');
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
                formData.append('orc_id', self.id());
            }
            if (self.file().length > 0) {
                formData.append('file', self.file()[0].fileCropped);
            }
            formData.append('id', self.order.ord_protocol);
            formData.append('orc_answer', self.answer());
            formData.append('orc_answer_date', '{{ date("Y-m-d H:i:s") }}');

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.origin = data.response;
                commentArea.viewModel.comment(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(commentArea.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new commentArea.Comment(self.origin),
                position = commentArea.viewModel.comments.indexOf(item);
                commentArea.viewModel.comments.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                commentArea.viewModel.comments.remove(item);
            } else {
                self.goBackData(item);
            }
            commentArea.viewModel.comment(null);
        }
    }

    commentArea.ViewModel = function()
    {
        let self = this;

        self.comment = ko.observable();
        self.comments = ko.observableArray();
        self.init = function()
        {
            let params = {
                'page': base.getParamUrl('page'),
            },
            callback = function(data) {
                if (data.status) {
                    self.comments(ko.utils.arrayMap(data.response, function(obj) {
                        return new commentArea.Comment(obj);
                    }));
                }
            };
            base.post(commentArea.urlData, params, callback, 'GET');
        };
        self.init();

        self.addComment = function()
        {
            let comment = new commentArea.Comment({});
            self.comments.push(comment);
            comment.edit();
        }
    }

	commentArea.viewModel = new commentArea.ViewModel();
    ko.applyBindings(commentArea.viewModel, document.getElementById('koComment'));

</script>
@endsection
