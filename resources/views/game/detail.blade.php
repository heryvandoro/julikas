@extends("layouts.master")

@section("content")
	<div class="row"><h3 class="center">Game : {{$data->game_name}}</h3></div>
  <div class="row">
    <div class="col m12">
      <table class="table responsive-table bordered highlight" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Starter</th>
                <th>Group ID</th>
                <th>Status</th>
								<th>Start Time</th>
								<th>End Time</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Session ID</th>
                <th>Starter</th>
                <th>Group ID</th>
                <th>Status</th>
								<th>Start Time</th>
								<th>End Time</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($data->game_sessions as $d)
            <tr>
              	<td>{{$d->id}}</td>
                <td>{{$d->starter_id}} ({{$d->user->displayName}})</td>
                <td>{{$d->group_id}}</td>
                <td>{{$d->status}}</td>
								<td>{{$d->created_at}}</td>
								<td>{{$d->end_time}}</td>
            </tr>
            @endforeach
        </tbody>
      </table> 
    </div>
  </div>
@endsection