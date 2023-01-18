<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('shopper');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('shopper.notifications', compact('notifications'));
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
