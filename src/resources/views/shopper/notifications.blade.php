@extends('shopper.baseTemplate')

@section('shopperContent')

<notification-area></notification-area>
<template id="template-notification-area">
    <div class="login_form_inner" style="padding: 20px 0;">
        <h2 class="text-center mb-4">Minhas notificações não lidas</h2>

        <!-- ko if: notifications().length == 0 -->
        <p>Nenhuma notificação encontrada</p>
        <!-- /ko -->

        <!-- ko if: notifications().length > 0 -->
        <div class="col-md-12 text-right">
            <a class="primary-btn mt-3" data-bind="click: markAll">Marcar todos como lida</a>
        </div>
        <!-- /ko -->

        <div class="order-area" data-bind="foreach: notifications">
            <div class="order-item">
                <div class="order-item-header text-right" style="display: inherit">
                    <span>Recebido em <strong class="acc-delivery-prevision-days delivered" data-bind="text: base.monthStringEn(date)"></strong></span>
                </div>
                <div class="order-item-body">
                    <div class="order-item-product">
                        <div class="order-item-product-info ml-0">
                            <span class="ti-alert pr-1" style="font-size: xx-large;"></span>
                            <span data-bind="text: message"></span><a class="ml-2" data-bind="attr: {href: notification.urlOrders + order}">Clique para visualizar</a>
                        </div>
                    </div>
                    <a class="primary-btn mt-3" data-bind="click: markAsRead">Marcar como lida</a>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('shopperScript')
    <script type="text/javascript">

        function notification(){[native/code]}
        notification.list = {!! $notifications !!};
        notification.urlNotificationsMark = "{{ route('shopper.notifications.read') }}";
        notification.urlOrders = "{{ route('shopper.orders.index') }}/";

        notification.Notification = function(obj, vm) {
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
                    head.viewModel.totalNotifications(head.viewModel.totalNotifications() - 1);
                };
                base.post(notification.urlNotificationsMark, params, callback);
            }
        }

        notification.NotificationAreaViewModel = function(params) {
            var self = this;

            self.notifications = ko.observableArray(ko.utils.arrayMap(notification.list, function(item) {
                return new notification.Notification(item, self);
            }));

            self.markAll = function() {
                let callback = function(data) {
                    if(data) {
                        Alert.error(data.message);
                        return;
                    }
                    Alert.info('Notificações lidas com sucesso.');
                    self.notifications([]);
                    head.viewModel.totalNotifications(0);
                };
                base.post(notification.urlNotificationsMark, null, callback);
            }
        }

        ko.components.register('notification-area', {
            template: { element: 'template-notification-area'},
            viewModel: notification.NotificationAreaViewModel
        });
    </script>
@endsection
