<!DOCTYPE html>
<html lang="en">
<head>
    <title>LaraX Exceptions</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <style>
        .solved-icon{
            position: absolute;
            border: solid green;
            padding: 10px;
            border-radius: 50%;
            background: green;
            color: white;
            height: 50px;
            width: 50px;
            margin-left: 81%;
            margin-top: -3%;
        }

        .deleted-icon{
            position: absolute;
            border: solid red;
            padding: 10px;
            border-radius: 50%;
            background: red;
            color: white;
            height: 50px;
            width: 50px;
            margin-left: 81%;
            margin-top: -3%;
        }
        .p_data{
            line-break: anywhere;
            max-height: 200px;
            overflow-x: scroll;
        }


    </style>
    @stack('css')
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('larax/exceptions')}}">LaraX</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="{{ (request()->is('larax/exceptions')) ? 'active' : '' }}"><a href="{{url('larax/exceptions')}}">Home</a></li>
            <li class="{{ (request()->is('larax/users/add')) ? 'active' : '' }}"><a href="{{url('larax/users/add')}}">Add User</a></li>
            <li class="{{ (request()->is('larax/users')) ? 'active' : '' }}"><a href="{{url('larax/users')}}">Users</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    @include('larax::layout.alerts')
   @yield('content')
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

@stack('js')
</body>
</html>
