<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Omnipay\Omnipay;
use App\Models\Payment;
use App\Models\Order;
use App\Models\DetailOrder;
use Darryldecode\Cart\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct() {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function pay(Request $request)
    {
        try {

            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error')
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                return $response->getMessage();
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state'];

                $payment->save();

                $responseOrder = Http::withToken(Session::get('api_key'))->post('http://127.0.0.1:8002/api/order_register', [
                   // 'time' => now(),
                    'phone' => Session::get('user')->phone,
                    'address' => Session::get('user')->address,
                    'user_id' =>Session::get('user')->id,
                ]);

                //dd($responseOrder);
                if($responseOrder->successful()){
                    $orderJson = json_decode($responseOrder->body());
                    $order = $orderJson->order;
                    $cartItems = \Cart::getContent()->toArray();
                   // dd($cartItems);
                    $responseDetails = Http::withToken(Session::get('api_key'))->post('http://127.0.0.1:8002/api/create_detail_order/'.$order->id, [
                        'datos' => $cartItems
                    ]);  
                    //dd($responseDetails);
                    if($responseDetails->successful()){
                        \Cart::clear();
                        session()->flash('success',"Payment is Successfull. Your Transaction Id is : " . $arr['id']);
                        return redirect()->route('cart.list');
                    }
                }
                //return redirect()->route('cart.list');

                // $orden= new Order();
                // $orden->time=now();
                // $orden->phone= Session::get('user')->phone;
                // $orden->address= Session::get('user')->address;
                // $orden->status="Pendiente";
                // $orden->user_id = Session::get('user')->id;
                // $orden->save();

                // $cartItems = \Cart::getContent();
                // $this->CreateOrderDetail($cartItems,$orden->id);

                // \Cart::clear();

                // session()->flash('success',"Payment is Successfull. Your Transaction Id is : " . $arr['id']);
                // return redirect()->route('cart.list');
                // return "Payment is Successfull. Your Transaction Id is : " . $arr['id'];

            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

    public function error()
    {
        return 'User declined the payment!';   
    }
    
    private function CreateOrderDetail(\Darryldecode\Cart\CartCollection $data,$idOrder){

        foreach ($data as $item) {
            $detalle = new DetailOrder();
            $detalle->id_order = $idOrder;
            $detalle->id_product = $item->id;
            $detalle->quantity = $item->quantity;
            $detalle->price = $item->price;
            $detalle->subtotal = $item->price * $item->quantity;
            $detalle->save();

            $product = Product::find($item->id);
            $existence = $product->existence - $item->quantity;
            $product->update(['existence' => $existence]);
        }

    }
}
