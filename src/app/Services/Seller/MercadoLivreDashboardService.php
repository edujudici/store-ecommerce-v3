<?php

namespace App\Services\Seller;

use App\Models\MercadoLivre;
use App\Services\BaseService;
use App\Services\Seller\MercadoLivreService;

class MercadoLivreDashboardService extends BaseService
{
    private $mlService;

    public function __construct(MercadoLivreService $mlService)
    {
        $this->mlService = $mlService;
    }

    public function index($request): array
    {
        $mercadoLivre = $this->mlService->findById($request);
        return array_merge(
            $this->handleDayData($mercadoLivre),
            $this->handleYesterdayData($mercadoLivre),
            $this->handleGeneralData($mercadoLivre)
        );
    }

    private function handleDayData(MercadoLivre $mercadoLivre): array
    {
        $from = date('Y-m-d 00:00:01');
        $to = date('Y-m-d 23:59:59');
        $loadDay = $mercadoLivre->histories
            ->whereBetween('created_at', [$from, $to])
            ->sum('lqh_total_sync');
        $answerDay = $mercadoLivre->comments
            ->where('mec_answer_local', '=', 1)
            ->whereBetween('created_at', [$from, $to])
            ->count();
        return [
            'loadDay' => $loadDay,
            'answerDay' => $answerDay,
        ];
    }

    private function handleYesterdayData(MercadoLivre $mercadoLivre): array
    {
        $from = date('Y-m-d 00:00:01', strtotime('-1 days'));
        $to = date('Y-m-d 23:59:59', strtotime('-1 days'));
        $loadYesterday = $mercadoLivre->histories
            ->whereBetween('created_at', [$from, $to])
            ->sum('lqh_total_sync');
        $answerYesterday = $mercadoLivre->comments
            ->where('mec_answer_local', '=', 1)
            ->whereBetween('created_at', [$from, $to])
            ->count();
        return [
            'loadYesterday' => $loadYesterday,
            'answerYesterday' => $answerYesterday,
        ];
    }

    private function handleGeneralData(MercadoLivre $mercadoLivre): array
    {
        $totalLoad = $mercadoLivre->histories->sum('lqh_total_sync');
        $totalAnswered = $mercadoLivre->comments
            ->whereNotNull('mec_answer_local')
            ->where('mec_answer_local', '=', 1)
            ->count();
        $totalAnsweredPartner = $mercadoLivre->comments
            ->whereNotNull('mec_answer_local')
            ->where('mec_answer_local', '=', 0)
            ->count();
        return [
            'totalLoad' => $totalLoad,
            'totalAnswered' => $totalAnswered,
            'totalAnsweredPartner' => $totalAnsweredPartner,
        ];
    }
}
