@extends("layouts.master")

@section("content")
  <div class="row">
    <div class="col m12">
      <table class="table responsive-table bordered highlight" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Join Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>User Name</th>
                <th>Join Date</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d->detail->displayName}}</td>
                <td>{{$d->updated_at}}</td>
            </tr>
            @endforeach
        </tbody>
      </table> 
    </div>
  </div>
@endsection