<privacy-area></privacy-area>
<template id="template-privacy-area">
    <section>
        <div class="container">
            <h1 class="mb-5">SEGURANÇA E PRIVACIDADE</h1>
            <p>
                A privacidade e a segurança são fundamentais na hora de comprar qualquer produto ou serviço pela internet. Foi pensando nisso que criamos regras claras e objetivas, que irão garantir privacidade e segurança a todos os nossos clientes.
            </p>
            <p>
                Todos os dados pessoais, inclusive e-mail de nossos clientes não serão vendidos ou comercializados em hipótese alguma.
            </p>
            <p>
                Todas as vendas realizadas em nosso site são protegidas por servidor seguro, que impedem que terceiros tenham acesso a qualquer dado de nossos clientes.
            </p>
            <p>
                Nosso site utiliza o sistema SSL (Secure Socket Layer). Esse sistema criptografa os dados e oferece mais segurança em transações financeiras pela internet.
            </p>
            <p>
                Eventualmente podemos enviar alguns e-mails com promoções e lançamentos de produtos que acreditamos que irá agradar nossos usuários. Se preferir não receber esses e-mails, basta selecionar a opção no ato de sua compra ou cancelar o envio através de link disponibilizado em cada mensagem enviada.
            <p>
            <p>
                Seus dados de pagamento, em especial cartões de credito estão superprotegidos. Utilizamos SEMPRE o
                <a target="_blank" href="https://www.mercadopago.com.br/">MercadoPago</a>
                (Sistema de cobrança mundialmente utilizado) para processar o pagamento e nossa loja não tem acesso aos dados informados, recebendo apenas o status do pagamento.
            </p>
            <p>
                A Império do Mdf utiliza cookies e informações de sua navegação (sessão do browser) com o objetivo de traçar um perfil do público que visita o site e aperfeiçoar sempre nossos serviços, produtos, conteúdos e garantir as melhores ofertas e promoções para você. Durante todo este processo mantemos suas informações em sigilo absoluto. Vale lembrar que seus dados são registrados pela Império do Mdf de forma automatizada, dispensando manipulação humana.
            </p>
            <br>
            <br>
            <p class="mb-5">
                Qualquer dúvida, sugestão ou crítica, não hesite em falar conosco através de nossa área de contato preenchendo o formulário e nos enviando.
            </p>
        </div>
    </section>
</template>

<script type="text/javascript">

    function privacyArea(){[native/code]}

    privacyArea.PrivacyAreaViewModel = function() {
        let self = this;
    }

	ko.components.register('privacy-area', {
	    template: { element: 'template-privacy-area'},
	    viewModel: privacyArea.PrivacyAreaViewModel
	});
</script>
