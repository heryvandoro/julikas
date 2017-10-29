@extends("layouts.master")

@section("content")
  <div class="row">
    <div class="col m12">
      <table class="table responsive-table bordered highlight" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Sub-Categories</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Sub-Categories</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d->category_parent_name}}</td>
                <td>
                    @foreach($d->categories as $x)
                      {{$x->category_name}}
                    @endforeach
                </td>
                <td>
                  <span><i class="tiny material-icons">edit</i> Edit</span> | 
                  <span><i class="tiny material-icons">delete</i> Delete</span>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table> 
    </div>
  </div>
@endsection