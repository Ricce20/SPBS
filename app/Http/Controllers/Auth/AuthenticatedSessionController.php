<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $responselogin = Http::post('http://127.0.0.1:8002/api/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);
       // dd($responselogin);

        if ($responselogin->successful()) {
            $body = json_decode($responselogin->body());
           // dd($body);
            \Session::put('api_key', $body->token);
            \Session::put('user', $body->user);
            //dd(Session::get('user'));
            if(Session::get('user')->type=='CLIENTE'){
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            if(Session::get('user')->type=='ADMIN'){
                return redirect()->intended(RouteServiceProvider::ADMIN);

            }else{
                return redirect()->route('login');
            }
           
        } else {
            session()->flash('error','Credenciales invalidas');
            return redirect()->intended(RouteServiceProvider::HOME);
          
        }
        // $user = Auth::user();

        // // $request->session()->regenerate();

        // if($user->type=='CLIENTE'){
        //     return redirect()->intended(RouteServiceProvider::HOME);
        // }

        // if($user->type=='ADMIN'){
        //     return redirect()->intended(RouteServiceProvider::ADMIN);
        // }
        
        // return redirect()->intended(RouteServiceProvider::HOME);
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $response = Http::withToken(Session::get('api_key'))->post('http://127.0.0.1:8002/api/logout');
        // dd();
        if($response->successful()){
            Session::flush();
            session()->flash('success', 'Vuelve pronto');
            return redirect()->intended(RouteServiceProvider::HOME);

        }else{
           // dd($response);
          // Session::flush();
            session()->flash('error','Error al cerrar session');
            return redirect('/login');
        }
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();
        // session()->forget('api_key');
        // session()->forget('user');

        //return redirect('/');
    }
}
