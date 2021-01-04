<?php
return [
    //set your middleware that's allow to access exceptions pages on website
    'middleware'=>'auth',
    //api middleware
    'ApiMiddleware'=>'AuthLaraxApi',

    //exceptions data you want to store it
    'exception_data'=>[
        'ip'=>true,
        'url'=>true,
        'headers'=>true,
        'body'=>true,
    ],
    //Note: if you enable detect user in exception larax will push StartSession::class and EncryptCookies::class in $middleware Kernel.php
    //and you should be comment StartSession::class  from $middlewareGroups => web
    'detect_user'=>false,
    //array of guards names will use it for detect user id
    'guards'=>['auth'],

    //ignore fields in request headers (this fields not saved in db)
    'ignore_headers'=>[
        'Authorization','cookie'
    ],

    //ignore fields in request (this fields not saved in db)
    'ignore_inputs'=>[
        'image','password','password_confirmation'
    ],


    //enable send emails
    'enable_email'=>true,

    //email list you want send message to them
    'emails'=>[
       //example
        //'es.code@yahoo.com',
    ],

];
