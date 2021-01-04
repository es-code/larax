<?php
namespace Escode\Larax;

use Escode\Larax\App\Models\LaraxException;
use Escode\Larax\App\Models\LaraxUser;
use Illuminate\Foundation\Exceptions\Handler as LaravelExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;


class ExceptionHandler extends LaravelExceptionHandler

{

    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        //
    ];


    public function report(Throwable $exception)
    {
        if (!$this->shouldntReport($exception)) {
            Larax::exception($exception);
        }
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }











}
