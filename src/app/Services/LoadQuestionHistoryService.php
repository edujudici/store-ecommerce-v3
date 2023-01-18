<?php

namespace App\Services;

use App\Models\LoadQuestionHistory;
use Illuminate\Database\Eloquent\Collection;

class LoadQuestionHistoryService extends BaseService
{
    private $loadQuestionHistory;

    public function __construct(LoadQuestionHistory $loadQuestionHistory)
    {
        $this->loadQuestionHistory = $loadQuestionHistory;
    }

    public function index(): Collection
    {
        return $this->loadQuestionHistory
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function store(
        $loadDate,
        $total,
        $totalSync,
        $accountId,
        $accountTitle
    ): LoadQuestionHistory {
        return $this->loadQuestionHistory->create([
            'lqh_total' => $total,
            'lqh_total_sync' => $totalSync,
            'lqh_account_id' => $accountId,
            'lqh_account_title' => $accountTitle,
            'created_at' => $loadDate,
        ]);
    }

    public function update($historyId, $totalSync): void
    {
        if ($totalSync > 0) {
            $this->loadQuestionHistory
                ->where('lqh_id', $historyId)
                ->update(['lqh_total_sync' => $totalSync]);
        }
    }
}
