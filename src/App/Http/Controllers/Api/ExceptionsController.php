<?php

namespace Escode\Larax\App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Escode\Larax\App\Models\LaraxException;
use Illuminate\Http\Request;

class ExceptionsController extends Controller
{
    protected $model;

    public function __construct(LaraxException $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $data = $this->model::query();

        if($request->user_ip)
            $data->where('ip',$request->ip);
        if($request->from)
            $data->where('created_at','>=',Carbon::parse($request->from)->toDateTimeString());
        if($request->to)
            $data->where('created_at','<=',Carbon::parse($request->to)->toDateTimeString());
        if ($request->user_id)
            $data->where('user_id', $request->user_id);
        if ($request->url)
            $data->where('url', 'like', '%' . $request->url . '%');
        if ($request->guard)
            $data->where('guard', $request->guard);
        if($request->solved)
            $data->where('solved',$request->solved);

        $data = $data->orderBy('id', 'desc')->paginate(30);
        return response()->json(['status' => true, 'data' => $data]);

    }



}

