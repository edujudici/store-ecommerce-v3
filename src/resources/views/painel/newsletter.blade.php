@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koNewsletter">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Newsletters</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Data de Cadastro</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: newsletters">
                            <tr>
                                <td scope="row" data-bind="text: new_id"></td>
                                <td><span data-bind="text: new_email"></span></td>
                                <td><span data-bind="text: base.dateTimeStringEn(created_at)"></span></td>
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

    function homeNewsletter(){[native/code]}
    homeNewsletter.urlData = "{{ route('api.newsletters.index') }}";

    homeNewsletter.ViewModel = function()
    {
        let self = this;

        self.newsletters = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.newsletters(data.response);
                }
            };
            base.post(homeNewsletter.urlData, null, callback, 'GET');
        };
        self.init();
    }

	homeNewsletter.viewModel = new homeNewsletter.ViewModel();
    ko.applyBindings(homeNewsletter.viewModel, document.getElementById('koNewsletter'));

</script>
@endsection
