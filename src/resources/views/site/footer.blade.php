<!-- start footer Area -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v7.0" nonce="IvfvMYPa"></script>


<footer id="koFooter" class="footer-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-5">
                <div class="single-footer-widget">
                    <h6 class="text-center">NEWSLETTER! Cadastre-se e receba todas as novidades de promoção e conteúdo.</h6>
                    <p class="text-center">Seja o primeiro a saber sobre nossas últimas tendências e obtenha ofertas exclusivas</p>
                    <div>
                        <form class="form-inline">
                            <div class="col-lg-8  col-md-8 col-sm-12 offset-lg-2 offset-md-2 offset-sm-0">
                                <div class="d-flex flex-row justify-content-center">
                                    <input class="form-control mr-3" name="EMAIL" placeholder="Digite seu Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu e-mail '"
                                        required="" type="email" data-bind="value: email">
                                    <button class="btn btn-default" data-bind="click: send">Inscreva-se</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Sobre nós</h6>
                    <p data-bind="text: company.com_description"></p>
                </div>
            </div>
            <div class="col-lg-3  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Institucional</h6>
                    <div class="footer-intitucional contact_info">
                        <div class="info_item">
                            <i class="lnr lnr-bubble"></i>
                            <p><a href="{{route('site.faq.index')}}">FAQ</a></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-envelope"></i>
                            <p><a href="{{route('site.contact.index')}}">Contato</a></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-lock"></i>
                            <p><a href="{{ route('site.privacy.index') }}">Política Privacidade</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Siga-nos</h6>
                    <p>Fique por dentro de todas as nossas promoções</p>
                    <div class="footer-social d-flex align-items-center">
                        <a href="https://www.facebook.com/imperiodomdf1" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Atendimento</h6>
                    <div class="contact_info">
                        <div class="info_item">
                            <i class="lnr lnr-home"></i>
                            <p data-bind="text: company.com_address"></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-phone-handset"></i>
                            <p data-bind="text: company.com_phone"></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-clock"></i>
                            <p data-bind="text: company.com_work_hours"></p>
                        </div>
                        <div class="info_item">
                            <i class="lnr lnr-envelope"></i>
                            <p data-bind="text: company.com_mail"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Pague com</h6>
                    <img src="https://imgmp.mlstatic.com/org-img/MLB/MP/BANNERS/tipo2_125X125.jpg?v=1"
                        alt="Mercado Pago - Meios de pagamento" title="Mercado Pago - Meios de pagamento"
                        width="125" height="125"/>
                </div>
            </div>
            <div class="col-lg-4  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6 class="mb-20">Curta nossa página</h6>
                    <div class="fb-page"
                        data-href="https://www.facebook.com/imperiodomdf1"
                        data-width="300"
                        data-hide-cover="false"></div>
                </div>
            </div>
            <div class="col-lg-4  col-md-6 col-sm-6 mb-3">
                <div class="single-footer-widget">
                    <h6>Selos</h6>
                    <a target="_blank" href="https://letsencrypt.org/">
                        <img class="mr-3" src="{{ asset('assets/site/img/letsencrypt.png') }}"
                            alt="Selo segurança" title="Selo segurança"
                            width="80" height="80"/>
                    </a>
                    <a target="_blank" href="https://www.mercadopago.com.br/">
                        <img class="mr-3" src="{{ asset('assets/site/img/mercadopago.jpg') }}"
                            alt="Selo segurança" title="Selo segurança"
                            width="80" height="80"/>
                    </a>
                    <a target="_blank" href="https://transparencyreport.google.com/safe-browsing/search#url=https://www.imperiodomdf.com.br/">
                        <img class="mr-3" src="{{ asset('assets/site/img/google-security.jpg') }}"
                            alt="Selo segurança" title="Selo segurança"
                            width="80" height="80"/>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
            <p class="footer-text m-0">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script> - Império do Mdf. Todos os direitos reservado | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i>                by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
        </div>
    </div>

    <!-- The Privacy Modal -->
    <div class="modal" id="privacy-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Política</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    @include('site.partials.template-privacy-area')
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</footer>
<!-- End footer Area -->
<script type="text/javascript">

    function footer(){[native/code]}

    footer.urlSaveNewsletter = "{{ route('api.newsletters.store') }}";

    footer.ViewModel = function()
    {
        let self = this;
        self.email = ko.observable().extend({
            required: {
                params: true,
                message: 'O campo e-mail é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo e-mail não pode ter mais que 255 caracteres.'
            }
        });
        self.errors = ko.validation.group(self);

        self.send = function()
        {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            let params = {
                'email': self.email()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.email(null);
                Alert.success('Cadastro realizado com sucesso!');
            };
            base.post(footer.urlSaveNewsletter, params, callback);
        }
    }

    footer.viewModel = new footer.ViewModel();
    ko.applyBindings(footer.viewModel, document.getElementById('koFooter'));
</script>

## chat
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5eb5b38c967ae56c521812f9/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
