<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('painel.home', compact('notifications'));
    }

    public function read(Request $req)
    {
        auth()->user()
            ->unreadNotifications
            ->when($req->input('id'), static function ($query) use ($req) {
                return $query->where('id', $req->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }
}
