@extends('/admin')

@section('contenido')

<table class="table table-striped-columns text-white">
    <thead>
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Producto</th>
        <th scope="col">Cantidad</th>
        <th scope="col">Precio</th>
        <th scope="col">Total</th>
        <th scope="col">Hora del pedido</th>

        <th></th>
       
      </tr>
    </thead>
    
    <tbody>

       @foreach ($orders as $order)
          
      <tr>
        <th scope="row">{{$order->id}}</th>
        <td>{{$order->product->name}}</td>
        <td>{{$order->quantity}}</td>
        <td>{{$order->product->price}}</td>
        <td>{{$order->subtotal}}</td>
        <td>{{$order->created_at}}</td>

      </tr>
      @endforeach
    </tbody>
  </table>
  @endsection