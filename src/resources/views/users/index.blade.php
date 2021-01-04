@extends('larax::layout.app')

@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>user name</th>
            <th>user key</th>
            <th>del</th>
        </tr>
        </thead>
        @if(count($users) > 0)
            @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->user_name}}</td>
                <td>{{$user->user_key}}</td>
                <td><a onclick="return confirm('Are you sure delete this user ?')" href="{{url('larax/users/delete/'.$user->id.'?_token='.csrf_token())}}">delete</a> </td>
            </tr>
            @endforeach
           {{$users->links()}}
        @endif
    </table>
@endsection
