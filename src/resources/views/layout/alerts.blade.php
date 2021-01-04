@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@if(session('success') != "")
    <div class="alert alert-success">
        <li>{{session('success')}}</li>
    </div>
@endif
