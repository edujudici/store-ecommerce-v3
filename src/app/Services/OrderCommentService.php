<?php

namespace App\Services;

use App\Events\OrderCommentAnswerRegistered;
use App\Events\OrderCommentRegistered;
use App\Models\Order;
use App\Models\OrderComment;
use Illuminate\Database\Eloquent\Collection;

class OrderCommentService extends BaseService
{
    private $comment;
    private $order;

    public function __construct(
        Order $order,
        OrderComment $comment
    ) {
        $this->order = $order;
        $this->comment = $comment;
    }

    public function indexAll(): Collection
    {
        return $this->comment
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->where('orc_name', '!=', 'Mensagem automática')
            ->get();
    }

    public function index($request): array
    {
        $order = $this->order
            ->where('ord_protocol', $request->input('id'))
            ->firstOrFail();
        return $order->comments()
            ->get()
            ->toArray();
    }

    public function store($request): array
    {
        $order = $this->order
            ->where('ord_protocol', $request->input('id'))
            ->firstOrFail();

        $params = $request->all();
        uploadImage($request, $params, 'orc_image');
        $order->comments()->updateOrCreate([
            'orc_id' => $request->input('orc_id'),
        ], $params);

        $this->sendNotification($request, $order);

        return $this->index($request);
    }

    public function destroy($request)
    {
        $comment = $this->comment->findOrFail($request->input('id'));
        return $comment->delete();
    }

    public function storeMessageOrder($order)
    {
        $message = trans(
            'autoMessage.new_order',
            ['name' => $order->user->name]
        );
        $order->comments()->create([
            'orc_name' => 'Mensagem automática',
            'orc_answer' => $message,
            'orc_answer_date' => date('Y-m-d H:i:s'),
        ]);
    }

    public function storeMessageWelcome($order)
    {
        $message = trans('autoMessage.welcome');
        $order->comments()->create([
            'orc_name' => 'Mensagem automática',
            'orc_answer' => $message,
            'orc_answer_date' => date('Y-m-d H:i:s'),
        ]);
    }

    public function storeMessageFinished($order)
    {
        $message = trans('autoMessage.end_order');
        $order->comments()->create([
            'orc_name' => 'Mensagem automática',
            'orc_answer' => $message,
            'orc_answer_date' => date('Y-m-d H:i:s'),
        ]);
    }

    private function sendNotification($request, $order)
    {
        if ($request->has('orc_answer')) {
            event(new OrderCommentAnswerRegistered($order));
            return;
        }
        event(new OrderCommentRegistered($order));
    }
}
