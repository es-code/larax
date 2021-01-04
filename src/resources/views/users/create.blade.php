@extends('larax::layout.app')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" action="{{url('larax/users/store')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="control-label col-sm-2" >User Name:</label>
                    <div class="col-sm-10">
                        <input type="text" name="user_name" class="form-control"  placeholder="Enter user name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" >User Key:</label>
                    <div class="col-sm-10">
                        <input type="text" name="user_key" class="form-control"  placeholder="Enter user key will use it to auth app">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </div>
            </form>



        </div>
    </div>

@endsection
