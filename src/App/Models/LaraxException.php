<?php

namespace Escode\Larax\App\Models;


use Illuminate\Database\Eloquent\Model;

class LaraxException extends Model
{

    protected $table = "larax_exceptions";

    protected $fillable = ['url','ip','headers','body','user_id','guard','exception'];

    //
}
