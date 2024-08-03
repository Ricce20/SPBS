<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
   public function index(){
    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/products');
        if($response->successful()){
            $productsJson = json_decode($response->body());
            $products = $productsJson->products;
            //dd($orders);
            return view('/products/index')->with('products', $products);

        }else{
            session()->flas('error','Error en la peticion');
            return redirect()->back();
        }
      
    }else{
        return redirect('/');
    }

    //return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }

   public function create(){
    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/suppliers');
        if($response->successful()){
            $suppliersJson = json_decode($response->body());
            $suppliers = $suppliersJson->suppliers;
            //dd($orders);
            return view('/products/create')->with('suppliers', $suppliers);

        }else{
            session()->flas('error','Error en la peticion');
            return redirect()->back();
        }
      
    }else{
        return redirect('/');
    }
    //return view('/products/create')->with('suppliers', Supplier::where('status', 'Activo')->get());
   }

   public function store(Request $request){
    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->attach(
            'image1', file_get_contents($request->image1), $request->image1->getClientOriginalName()
        )->attach(
            'image2', file_get_contents($request->image2), $request->image2->getClientOriginalName()
        )->attach(
            'image3', file_get_contents($request->image3), $request->image3->getClientOriginalName()
        )->post('http://127.0.0.1:8002/api/products/store',[
            'supplier_id' => $request->supplier_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'existence' => $request->existence,
            'capability' => $request->capability,
            'capability_type' => $request->capability_type,
            'color' => $request->color,
            'type'=> $request->type,
            'status' => $request->status
        ]);

        if($response->successful()){
            $productJson = json_decode($response->body());
            $product = $productJson->success;
            session()->flash('message' ,$product);
            //dd($orders);
            return redirect()->route('products.index');

        }else{
            session()->flash('error','Error en la peticion');
            return redirect()->back();
        }
      
    }else{
        return redirect('/');
    }
    // return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }

    public function edit($id){

        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/products/show/'.$id);
            $responseSup = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/suppliers');

            if($response->successful() && $responseSup->successful()){
                $proudctJson = json_decode($response->body());
                $product = $proudctJson->product;
                $suppliersJson = json_decode($responseSup->body());
                $suppliers = $suppliersJson->suppliers;
                return view('/products/edit')->with('product', $product)->with('suppliers',$suppliers);

            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
            }else{
                return redirect('/');
            }
        // return view('/products/edit')->with('product', Product::find($id))->with('suppliers', Supplier::where('status', 'Activo')->get());
    }

   public function update(Request $request, $id){
    //dd($request);
    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->when($request->hasFile('image1'), function ($http) use ($request) {
            return $http->attach(
                'image1', file_get_contents($request->image1), $request->image1->getClientOriginalName()
            );
        })->when($request->hasFile('image2'), function ($http) use ($request) {
            return $http->attach(
                'image2', file_get_contents($request->image2), $request->image2->getClientOriginalName()
            );
        })->when($request->hasFile('image3'), function ($http) use ($request) {
            return $http->attach(
                'image3', file_get_contents($request->image3), $request->image3->getClientOriginalName()
            );
        })->post('http://127.0.0.1:8002/api/products/update/'.$id,[
            'supplier_id' => $request->supplier_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'existence' => $request->existence,
            'capability' => $request->capability,
            'capability_type' => $request->capability_type,
            'color' => $request->color,
            'type'=> $request->type,
            'status' => $request->status,
            '_method' => 'PUT'

        ]);
       // dd($response);
        if($response->successful()){
            $productJson = json_decode($response->body());
            //$product = $productJson->success;
        //    session()->flash('message' ,$product);
            //dd($orders);
            return redirect()->route('products.index');

        }else{
            session()->flash('error','Error en la peticion');
            return redirect()->back();
        }
      
    }else{
        return redirect('/');
    }

    //return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }

   public function show($id){
    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/products/show/'.$id);

        if($response->successful() ){
            $proudctJson = json_decode($response->body());
            $product = $proudctJson->product;
            return view('/products/show')->with('product', $product);

        }else{
            session()->flash('error','Error en la peticion');
            return redirect()->back();
        }
        
        }else{
            return redirect('/');
        }
   // return view('/products/show')->with('product', Product::find($id));
   }

   public function delete(Request $request, $id){

    if(Session::get('api_key')){

        $response = Http::withToken(Session::get('api_key'))->delete('http://127.0.0.1:8002/api/products/delete/'.$id);

        if($response->successful() ){
            return redirect()->route('products.index');


        }else{
            session()->flash('error','Error en la peticion');
            return redirect()->back();
        }
        
        }else{
            return redirect('/');
        }


    // $product= Product::find($id);
    // $product->status='Inactivo';
    // $product->save();
    //return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }


}