<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Models\Client;
use App\Models\Supplier;

use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('/client_public.inicio');
    
});

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
 
//     return redirect('/');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
 
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();
    // dd($user_google);
    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
    ]);
    //dd($user);
    $responselogin = Http::post('http://127.0.0.1:8002/api/login-google', [
        'email' => $user->email,
    ]);
   // dd($responselogin);
    if ($responselogin->successful()) {
        
        $body = json_decode($responselogin->body());
        // dd($body);
         \Session::put('api_key', $body->token);
         \Session::put('user', $body->user);
         return redirect()->intended(RouteServiceProvider::HOME);

    }
   // Auth::login($user);
 
    //return redirect('/');
 
    // $user->token
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//ADMIN
// Route::view('/admin','/admin')->name('admin');
Route::get('/admin',function(){
    if(Session::get('api_key')){
        return view('/admin');
    }
    else{
        return redirect('/');
    }
})->name('admin');
    
    //PRODUCTS
            Route::get('/products',[ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');

            Route::post('/products/store',[ProductController::class, 'store'])->name('products.store'); 

            Route::get('/products/edit/{id}',[ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/update/{id}',[ProductController::class, 'update'])->name('products.update');

            Route::get('/products/show/{id}',[ProductController::class, 'show'])->name('products.show');
            Route::delete('/products/delete/{id}',[ProductController::class, 'delete'])->name('products.delete');

    //CLIENTS
            Route::get('/clients',[ClientController::class, 'index'])->name('clients.index');
            Route::get('/clients/create',[ClientController::class, 'create'])->name('clients.create');

            Route::post('/clients/store',[ClientController::class, 'store'])->name('clients.store');

            Route::get('/clients/edit/{id}',[ClientController::class, 'edit'])->name('clients.edit');
            Route::put('/clients/update/{id}',[ClientController::class, 'update'])->name('clients.update');

            Route::get('/clients/show/{id}',[ClientController::class, 'show'])->name('clients.show');
            Route::delete('/clients/delete/{id}',[ClientController::class, 'delete'])->name('clients.delete');

    //SUPPLIERS
            Route::get('/suppliers',[SupplierController::class,'index'])->name('suppliers.index');
            Route::get('/suppliers/create',[SupplierController::class,'create'])->name('suppliers.create');

            Route::post('/suppliers/store',[SupplierController::class, 'store'])->name('suppliers.store');

            Route::get('/suppliers/edit/{id}',[SupplierController::class, 'edit'])->name('suppliers.edit');
            Route::put('/suppliers/update/{id}',[SupplierController::class, 'update'])->name('suppliers.update');

            Route::get('/suppliers/show/{id}',[SupplierController::class, 'show'])->name('suppliers.show');
            Route::delete('/suppliers/delete/{id}',[SupplierController::class, 'delete'])->name('suppliers.delete');

    //users
    // Route::get('/users',[ClientProductController::class,'view'])->name('users.index');
    // // Route::get('/users/create',[ClientProductController::class,'create'])->name('users.create');

    // Route::post('/users/store',[ClientProductController::class, 'store'])->name('users.store');

    // Route::get('/users/edit/{id}',[ClientProductController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}',[ClientProductController::class, 'update'])->name('users.update');

            // //users
        // Route::view('/users','users/index')->name('users.index');
        // Route::view('/users/create','/users/create')->name('users.create');

    Route::get('/users/show/{id}',[ClientProductController::class, 'show'])->name('users.show');
    Route::delete('/users/delete/{id}',[ClientProductController::class, 'delete'])->name('users.delete');




        Route::get('users', [ClientProductController::class, 'view'])->name('client.index');
        
    Route::get('/admin/user/{userId}',[ClientProductController::class, 'showOrders'])->name('user.orders');// pedidos
    Route::get('/users/edit/{userId}',[ClientProductController::class, 'edit'])->name('user.edit');// editar weones
    Route::put('/users/{userId}',[ClientProductController::class, 'update'])->name('user.update');// editar weones

        //PEDIDOS
        Route::get('/orders',[OrderController::class,'index'])->name('orders.index');//clientes
         
        Route::post('/enviarOrden/{id}', [OrderController::class, 'enviarOrden'])->name('enviarOrden');

        Route::get('/admin/orders',[OrderController::class,'admin'])->name('admin.orders');//admin

         Route::get('/orders/show/{id}',[OrderController::class,'prod'])->name('orders.show');
        Route::post('/update-order', [OrderController::class, 'updateOrder'])->name('update.order');//estatus actualizar


        // //orders
        // Route::view('/orders','orders/index')->name('orders.index');
        // Route::view('/orders/create','/orders/create')->name('orders.create');

        // Route::view('/order_details','order_details/index')->name('order_details.index');
        // Route::view('/order_details/create','/order_details/create')->name('order_details.create');

        //PAYPAL
        Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
        Route::get('success', [PaymentController::class, 'success']);
        Route::get('error', [PaymentController::class, 'error']);

    // });

    require __DIR__.'/auth.php';

    //CART
    // Route::get('products', [ProductController::class, 'productList'])->name('products.list');
    Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
    Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
    Route::post('buy', [CartController::class, 'buy'])->name('cart.buy');


    //PUBLIC

    Route::view('/inicio','/client_public.inicio')->name('inicio');

    Route::get('/cat',[ClientProductController::class, 'index'])->name('cat');
    // Route::view('/cat','/client_public.cat')->name('cat');
    // Route::view('/detail','/client_public.detail')->name('detail');
    Route::get('/cat/detail/{id}',[ClientProductController::class, 'show'])->name('cat.detail');
    return view('welcome');


    
