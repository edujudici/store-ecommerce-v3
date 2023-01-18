<contact-area></contact-area>
<template id="template-contact-area">

    <!--================Contact Area =================-->
    <section class="contact_area section_gap_bottom">
        <div class="container">
            <div class="row" data-bind="html: company.com_iframe">
            </div>
            <div class="row mt-5">
                <div class="col-lg-4">
                    <div class="contact_info">
                        <div class="info_item">
                            <i class="lnr lnr-home"></i>
                            <h6 data-bind="text: company.com_address"></h6>
                             <p>Venha nos visitar.</p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-phone-handset"></i>
                            <h6><a href="#" data-bind="text: company.com_phone"></a></h6>
                            <p data-bind="text: company.com_work_hours"></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-envelope"></i>
                            <h6><a href="#" data-bind="text: company.com_mail"></a></h6>
                            <p>Envie-nos sua consulta a qualquer momento!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form class="row contact_form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nome:</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu nome'" data-bind="value: name">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu e-mail'" data-bind="value: email">
                            </div>
                            <div class="form-group">
                                <label for="subject">Assunto:</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Digite o Assunto" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite o assunto'" data-bind="value: subject">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Mensagem:</label>
                                <textarea class="form-control" name="message" id="message" rows="1" placeholder="Digite sua Mensagem" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite sua mensagem'" data-bind="value: message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button class="primary-btn" data-bind="click: sendMessage">Enviar Mensagem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--================Contact Area =================-->
</template>

<script type="text/javascript">

    function contact(){[native/code]}
    contact.urlSave = "{{ route('api.contacts.store') }}";

    contact.ContactViewModel = function() {
        let self = this;

        self.name = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo nome é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo nome não pode ter mais que 255 caracteres.'
            }
        });;
        self.email = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo e-mail é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo e-mail não pode ter mais que 255 caracteres.'
            }
        });;
        self.subject = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo assunto é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo assunto não pode ter mais que 255 caracteres.'
            }
        });;
        self.message = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo mensagem é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo mensagem não pode ter mais que 255 caracteres.'
            }
        });;
        self.errors = ko.validation.group(self);

        self.sendMessage = function() {

            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            let params = {
                'name': self.name(),
                'email': self.email(),
                'subject': self.subject(),
                'message': self.message(),
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.name(null);
                self.email(null);
                self.subject(null);
                self.message(null);
                Alert.info('Mensagem enviada com sucesso.');
            };
            base.post(contact.urlSave, params, callback);
        }
    }

	ko.components.register('contact-area', {
	    template: { element: 'template-contact-area'},
	    viewModel: contact.ContactViewModel
	});
</script>
