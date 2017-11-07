@extends("layouts.master")

@section("content")
  <div class="row">
    <div class="col m12">
      <table class="table responsive-table bordered highlight" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Game Name</th>
                <th>Description</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Game Name</th>
                <th>Description</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d->game_name}}</td>
                <td>{{$d->description}}</td>
                <td>{{$d->created_at}}</td>
                <td><a href="{{url("/games/".$d->id)}}"><i class="material-icons">send</i></a></td>
            </tr>
            @endforeach
        </tbody>
      </table> 
    </div>
  </div>
@endsection