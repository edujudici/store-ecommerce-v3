<faq-area></faq-area>
<template id="template-faq-area">

    <!-- start faq Area -->
    <section class="cart_area" data-bind="visible: faqs().length == 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h1>Não existe perguntas no momento!</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section data-bind="visible: faqs().length > 0">
        <div class="container">
            <ul style="list-style-type: disclosure-closed;">

                <!-- ko foreach: faqs-->
                    <li class="section-top-border pb-3">
                        <a href="#"><h3 class="mb-30" data-bind="text: title, click: showDescription"></h3></a>
                        <div class="row" data-bind="visible: isShowDescription">
                            <div class="col-lg-12">
                                <blockquote class="generic-blockquote" data-bind="text: description"></blockquote>
                            </div>
                        </div>
                    </li>
                <!-- /ko -->

            </ul>
        </div>
    </section>
</template>

<script type="text/javascript">

    function faqArea(){[native/code]}
    faqArea.urlGetFaqs = "{{ route('api.faqs.index') }}";

    faqArea.Faq = function(obj) {
        let self = this;
        self.title = obj.faq_title;
        self.description = obj.faq_description;
        self.isShowDescription = ko.observable(true);

        self.showDescription = function() {
            self.isShowDescription(!self.isShowDescription());
        }
    }

    faqArea.FaqAreaViewModel = function() {
        let self = this;

        self.faqs = ko.observableArray();
        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.faqs(ko.utils.arrayMap(data.response, function(obj) {
                    return new faqArea.Faq(obj)
                }));
            };
            base.post(faqArea.urlGetFaqs, null, callback, 'GET');
        }
        self.init();
    }

	ko.components.register('faq-area', {
	    template: { element: 'template-faq-area'},
	    viewModel: faqArea.FaqAreaViewModel
	});
</script>
