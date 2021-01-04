<?php

namespace Escode\Larax\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Escode\Larax\App\Models\LaraxException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExceptionsController extends Controller
{
    protected $model;
    public function __construct(LaraxException $model)
    {
        $this->model=$model;
    }

    public function index(Request $request){

        $exceptions = $this->model::query();
        if($request->user_ip)
            $exceptions->where('ip',$request->ip);
        if($request->from)
            $exceptions->where('created_at','>=',Carbon::parse($request->from)->toDateTimeString());
        if($request->to)
            $exceptions->where('created_at','<=',Carbon::parse($request->to)->toDateTimeString());
        if ($request->user_id)
            $exceptions->where('user_id', $request->user_id);
        if ($request->url)
            $exceptions->where('url', 'like', '%' . $request->url . '%');
        if ($request->guard)
            $exceptions->where('guard', $request->guard);
        if($request->solved)
            $exceptions->where('solved',$request->solved);

        $exceptions_order = $exceptions->orderBy('id', 'desc');
        //get all exceptions and make dates and times report
        $reports=$this->dates_report($exceptions_order->get(['created_at']));
        //paginate exceptions
        $exceptions = $exceptions_order->paginate(15);
        return view('larax::exceptions.index',compact(['exceptions','reports']));
    }

    private function dates_report($exceptions){
        $dates=[];
        $times=[];
        foreach ($exceptions as $exception){
            $date=$exception->created_at->format("Y-m-d");
            $time=$exception->created_at->format("H:i");
            if(array_key_exists($date,$dates)){
                $dates[$date]+=1;
            }else{
                $dates[$date]=1;
            }

            if(array_key_exists($time,$times)){
                $times[$time]+=1;
            }else{
                $times[$time]=1;
            }
        }

        return ['dates'=>$dates,'times'=>$times];
    }


    public function solved($id,Request $request){
        if (Session::token() != $request->get('_token'))
        {
            throw new \Illuminate\Session\TokenMismatchException;
        }
       $item=$this->model->findOrFail($id);
        $item->solved=true;
        if($item->save()){
            return redirect()->back()->with(['success'=>'done solved exception']);
        }
        return redirect()->back()->withErrors('error happened when update exception as solve');
    }

}
