<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
class SupplierController extends Controller
{
    public function index(){
        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/suppliers');
    
            if($response->successful() ){
                $suppliersJson = json_decode($response->body());
                $suppliers = $suppliersJson->suppliers;
                return view('/suppliers/index')->with('suppliers',$suppliers);
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }
        // return view('/suppliers/index')->with('suppliers', Supplier::where('status', 'Activo')->get());
    }

    public function create(){
        return view('/suppliers/create');
    }

    public function store(Request $request){
        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->attach(
                'image', file_get_contents($request->image), $request->image->getClientOriginalName()
            )->post('http://127.0.0.1:8002/api/suppliers/store',[
                'name'=> $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status
            ]);
    
            if($response->successful() ){
                return redirect()->route('suppliers.index');
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }

       // return view('/suppliers/index')->with('suppliers', Supplier::where('status', 'Activo')->get());
    }

    public function edit($id){

        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/suppliers/show/'.$id);
    
            if($response->successful() ){
                $supplierJson = json_decode($response->body());
                $supplier = $supplierJson->supplier;
                return view('/suppliers/edit')->with('supplier',$supplier);
    
            }else{
                session()->flas('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }
       // return view('/suppliers/edit')->with('supplier', Supplier::find($id));
    }

    public function update(Request $request, $id){
        // dd($request);
        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->when($request->hasFile('image'), function ($http) use ($request) {
                return $http->attach(
                    'image', file_get_contents($request->file('image')), 
                    $request->file('image')->getClientOriginalName()
                );
            })->put('http://127.0.0.1:8002/api/suppliers/update/'.$id,[
                'name'=> $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status
            ]);
            // dd($response);
            if($response->successful() ){
                return redirect()->route('suppliers.index');
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }
    }
    
    public function show($id){
        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->get('http://127.0.0.1:8002/api/suppliers/show/'.$id);
    
            if($response->successful() ){
                $supplierJson = json_decode($response->body());
                $supplier = $supplierJson->supplier;
                return view('/suppliers/show')->with('supplier',$supplier);
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }
      //  return view('/suppliers/show')->with('supplier', Supplier::find($id));
    }

    public function delete(Request $request, $id){
        if(Session::get('api_key')){

            $response = Http::withToken(Session::get('api_key'))->delete('http://127.0.0.1:8002/api/suppliers/delete/'.$id);
            
            if($response->successful() ){
               
                return redirect()->route('suppliers.index');
    
            }else{
                session()->flash('error','Error en la peticion');
                return redirect()->back();
            }
            
        }else{
            return redirect('/');
        }
    
        // return view('/suppliers/index')->with('suppliers', Supplier::where('status', 'Activo')->get());  
    }
}