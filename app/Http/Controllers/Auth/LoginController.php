<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/cabinet';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->middleware('guest')->except('logout');
        $this->setTitle('Вход на сайт');
        $this->setDescription('Вход на сайт');
        $this->addBread(route('front'), 'Главная', 'Главная');
        $this->addBread('','Вход на сайт','Вход на сайт');
    }
}
