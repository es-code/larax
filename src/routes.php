<?php


//web routes
\Illuminate\Support\Facades\Route::group(['prefix'=>'larax','namespace'=>'Escode\Larax\App\Http\Controllers','middleware'=>['web',config('larax.middleware')]],function (){
   //exceptions routes
    \Illuminate\Support\Facades\Route::group(['prefix'=>'exceptions'],function (){
       //exceptions index
       \Illuminate\Support\Facades\Route::get('/','ExceptionsController@index');
       //exception solved
       \Illuminate\Support\Facades\Route::get('solved/{id}','ExceptionsController@solved');
   });
   //users routes

    \Illuminate\Support\Facades\Route::group(['prefix'=>'users'],function (){
        //users index
        \Illuminate\Support\Facades\Route::get('/','UsersController@index');
        //create users
        \Illuminate\Support\Facades\Route::get('add','UsersController@create');
        \Illuminate\Support\Facades\Route::post('store','UsersController@store');
        //delete user
        \Illuminate\Support\Facades\Route::get('delete/{id}','UsersController@delete');
    });



});



//api routes
\Illuminate\Support\Facades\Route::group(['prefix'=>'api/larax','namespace'=>'Escode\Larax\App\Http\Controllers\Api','middleware'=>config('larax.ApiMiddleware')],function (){
    \Illuminate\Support\Facades\Route::get('exceptions','ExceptionsController@index');

});
