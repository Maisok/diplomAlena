<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|digits:5',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ]);

         // Проверка reCAPTCHA
         $client = new Client([
            'verify' => false, // Disable SSL verification
        ]);
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6Lc9BYEqAAAAAHy807C2BpwH7O-nqlIEEnDOyx0I',
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ],
        ]);

        $body = json_decode((string) $response->getBody());

        if (!$body->success) {
<<<<<<< HEAD
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA: проверка не пройдена.']);
=======
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed.']);
>>>>>>> ab11bb343f655af4f50408e4d70a2344b89856cd
        }


        if (Auth::attempt(['login' => $credentials['login'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors([
<<<<<<< HEAD
            'login' => 'Неверный логин или пароль.',
=======
            'login' => 'The provided credentials do not match our records.',
>>>>>>> ab11bb343f655af4f50408e4d70a2344b89856cd
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}