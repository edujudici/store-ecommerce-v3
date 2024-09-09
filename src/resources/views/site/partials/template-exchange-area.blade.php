<privacy-area></privacy-area>
<template id="template-privacy-area">
    <section>
        <div class="container">
            <h1 class="mb-5">TROCAS E DEVOLUÇÕES</h1>
            <h2>Prazo para Trocas e Devoluções</h2>
            <p>O consumidor tem o direito de solicitar a troca ou devolução de um produto em até <strong>7 dias
                    corridos</strong> após o recebimento, conforme o <strong>Código de Defesa do Consumidor
                    (CDC)</strong>, especialmente para compras realizadas fora do estabelecimento físico (online ou por
                telefone).</p>
            <p>O prazo começa a contar a partir do dia seguinte ao recebimento do produto.</p>

            <h2>Condições para Troca ou Devolução</h2>
            <p>O produto deve estar nas mesmas condições em que foi recebido, ou seja, <strong>sem indícios de uso, em
                    sua embalagem original e com todos os acessórios</strong> e manuais, se aplicável.</p>
            <p>A apresentação da <strong>nota fiscal</strong> ou comprovante de compra é essencial para validar o
                processo de troca ou devolução.</p>

            <h2>Produtos com Defeito</h2>
            <p>Caso o produto apresente <strong>defeito de fabricação</strong>, o consumidor tem o direito de solicitar
                a reparação, troca ou devolução do valor pago dentro do prazo de <strong>30 dias</strong> para bens não
                duráveis e <strong>90 dias</strong> para bens duráveis, a partir da constatação do defeito.</p>
            <p>A empresa poderá realizar uma <strong>análise técnica</strong> para verificar o defeito relatado antes de
                proceder com a troca ou reembolso.</p>

            <h2>Desistência da Compra (Direito de Arrependimento)</h2>
            <p>Conforme o artigo 49 do CDC, o consumidor tem o <strong>direito de arrependimento</strong> em até 7 dias
                para compras realizadas fora do estabelecimento físico, sem a necessidade de justificar o motivo da
                desistência.</p>
            <p>O valor pago, incluindo o frete, deverá ser <strong>reembolsado integralmente</strong> ao consumidor.</p>

            <h2>Custos de Envio</h2>
            <p>No caso de devolução por arrependimento ou por defeito do produto, o custo de envio será arcado pela
                empresa, seguindo as regras do CDC.</p>
            <p>Para trocas não relacionadas a defeito, como tamanho ou cor, a política de cada empresa pode definir se o
                consumidor ou a empresa será responsável pelo custo de envio.</p>

            <h2>Formas de Reembolso</h2>
            <p>O reembolso será efetuado utilizando o mesmo método de pagamento usado na compra original, como
                <strong>estorno no cartão de crédito</strong> ou depósito em conta, dependendo do meio de pagamento.
            </p>

            <h2>Exceções à Troca e Devolução</h2>
            <p>Produtos <strong>personalizados</strong>, feitos sob encomenda ou com características específicas
                solicitadas pelo consumidor, podem não ser elegíveis para troca ou devolução, exceto em casos de
                defeito.</p>
            <br>
            <br>
            <p class="mb-5">
                Qualquer dúvida, sugestão ou crítica, não hesite em falar conosco através de nossa área de contato
                preenchendo o formulário e nos enviando.
            </p>
        </div>
    </section>
</template>

<script type="text/javascript">
    function privacyArea() {
        [native / code]
    }

    privacyArea.PrivacyAreaViewModel = function() {
        let self = this;
    }

    ko.components.register('privacy-area', {
        template: {
            element: 'template-privacy-area'
        },
        viewModel: privacyArea.PrivacyAreaViewModel
    });
</script>
