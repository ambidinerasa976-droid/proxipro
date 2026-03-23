<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/feed';

    /**
     * Determine the redirect path after login.
     * All users go to feed. Professionals see onboarding modal on the feed page if needed.
     */
    protected function redirectTo()
    {
        return '/feed';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Validate the user login request (with reCAPTCHA).
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        // Add reCAPTCHA validation if configured
        if (config('services.recaptcha.secret_key')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha('login')];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => 'La vérification de sécurité est requise.',
        ]);
    }
}
