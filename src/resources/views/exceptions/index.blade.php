@extends('larax::layout.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline" method="get" action="{{url('larax/exceptions')}}">
                <div class="form-group">
                    <input type="datetime-local"  name="from" class="form-control" placeholder="From Date" value="{{request()->get('from')}}">
                </div>
                <div class="form-group">
                    <input type="datetime-local"  name="to" class="form-control" placeholder="To Date" value="{{request()->get('to')}}">
                </div>
                <div class="form-group">
                    <select name="solved" class="form-control">
                        <option value="">solved/un-solved</option>
                        <option {{request()->get('solved') == "1"?"selected":""}} value="1">solved</option>
                        <option {{request()->get('solved') == "0"?"selected":""}} value="0">un-solved</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="url" class="form-control" placeholder="Url" value="{{request()->get('url')}}">
                </div>
                <div class="form-group">
                    <input type="number"  name="user_id" class="form-control" placeholder="User Id" value="{{request()->get('user_id')}}">
                </div>
                <div class="form-group">
                    <input type="text"  name="guard" class="form-control" placeholder="Guard" value="{{request()->get('guard')}}">
                </div>
                <div class="form-group">
                    <input type="text"  name="user_ip" class="form-control" placeholder="User IP Address" value="{{request()->get('user_ip')}}">
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>

        </div>
    </div>
    <br />


   <div class="row">
       <div class="col-md-12">

           @if(count($exceptions))

               <hr>
               <div class="row">
                   <div class="col-md-6">
                       <canvas id="dates-report"></canvas>
                   </div>
                   <div class="col-md-6">
                       <canvas id="times-report"></canvas>
                   </div>
               </div>
           <hr>

               @foreach($exceptions as $exception)
               <div class="col-md-12">
                     <div class="panel-group">
               <div class="panel {{$exception->solved==1?"panel-success":"panel-danger"}}">
                   <div class="panel-heading">
                       <h4 class="panel-title">
                           <a style="display: inline-block; max-width: 85%;width: 85%" data-toggle="collapse" href="#collapse{{$exception->id}}">{{$exception->url}} -  {{$exception->created_at}}
                               </a>

                           @if($exception->solved == 1)
                           <svg  width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-circle solved-icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                               <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                               <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                           </svg>
                           @else
                            <a onclick="return confirm('Are you sure this exception is solved ?')" href="{{url('larax/exceptions/solved/'.$exception->id.'?_token='.csrf_token())}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle deleted-icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg></a>
                           @endif

                       </h4>
                   </div>
                   <div id="collapse{{$exception->id}}" class="panel-collapse collapse">
                       <ul class="list-group">

                           <li class="list-group-item">
                               <table class="table table-bordered">
                                   <thead>
                                   <tr>
                                       <th>User Id</th>
                                       <th>User Guard</th>
                                       <th>User Ip</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr><td>{{$exception->user_id}}</td><td>{{$exception->guard}}</td><td>{{$exception->ip}}</td></tr>
                                   </tbody>
                               </table>

                               <table class="table table-bordered">
                                   <thead>
                                   <tr>
                                       <th>Headers</th>
                                       <th>Body</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr><td width="50%"><p class="p_data">{{$exception->headers}}</p></td><td><p class="p_data">{{$exception->body}}</p></td></tr>
                                   </tbody>
                               </table>
                           </li>
                           <li class="list-group-item">
                               <p class="p_data">{{$exception->exception}}</p>
                           </li>

                       </ul>

                   </div>
               </div>
           </div>
               </div>
               @endforeach

               {{$exceptions->links()}}
           @else
               <div class="alert alert-success">No Exceptions Found</div>
           @endif




   </div>
   </div>

@endsection

@push('js')
{{--    push chartjs--}}
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script>

    drawDatesChart();
    drawTimesChart();

function drawTimesChart() {
        //our times report canvas
        var ctx = document.getElementById('times-report').getContext('2d');
        let times={!! json_encode($reports['times']) !!};
//chart labels
        let label_times = [];
//chart data
        let data = [];
// check dates report not empty
        if(Object.keys(times).length < 1){
            return ;
        }
        Object.keys(times).sort().forEach(function (key){
            label_times.push(key);
            data.push(times[key]);
        });

        //  create chart with options
        new Chart(ctx,{
            type: 'line',
            data: {
                labels: label_times,
                datasets: [{
                    label: "exception",
                    fill: false,
                    backgroundColor: "red",
                    borderColor: "red",
                    data:data ,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: "exceptions based on time"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'times'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'exceptions count'
                        }
                    }]
                }
            }
        });
    }

function drawDatesChart() {
//our dates report canvas
    var ctx = document.getElementById('dates-report').getContext('2d');
    let dates={!! json_encode($reports['dates']) !!};
//chart labels
    let label_dates = [];
//chart data
    let data = [];
// check dates report not empty
    if(Object.keys(dates).length < 1){
        return ;
    }
    Object.keys(dates).sort().forEach(function (key){
        label_dates.push(key);
        data.push(dates[key]);
    });

    //  create chart with options
    new Chart(ctx,{
        type: 'line',
        data: {
            labels: label_dates,
            datasets: [{
                label: "exception",
                fill: false,
                backgroundColor: "green",
                borderColor: "green",
                data:data ,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: "exceptions based on dates"
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'dates'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'exceptions count'
                    }
                }]
            }
        }
    });

}


</script>

@endpush
