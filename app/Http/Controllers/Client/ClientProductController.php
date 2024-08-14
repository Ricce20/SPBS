<?php

namespace App\Http\Controllers\client;
use App\Http\Controllers\controller;
use App\Models\Product;
use App\Models\order;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class ClientProductController extends Controller

{
   public function index(){
    
        $response = Http::get('http://127.0.0.1:8002/api/cat');
        //dd($response);
        if($response->successful()){
            $ProductsJson = json_decode($response->body());
            $products = $ProductsJson->products;
            //dd($orders);
            return view('/client_public.cat')->with('products', $products);

        }else{
            session()->flash('error','Error en la peticion');
            return redirect()->back();
        }
      
    
    // return view('/client_public.cat')->with('products', Product::all());
    }

   public function show($id){
    $response = Http::get('http://127.0.0.1:8002/api/users/edit/'.$id);
            if($response->successful()){
                $UserJson = json_decode($response->body());
                $user = $UserJson->user;
                //dd($orders);
                return view('users.show', compact('user'));
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
    //return view('/client_public.detail')->with('product', Product::find($id));
    }

    public function view(){
        if(Session::get('user')->type=='ADMIN'){
            $response = Http::get('http://127.0.0.1:8002/api/users');
            if($response->successful()){
                $UsersJson = json_decode($response->body());
                $users = $UsersJson->users;
                //dd($orders);
                return view('users.index')->with('Users', $users);
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
        }else {
            return redirect()->route('login');
        }   
    }
    
        public function showOrders($userId)
        {
            $user = User::find($userId);
    
            if ($user) {
                $orders = Order::where('user_id', $user->id)->get();
    
                return view('users.orders', compact('user', 'orders'));
            } else {
                // Manejo si el usuario no es encontrado
                // Puedes redirigir a una página de error o hacer algo más
            }
        }


        public function edit($userId)
        {
            $response = Http::get('http://127.0.0.1:8002/api/users/edit/'.$userId);
            if($response->successful()){
                $UserJson = json_decode($response->body());
                $user = $UserJson->user;
                //dd($orders);
                return view('users.edit', compact('user'));
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }

            // $user = User::find($userId);
            // $user->save();
            // if ($user) {
            //     return view('users.edit', compact('user'));
            // } else {
            //     abort(404);
            // }
        }


        public function update(Request $request, $userId)
        {
           // dd($request, $userId);
            $response = Http::when($request->hasFile('image'), function ($http) use ($request) {
                return $http->attach(
                    'image', file_get_contents($request->image), $request->image->getClientOriginalName()
                );
            })->post('http://127.0.0.1:8002/api/users/update/'.$userId, [
                'name'=>$request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'type' => $request->type,
                'status' => $request->status,
                'email' => $request->email,
                '_method' => 'PUT'

                // 'password' => $request->password,
                // 'password_confirmation' => $request->password_confirmation
            ]);
          // dd($response);
    
            if ($response->successful()) {
                return redirect()->back();
            }
            else{
                return redirect()->back();
            }

            //$user = User::find($userId);

            // if ($user) {
            //     $user->update($request->all());
            //     return redirect()->back();
            //    // return redirect()->route('user.edit', ['userId' => $user->id])->with('success', 'Usuario actualizado exitosamente.');
            // } else {
            //     abort(404);
            // }
        }

        public function delete($id){
            $response = Http::delete('http://127.0.0.1:8002/api/users/delete/'.$id);
            if($response->successful()){
                return redirect('/users');
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
        }

}

