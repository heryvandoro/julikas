@extends("layouts.master")

@section("content")
  <div class="row">
    <div class="col m12">
      <table class="table responsive-table bordered highlight" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Owner</th>
                <th>Price</th>
                <th>Photo</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Owner</th>
                <th>Price</th>
                <th>Photo</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d->product_name}}</td>
                <td>{{$d->category->category_name}}</td>
                <td>{{$d->user->detail->displayName}}</td>
                <td>{{$d->price}}</td>
                <td>{{$d->photo}}</td>
            </tr>
            @endforeach
        </tbody>
      </table> 
    </div>
  </div>
@endsection