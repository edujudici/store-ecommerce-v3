<painel-notifications-area></painel-notifications-area>
<template id="template-painel-notifications-area">
    <!--================Notifications Area =================-->
    <div class="card card-default" data-scroll-height="550">
        <div class="card-header justify-content-between border-bottom">
            <h2>Todas as notificações</h2>
            <!-- ko if: notifications().length > 0 -->
            <a href="#" data-bind="click: markAll">
                Marcar todos como lida
            </a>
            <!-- /ko -->
        </div>
        <div class="card-body slim-scroll">
            <!-- ko if: notifications().length == 0 -->
            <p>Não existe nenhuma notifação no momento.</p>
            <!-- /ko -->

            <!-- ko foreach: notifications -->
            <div role="alert" class="mb-3">
                <span data-bind="text: base.monthStringEn(date)"></span> - <span data-bind="text: message"></span>
                <a href="#" class="float-right" data-bind="click: markAsRead">Marcar como lida</a>
            </div>
            <!-- /ko -->
        </div>
        <div class="mt-3"></div>
    </div>
    <!--================End Notifications Area =================-->
</template>

<script type="text/javascript">
    function notifications(){[native/code]}
    notifications.list = {!! $notifications !!};
    notifications.urlNotificationsMark = "{{ route('painel.notifications.read') }}";

    notifications.Notification = function(obj, vm) {
        let self = this;

        self.id = obj.id;
        self.order = obj.data.order;
        self.message = obj.data.message;
        self.date = obj.created_at;
        self.vm = vm;

        self.markAsRead = function(item) {
            let params = {
                'id': self.id,
            },
            callback = function(data) {
                if(data) {
                    Alert.error(data.message);
                    return;
                }
                Alert.info('Notificação lida com sucesso.');
                self.vm.notifications.remove(item);
            };
            base.post(notifications.urlNotificationsMark, params, callback);
        }
    }

    notifications.notificationsViewModel = function(params) {
        var self = this;

        self.notifications = ko.observableArray(ko.utils.arrayMap(notifications.list, function(item) {
            return new notifications.Notification(item, self);
        }));

        self.markAll = function() {
            let callback = function(data) {
                if(data) {
                    Alert.error(data.message);
                    return;
                }
                Alert.info('Notificações lidas com sucesso.');
                self.notifications([]);
            };
            base.post(notifications.urlNotificationsMark, null, callback);
        }
    }

	ko.components.register('painel-notifications-area', {
	    template: { element: 'template-painel-notifications-area'},
	    viewModel: notifications.notificationsViewModel
	});
</script>
