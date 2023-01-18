/*
     exemplo de como criar um componente knockout
    ko.bindingHandlers.yourBindingName = {
        init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called when the binding is first applied to an element
            // Set up any initial state, event handlers, etc. here
        },
        update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called once when the binding is first applied to an element,
            // and again whenever any observables/computeds that are accessed change
            // Update the DOM element based on the supplied values here.
        }
    };
*/

/*
    AUTOCOMPLETE
        MODO DE USO:
            <input
                type="text"
                data-bind="
                    autocomplete:<o ko.obervable do objeto que irá receber o valor>,
                    source:'<a url do controller para onde a chamada deve ser direcionada>',
                    render: <opcional, callback que monta as opções do autocomplete quando o retono não é chave=>valor>
                    onselect: <opcional, callback com a ação de onselect do inpu
                "
            />
*/
ko.bindingHandlers.autocomplete = {
    init: function(element, valueAccessor, allBindings) {
        var value = valueAccessor();
        var urlSource = allBindings.get('source');
        var render = allBindings.get('render');
        var onselect = allBindings.get('onselect');
        var opcoes = allBindings.get('opcoes');
        var customrequest = allBindings.get('customrequest');

        //  atribuindo automaticamente um loading para sinalizar o carregamento
        $(element).parent().append("<img class='loading-gif-ui-autocomplete'src='"+GLOBAL_PATH_LOADING_GIF_KNOCKOUT+"' style='display:none;width: 15px;position: relative;float: right;top: -25px;left: -15px;'>");

        $(element).autocomplete({
            source: function(request, response)
            {
                $(element).parent().find('img.loading-gif-ui-autocomplete').show();
                if(opcoes)
                {
                    var filtrados = opcoes;
                    if(request.term != null)
                    {
                        filtrados = ko.utils.arrayFilter(filtrados, function(i)
                        {
                            return i.toString().toLowerCase().indexOf(request.term.toString().toLowerCase()) != -1;
                        });
                    }
                    response(filtrados);
                    $(element).parent().find('img.loading-gif-ui-autocomplete').hide();
                }
                else
                {
                    if(!!customrequest && typeof(customrequest) == 'function'){
                        customrequest(request, response,$(element).parent().find('img.loading-gif-ui-autocomplete'));
                    } else {
                        $.get(urlSource, {'term':request.term})
                            .done(function(dados)
                            {
                                // por problemas de exibição nos modais. trazer o z-indez da lista para frente sempre
                                $(".ui-autocomplete").css('z-index', '999999');
                                // para customizar a renderização dos dados como concatenação de id - nome fazer um callback que receba os dados e formate em array
                                if(render != null)
                                {
                                    response(render(dados));
                                }
                                // senão trazer os dados formatados em array direto do service
                                else
                                {
                                    response(dados);
                                }
                            }).fail(function(error)
                            {
                                console.log('Um erro ocorreu no componente de autocomplete.');
                                console.log(error);
                            }).always(function()
                            {
                                $(element).parent().find('img.loading-gif-ui-autocomplete').hide();
                            });
                    }
                    // $(element).parent().find('img.loading-gif-ui-autocomplete').show();
                }
            },
            minlength: 3,
            select: function(event, ui)
            {
                // sempre que selecionado o valor do input recebe a string no observable... para fazer uma pos exexução setar o callback onselect
                valueAccessor()(ui.item.value);
                if(onselect != null)
                {
                    onselect(event, ui);
                }
            }
        });
        ko.utils.registerEventHandler(element, 'focusout', function() {
            var observable = valueAccessor();
            observable($(element).val());
        });

        ko.utils.registerEventHandler(element, 'keypress', function(e) {
            if (e.which == 13)
            {
                var observable = valueAccessor();
                observable($(element).val());
            }
        });
    },
    update: function(element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        $(element).val(value);
    }
};
ko.validation.makeBindingHandlerValidatable('autocomplete');

/*
    MASKED
        MODO DE USO:
            <input
                type="text"
                data-bind="
                    masked:<ko.observable do objeto>
                    pattern:'<obrigatório string com o pattern desejado ex: (99) 9999-9999?9>'
                "
            />
*/
ko.bindingHandlers.masked = {
    init: function(element, valueAccessor, allBindingsAccessor) {

        var mask = allBindingsAccessor().mask || {};
        $(element).mask(mask);

        ko.utils.registerEventHandler(element, 'focusout', function() {
            var observable = valueAccessor();
            observable($(element).val());
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        var mask = allBindingsAccessor().mask || {};
        var value = ko.utils.unwrapObservable(valueAccessor());
        $(element).val(value);
        $(element).mask(mask);
    }
};
ko.validation.makeBindingHandlerValidatable('masked');

/*
        font: http://stackoverflow.com/questions/15775608/using-nicedit-with-knockout
        And how to use it:

        <textarea id="area1" data-bind="nicedit: title" style="width: 640px"></textarea>
        ... where in my example "title" is your bound property of course.

        There are two "limitations":

        The textarea must have a DOM "id" attribute otherwise it crashes.
        With IE (at least, version 8) the DOMNodeInserted and DOMNodeRemoved
        are not fired, which means that you have to type something after
        modifying the style of your text for it to be properly updated in
        your observable object.
*/
ko.bindingHandlers.nicedit = {
    init: function(element, valueAccessor) {
        var value = valueAccessor();
        var area = new nicEditor({fullPanel : true}).panelInstance(element, {hasPanel : true});
        // $(element).text(ko.utils.unwrapObservable(value));

        // function for updating the right element whenever something changes
        var textAreaContentElement = $($(element).prev()[0].childNodes[0]);
        var areachangefc = function() {
            value(textAreaContentElement.html());
        };
        //setando dados na inicializaÃ§Ã£o
        textAreaContentElement.html(value());

        // Make sure we update on both a text change, and when some HTML has been added/removed
        // (like for example a text being set to "bold")
        $(element).prev().keyup(areachangefc);
        $(element).prev().bind('DOMNodeInserted DOMNodeRemoved', areachangefc);
    },
    update: function(element, valueAccessor) {
        //o update buga o esquema. migrado para o init function que ja tem o keyup e DOMNodeInserted, DOMNodeRemoved function
        /*var value = valueAccessor();
        var textAreaContentElement = $($(element).prev()[0].childNodes[0]);
        textAreaContentElement.html(value());*/
    }
};
ko.validation.makeBindingHandlerValidatable('nicedit');

/*
    NUMERICO
        MODO DE USO:
            <input
                type="text"
                data-bind="
                    numerico:<ko.observable do objeto>,
                    precisao:'<opcional, padrão 0. numero de casas decimais para exibição>'
                "
            />
        OBS: por mais que a exibição mostre o numero formatado no padrão pt-BR o numero continua do tipo float no observable ;)
*/
ko.bindingHandlers.numerico = {
    init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        var precisao = allBindings.get('precisao')||0;
        var valor = ko.utils.unwrapObservable(valueAccessor());

        valor = valor == null || isNaN(valor) ? 0 : valor;
        valor = parseFloat(parseFloat(valor.toString()).toFixed(precisao));
        element.style.textAlign = 'right';

        if(element.tagName == 'INPUT')
        {
            element.value = valor.toLocaleString("pt-BR", { minimumFractionDigits: precisao, maximumFractionDigits: precisao });
        }
        else
        {
            element.innerHTML = valor.toLocaleString("pt-BR", { minimumFractionDigits: precisao, maximumFractionDigits: precisao });
        }

        if(ko.isWriteableObservable(valueAccessor()))
        {
            valueAccessor()(valor);
        }

        ko.utils.registerEventHandler(element, 'input', function()
        {
            var valor = element.value.replace(/\D/g,'');

            if(valor == null || valor == '')
            {
                valor = '0';
            }

            if(precisao > 0)
            {
                valor = parseFloat(valor.substr(0, valor.length - precisao) + '.' + valor.substr(valor.length - precisao, valor.length));
            }
            else
            {
                valor = parseFloat(parseFloat(valor).toFixed(precisao));
            }

            element.value = valor.toLocaleString("pt-BR", { minimumFractionDigits: precisao, maximumFractionDigits: precisao });

            if(ko.isWriteableObservable(valueAccessor()))
            {
                valueAccessor()(valor);
            }
        });
    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        var precisao = allBindings.get('precisao')||0;
        var valor = ko.utils.unwrapObservable(valueAccessor());

        valor = valor == null ? 0 : valor;
        valor = typeof(valor) == 'string' && valor.indexOf(",") != -1 ? valor.replace(/\./g,'').replace(",",".") : valor;
        valor = parseFloat(valor).toFixed(precisao);
        valor = isNaN(valor) ? valor.toString().replace(/\D/g,'') : valor;

        if(valor == null || valor == '')
        {
            valor = 0;
        }

        valor = parseFloat(valor);

        if(element.tagName == 'INPUT')
        {
            element.value = valor.toLocaleString("pt-BR", { minimumFractionDigits: precisao, maximumFractionDigits: precisao });
        }
        else
        {
            element.innerHTML = valor.toLocaleString("pt-BR", { minimumFractionDigits: precisao, maximumFractionDigits: precisao });
        }

        if(ko.isWriteableObservable(valueAccessor()))
        {
            valueAccessor()(valor);
        }
    }
};
ko.validation.makeBindingHandlerValidatable('numerico');

/*
    DATEPICKER
        MODO DE USO:
            <input
                type="text"
                data-bind="
                    value: <ko.obervable do objeto>,
                    datepicker: <ko.obervable do objeto>,
                    datepickerOptions: {
                        callbackOk         :<opcional callback com a ação do onselect do calendário>,
                        format             :<opcional string 'yy-mm-dd' ou qualquer outra para o retono da data selecionada no observable>,
                        showDays           :<opcional flag para mostrar ou não os dias no calendário>,
                        showMonths         :<opcional flag para mostrar ou não os meses no calendário>,
                        showYears          :<opcional flag para mostrar ou não o ano no calendário>,
                        showIconsNavigation:<opcional flag para mostrar ou não os botoes de navegação do calendário>,
                    }
                    //TODAS ESSAS OPÇÕES PODEM ESTAR DENTRO DE UM UNICO OBJETO  SIMPLIFICANDO A CONFIGURAÇÃO
                    datepickerOptions:<objeto contendo as opções>
                "
            />
*/
ko.bindingHandlers.datepicker = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        var options = allBindingsAccessor().datepickerOptions || {};

        // valores defaul
        options.dayNames            = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        options.dayNamesMin         = ['D','S','T','Q','Q','S','S','D'];
        options.dayNamesShort       = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'];
        options.monthNames          = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        options.monthNamesShort     = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        options.nextText            = 'Próximo';
        options.prevText            = 'Anterior';
        options.closeText           = 'ok';
        options.changeMonth         = true;
        options.changeYear          = true;
        options.showButtonPanel     = true;
        options.dateFormat          = 'dd/mm/yy';
        options.format              = options.format != undefined ? options.format : 'dd/mm/yy';
        options.showDays            = options.showDays != undefined ? options.showDays : true;
        options.showMonths          = options.showMonths != undefined ? options.showMonths : true;
        options.showYears           = options.showYears != undefined ? options.showYears : true;
        options.showIconsNavigation = options.showIconsNavigation != undefined ? options.showIconsNavigation : true;

        options.onClose = function(dateText, inst){
            var data = $.datepicker.formatDate(options.format, new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay))
            valueAccessor()(data);
            $(element).val(data);
            if(options.callbackOk != undefined && typeof(options.callbackOk) == 'function')
            {
                options.callbackOk();
            }
        };

        $(element).val(valueAccessor()());
        $(element).datepicker(options);

        //setando valores default para o datepicker independente da configuração de idioma
        $.datepicker.setDefaults(options);

        $(element).focus(function(){
            if (!options.showDays) $(".ui-datepicker-calendar").hide();
            if (!options.showMonths) $(".ui-datepicker-month").hide();
            if (!options.showYears) $(".ui-datepicker-year").hide();
            if (!options.showIconsNavigation) $("div.ui-datepicker-header a.ui-datepicker-prev,div.ui-datepicker-header a.ui-datepicker-next").hide();
        });

        //handle the field changing
        ko.utils.registerEventHandler(element, "change", function () {
            var observable = valueAccessor();
            observable($.datepicker.formatDate(options.format, $(element).datepicker("getDate")));
        });

        //handle disposal (if KO removes by the template binding)
        ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
            $(element).datepicker("destroy");
        });
    }
};
ko.validation.makeBindingHandlerValidatable('datepicker');


ko.bindingHandlers.sortable = {
    init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called when the binding is first applied to an element
        // Set up any initial state, event handlers, etc. here
        var
            list = valueAccessor(),
            defaultAction = function(){},
            actionActivate   = allBindings.get('activate')   || defaultAction,
            actionBeforeStop = allBindings.get('beforeStop') || defaultAction,
            actionChange     = allBindings.get('change')     || defaultAction,
            actionCreate     = allBindings.get('create')     || defaultAction,
            actionDeactivate = allBindings.get('deactivate') || defaultAction,
            actionOut        = allBindings.get('out')        || defaultAction,
            actionOver       = allBindings.get('over')       || defaultAction,
            actionReceive    = allBindings.get('receive')    || defaultAction,
            actionRemove     = allBindings.get('remove')     || defaultAction,
            actionSort       = allBindings.get('sort')       || defaultAction,
            actionStart      = allBindings.get('start')      || defaultAction,
            actionStop       = allBindings.get('stop')       || defaultAction
            //update           = allBindings.get('update')     || defaultAction,
        ;
        $(element).sortable(
        {
            // containment: 'parent',
            // placeholder: 'placeholder',
            update: function (event, ui)
            {
                var item = ko.dataFor(ui.item[0]),
                    newIndex = ko.utils.arrayIndexOf(ui.item.parent().children(), ui.item[0]);
                if (newIndex >= list().length) newIndex = list().length - 1;
                if (newIndex < 0) newIndex = 0;

                ui.item.remove();
                list.remove(item);
                list.splice(newIndex, 0, item);
            },
            activate: function( event, ui )
            {
                actionActivate(event, ui);
            },
            beforeStop: function( event, ui )
            {
                actionBeforeStop(event, ui);
            },
            change: function( event, ui )
            {
                actionChange(event, ui);
            },
            create: function( event, ui )
            {
                actionCreate(event, ui);
            },
            deactivate: function( event, ui )
            {
                actionDeactivate(event, ui);
            },
            out: function( event, ui )
            {
                actionOut(event, ui);
            },
            over: function( event, ui )
            {
                actionOver(event, ui);
            },
            receive: function( event, ui )
            {
                actionReceive(event, ui);
            },
            remove: function( event, ui )
            {
                actionRemove(event, ui);
            },
            sort: function( event, ui )
            {
                actionSort(event, ui);
            },
            start: function( event, ui )
            {
                actionStart(event, ui);
            },
            stop: function( event, ui )
            {
                actionStop(event, ui);
            }
        });
    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called once when the binding is first applied to an element,
        // and again whenever any observables/computeds that are accessed change
        // Update the DOM element based on the supplied values here.
    }
};
ko.validation.makeBindingHandlerValidatable('sortable');


ko.bindingHandlers.filereader = {
    init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called when the binding is first applied to an element
        // Set up any initial state, event handlers, etc. here
        var conf = {
            input   : $(element)[0],
            value   : valueAccessor(),
            ext     : allBindings.get('ext')||[],//extenções permitidas
            max     : allBindings.get('max')||1024*1024*5//tamanho máximo permitido 5MB
        }
        ko.utils.registerEventHandler(element, 'change',function()
        {
            if(conf.input.files && conf.input.files[0])
            {
                var f = conf.input.files[0];
                if(conf.ext.length && conf.ext.indexOf(f.name.split('.').pop()) == -1)
                {
                    confirmModal.alertar('O item selecionado não é do tipo esperado:'+conf.ext.toString());
                    return;
                }
                if(f.size > conf.max)
                {
                    confirmModal.alertar('O item selecionado é maior que o permitido:'+(conf.max/1024/1024)+' MB');
                    return;
                }
                var FR = new FileReader();
                FR.onload = function(e)
                {
                    conf.value({
                        name:f.name,
                        type:f.type,
                        size:f.size,
                        file:e.target.result
                    });
                }
                FR.readAsDataURL(conf.input.files[0]);
            }
        });
    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called once when the binding is first applied to an element,
        // and again whenever any observables/computeds that are accessed change
        // Update the DOM element based on the supplied values here.
    }
};
ko.validation.makeBindingHandlerValidatable('filereader');


ko.bindingHandlers.enterkey = {
    init: function (element, valueAccessor, allBindings, viewModel) {
        var callback = valueAccessor();
        $(element).keypress(function (event) {
            var keyCode = (event.which ? event.which : event.keyCode);
            if (keyCode === 13) {
                callback.call(viewModel);
            }
        });
    }
};
ko.validation.makeBindingHandlerValidatable('enterkey');

ko.validation.rules['url'] = {
    validator: function (value_on_input, params) {
        if(!value_on_input) return true;
        return (
            !!value_on_input &&
            (
                value_on_input.indexOf('http://') == 0 ||
                value_on_input.indexOf('https://') == 0 ||
                value_on_input.indexOf('www.') == 0
            ) &&
            (
                value_on_input.indexOf('.com') != -1 ||
                value_on_input.indexOf('.com.br') != -1 ||
                value_on_input.indexOf('.net') != -1
            )
        );
    },
    message: 'Valor informado não é um link válido'
};
ko.validation.registerExtenders();

ko.bindingHandlers.executeOnEnter = {
    init: function (element, valueAccessor, allBindings, viewModel) {
        var callback = valueAccessor();
        $(element).keypress(function (event) {
            var keyCode = (event.which ? event.which : event.keyCode);
            if (keyCode === 13) {
                callback.call(viewModel);
                return false;
            }
            return true;
        });
    }
};
ko.validation.makeBindingHandlerValidatable('executeOnEnter');

var summernoteText = "";
ko.bindingHandlers.summernote = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
        var allBindings = allBindingsAccessor();
        // initialize summernote with config from binding
        var toolbar = {
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'video']]
          ]
        };
        //$(element).summernote(toolbar);
        var summernoteConfig = ko.utils.unwrapObservable(allBindings.summernote);
        summernoteConfig = (typeof summernoteConfig === 'object') ? summernoteConfig : {}
        var options = $.extend(toolbar, summernoteConfig);
        $(element).summernote(options);

        $(element).summernote('code', allBindings.value());
        summernoteText = allBindings.value();

        // callback to update value observable
        $(element).on('summernote.change', function (we, contents, $editable) {
            summernoteText = contents;
            allBindings.value(contents);
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        var $element = $(element);
        var allBindings = allBindingsAccessor();
        var value = allBindings.value();
        if (summernoteText != value)
            $element.summernote('code', value);
    }
};
ko.validation.makeBindingHandlerValidatable('summernote');

/* Datepick Painel Admin */
ko.bindingHandlers.datePicker = {
    init: function(element, valueAccessor, allBindingsAccessor) {

        var options = allBindingsAccessor().datePickerOptions || {};
        var datePickerOptions = Object.assign({language: 'pt-BR', format: 'dd/mm/yyyy'},options);

        $(element).datepicker(datePickerOptions);

        ko.utils.registerEventHandler(element, 'focusout', function() {
            var observable = valueAccessor();
            observable($(element).val());
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {

        var options = allBindingsAccessor().datePickerOptions || {};
        var datePickerOptions = Object.assign({language: 'pt-BR', format: 'dd/mm/yyyy'},options);
        var value = ko.utils.unwrapObservable(valueAccessor());

        $(element).val(value);
        $(element).datepicker({language: 'pt-BR', format: 'dd/mm/yyyy'});
    }
};
ko.validation.makeBindingHandlerValidatable('datePicker');

ko.bindingHandlers.select2 = {
    init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
        var options = ko.unwrap(valueAccessor());
        options = options.data();
        ko.unwrap(allBindings.get('selectedOptions'));

        $(element).select2(options);
        console.log('init');
    },
    update: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
        var options = ko.unwrap(valueAccessor());
        options = options.data();
        ko.unwrap(allBindings.get('selectedOptions'));

        $(element).select2(options);
        console.log('update');
    }
};
ko.validation.makeBindingHandlerValidatable('select2');
