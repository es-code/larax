<?php

namespace Escode\Larax\App\Models;

use Illuminate\Database\Eloquent\Model;

class LaraxUser extends Model
{
    protected $table="larax_users";

    protected $fillable=['user_name','user_key','device_token'];
    //
}
