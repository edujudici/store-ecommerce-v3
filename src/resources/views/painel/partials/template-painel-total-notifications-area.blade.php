<painel-total-notifications-area></painel-total-notifications-area>
<template id="template-painel-total-notifications-area">
    <!--================Total Notifications Area =================-->
    <div class="card card-default" data-scroll-height="550">
        <div class="card-header justify-content-between border-bottom">
            <h2>Total de Notificações</h2>
            {{--  <div>
                <button class="text-black-50 mr-2 font-size-20">
                    <i class="mdi mdi-cached"></i>
                </button>
            </div>  --}}
        </div>
        <div class="card-body slim-scroll">
            <!-- ko if: notifications().length == 0 -->
                <p>Nenhuma notifação no momento.</p>
            <!-- /ko -->

            <!-- ko foreach: notifications -->
                <div class="media py-3 align-items-center justify-content-between">
                    <div data-bind="class: color"
                        class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 text-white">
                        <i class="mdi font-size-20" data-bind="class: icon"></i>
                    </div>
                    <div class="media-body">
                        <a class="mt-0 mb-1 font-size-15 text-dark" href="#" data-bind="text: title"></a>
                        <p><span data-bind="text: total"></span> <span data-bind="text: description"></span></p>
                    </div>
                </div>
            <!-- /ko -->
        </div>
        <div class="mt-3"></div>
    </div>
	<!--================End Total Notifications Area =================-->
</template>

<script type="text/javascript">

    function totalNotifications(){[native/code]}
    totalNotifications.list = {!! $notifications !!};

    totalNotifications.Notification = function(obj) {
        let self = this;

        self.id = obj.id;
        self.order = obj.data.order;
        self.message = obj.data.message;
        self.date = obj.created_at;
        self.originalType = obj.type;
        self.type = ko.observable();
        self.icon = ko.observable();
        self.color = ko.observable();
        self.title = ko.observable();
        self.description = ko.observable();
        self.total = ko.observable(1);

        const type = obj.type.replace('App\\Notifications\\', '');

        self.type(type);

        switch(type) {
            case 'ContactNotification':
                self.icon('mdi-email');
                self.color('bg-success');
                self.title('Contatos');
                self.description('novos contatos');
                break;
            case 'NewsletterNotification':
                self.icon('mdi-email-outline');
                self.color('bg-warning');
                self.title('Newsletter');
                self.description('novas newsletters');
                break;
            case 'OrderNotification':
                self.icon('mdi-cart-outline');
                self.color('bg-primary');
                self.title('Pedidos');
                self.description('novos pedidos efetuados');
                break;
            case 'ProductCommentNotification':
                self.icon('mdi-comment-text-multiple-outline');
                self.color('bg-info');
                self.title('Comentários de Produto');
                self.description('comentários de produto');
                break;
            case 'OrderCommentNotification':
                self.icon('mdi-comment-text-multiple');
                self.color('bg-dark');
                self.title('Comentários de Pedido');
                self.description('comentários de pedido');
                break;
            case 'UserNotification':
                self.icon('mdi-account-group');
                self.color('bg-danger');
                self.title('Cliente');
                self.description('novos clientes cadastrado');
                break;
            case 'OrderPaidNotification':
                self.icon('mdi-cash-multiple');
                self.color('bg-secondary');
                self.title('Pagamento aprovado');
                self.description('novos pagamentos aprovados');
                break;
        }
    }

    totalNotifications.notificationsViewModel = function(params) {
        var self = this;

        self.notifications = ko.observableArray();

        ko.utils.arrayForEach(totalNotifications.list, function(item) {
            const notifyExist = ko.utils.arrayFirst(self.notifications(), function(notification) {
                const type = item.type.replace('App\\Notifications\\', '');
                return notification.type() === type
            });
            if (notifyExist) {
                notifyExist.total(notifyExist.total() + 1);
                return;
            }

            self.notifications.push(new totalNotifications.Notification(item));
        });


        self.mySortedArray = ko.computed(function () {
            return self.notifications.sort(function (left, right) {
                return left.total() === right.total() ? 0
                     : left.total() > right.total() ? -1
                     : 1;
            });
        });
    }

	ko.components.register('painel-total-notifications-area', {
	    template: { element: 'template-painel-total-notifications-area'},
	    viewModel: totalNotifications.notificationsViewModel
	});
</script>
