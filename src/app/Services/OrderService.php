<?php

namespace App\Services;

use App\Events\OrderPaidRegistered;
use App\Events\OrderRegistered;
use App\Events\OrderStatusRegistered;
use App\Models\Order;
use App\Traits\OrderTransformable;

class OrderService extends BaseService
{
    use OrderTransformable;

    private $order;
    private $orderItemsService;
    private $orderHistoryService;
    private $orderAddressService;
    private $orderCommentService;

    public function __construct(
        Order $order,
        OrderItemsService $orderItemsService,
        OrderHistoryService $orderHistoryService,
        OrderAddressService $orderAddressService,
        OrderCommentService $orderCommentService
    ) {
        $this->order = $order;
        $this->orderItemsService = $orderItemsService;
        $this->orderHistoryService = $orderHistoryService;
        $this->orderAddressService = $orderAddressService;
        $this->orderCommentService = $orderCommentService;
    }

    public function getOrderByPreference($prefenceId): Order
    {
        return $this->order
            ->where('ord_preference_id', $prefenceId)
            ->firstOrFail();
    }

    public function index($request): array
    {
        $orders = $this->order->selectRaw('*, orders.created_at as order_date')
            ->with('address')->with('histories')->with('items.product')
            ->with([
                'payments' => function ($query) {
                    $query->selectRaw('ord_id, orp_payment_type_id, orp_status,
                    orp_resource_url, orp_date_of_expiration');
                },
            ]);
        if ($request->has('uuid')) {
            $orders->join('users', 'users.id', 'orders.user_id')
                ->where('users.uuid', $request->input('uuid'));
        }
        $orders = $orders->orderBy('ord_id', 'desc')
            ->paginate($request->input('amount', 12))
            ->onEachSide(1);
        return [
            'status' => $this->order::getStatus(),
            'allStatus' => $this->order::getAllStatus(),
            'orders' => isset($orders->toArray()['data'])
                ? $orders->toArray()['data']
                : [],
            'pagination' => (string) $orders->setPath('')->links(),
        ];
    }

    public function create($preference, $cart, $address, $paid = false): Order
    {
        $order = $this->store(
            null,
            $this->prepareOrder($preference, $cart, $paid)
        );
        $this->orderItemsService->store($order, $cart);
        $this->orderAddressService->store($order, $address);
        return $order;
    }

    public function store($request, $params = null): Order
    {
        $params = $params ? $params : $request->all();
        $order = $this->order->updateOrCreate([
            'ord_preference_id' => $params['preferenceId'],
        ], $params);
        $this->orderHistoryService->store($order, [
            'orh_preference_id' => $params['preferenceId'],
            'orh_collection_status' => $params['status'],
        ]);
        $this->registerEvents($params, $order);
        return $order;
    }

    private function registerEvents($params, $order): void
    {
        event(new OrderStatusRegistered(
            $order,
            $order::getStatusDescription($params['status'])
        ));

        switch ($params['status']) {
            case $this->order::STATUS_NEW:
                event(new OrderRegistered($order));
                $this->orderCommentService->storeMessageOrder($order);
                break;
            case $this->order::STATUS_PAID:
                event(new OrderPaidRegistered($order));
                $this->orderCommentService->storeMessageWelcome($order);
                if (isset($params['approvedDate'])) {
                    $days = $order->ord_freight_time;
                    $order->ord_promised_date_recalculated = date(
                        'Y-m-d',
                        strtotime($params['approvedDate'] . "+{$days} days")
                    );
                    $order->save();
                }
                break;
            case $this->order::STATUS_COMPLETE:
                $this->orderCommentService->storeMessageFinished($order);
                $order->ord_delivery_date = date('Y-m-d H:i:s');
                $order->save();
                break;
        }
    }
}
