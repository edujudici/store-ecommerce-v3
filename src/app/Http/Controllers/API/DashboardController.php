<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Painel\DashboardNewShoppersService;
use App\Services\Painel\DashboardOrdersOverviewService;
use App\Services\Painel\DashboardRecentOrdersService;
use App\Services\Painel\DashboardRevenueService;
use App\Services\Painel\DashboardShoppersService;
use App\Services\Painel\DashboardTopProductsService;

class DashboardController extends Controller
{
    public function totalShoppers()
    {
        return $this->_callService(DashboardShoppersService::class, 'index', []);
    }

    public function totalRevenue()
    {
        return $this->_callService(
            DashboardRevenueService::class,
            'indexRevenue',
            []
        );
    }

    public function salesYear()
    {
        return $this->_callService(
            DashboardRevenueService::class,
            'indexSalesYear',
            []
        );
    }

    public function ordersOverview()
    {
        return $this->_callService(
            DashboardOrdersOverviewService::class,
            'index',
            []
        );
    }

    public function recentOrders()
    {
        return $this->_callService(
            DashboardRecentOrdersService::class,
            'index',
            []
        );
    }

    public function newShoppers()
    {
        return $this->_callService(
            DashboardNewShoppersService::class,
            'index',
            []
        );
    }

    public function topProducts()
    {
        return $this->_callService(
            DashboardTopProductsService::class,
            'index',
            []
        );
    }
}
