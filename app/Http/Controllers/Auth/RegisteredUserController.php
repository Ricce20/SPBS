<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }
    public function createClient(): View
    {
        return view('auth.register-client');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            // 'image' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // $user=new User();
        // $user->name=$request->name;
        // $user->last_name=$request->last_name;
        // $user->phone=$request->phone;
        // $user->address=$request->address; 
        // // $user->image=$request->image;
        
        // $user->image='default.png'; 

        // $user->type=$request->type;
        // $user->status='ACTIVO';
        // $user->email=$request->email;
        // $user->password=Hash::make($request->password);
        // $user->save();

        // if ($request->hasFile('image')) {

        //     $extension = $request->image->extension();
        //     $new_name = 'user_' . $user->id . '_1.' . $extension;
        //     $path = $request->image->storeAs('/images/users', $new_name, 'public');
        //     $user->image= $path;
        //     $user->save();
        // }

        $response = Http::attach(
            'image', file_get_contents($request->image), $request->image->getClientOriginalName()
        )->post('http://127.0.0.1:8002/api/register', [
            'name'=>$request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'type' => $request->type,
            'status' => $request->status,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation
        ]);
      // dd($response);

        if ($response->successful()) {
            $body = json_decode($response->body());
           //dd($body);
           if(Session::get('api_key')){
            return redirect('/users');
           }
            \Session::put('api_key', $body->token);
            \Session::put('user', $body->user);
            //dd(Session::get('user'));

            if(Session::get('user')->type=='CLIENTE'){
                return redirect(RouteServiceProvider::HOME);;
            }
            if(Session::get('user')->type=='ADMIN'){
                return redirect(RouteServiceProvider::ADMIN);

            }else{
                return redirect()->route('login');
            }
           
        } else {
            session()->flash('error','Credenciales invalidas');
            return redirect()->intended(RouteServiceProvider::HOME);
          
        }

      //  event(new Registered($user));

      //  Auth::login($user);

       // return redirect(RouteServiceProvider::HOME);
    }
}
