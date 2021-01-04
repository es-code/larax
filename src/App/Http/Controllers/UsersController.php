<?php

namespace Escode\Larax\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Escode\Larax\App\Models\LaraxException;
use Escode\Larax\App\Models\LaraxUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    protected $model;
    public function __construct(LaraxUser $model)
    {
        $this->model=$model;
    }

    public function index(Request $request){
        $users = $this->model->orderBy('id', 'desc')->paginate(10);

        return view('larax::users.index',compact(['users']));
    }

    public function create(){

        return view('larax::users.create');
    }

    public function store(Request $request){

        $request->validate([
           'user_name'=>'required',
            'user_key'=>'required|unique:larax_users'
        ]);
        $save=$this->model::create($request->only('user_name','user_key'));
        if($save){
            return redirect()->back()->with(['success'=>'done add new user']);
        }

        return redirect()->back()->withErrors('error happened when insert new user in db');

    }


    public function delete($id,Request $request){
        if (Session::token() != $request->get('_token'))
        {
            throw new \Illuminate\Session\TokenMismatchException;
        }
        $item=$this->model->findOrFail($id);
        if($item->delete()){
            return redirect()->back()->with(['success'=>'done delete user']);
        }

        return redirect()->back()->withErrors('user not deleted');
    }

}
