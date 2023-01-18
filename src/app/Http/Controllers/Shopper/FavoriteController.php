<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
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
        return view('shopper.favorites');
    }

    public function store(Request $request)
    {
        return $this->_callService(
            FavoriteService::class,
            'addFavorite',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(FavoriteService::class, 'destroy', $request);
    }
}
