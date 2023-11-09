<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\User\UserService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    private const URI_CHECKOUT = '/checkout';
    private const URI_SHOPPER = '/shopper';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo;
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect(self::URI_SHOPPER);
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        if (session('redirect_checkout')) {
            $this->redirectTo = self::URI_CHECKOUT;
        } else {
            $this->redirectTo = self::URI_SHOPPER;
        }
        return $this->redirectTo;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $userSocialite = Socialite::driver('google')->user();
            $user = $this->_callService(
                UserService::class,
                'storeBySocialite',
                $userSocialite
            );
            Auth::login($user['response']);
            $route = $this->redirectTo() === self::URI_CHECKOUT
                ? 'site.checkout.index'
                : 'shopper.index';

            session(['login_socialite' => true]);

            return redirect()->route($route);
        } catch (Exception $e) {
            return redirect()->route('shopper.google.login');
        }
    }
}
