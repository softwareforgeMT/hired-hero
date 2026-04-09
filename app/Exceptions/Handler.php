<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use App\CentralLogics\Helpers;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */


    public function register(): void
    {

        $this->reportable(function (Throwable $e) {
           Helpers::logError($e);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('errors.404');
        }elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 500) {
            return response()->view('errors.500');
        }
        return parent::render($request, $exception);
    }

      protected function unauthenticated($request, Throwable $exception)
    {
                
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $guard = Arr::get($exception->guards(), 0);

        switch ($guard) {
        case 'admin':
            $login = 'admin.login';
            break;
        default:   
        $login = 'user.login';
        break;
            
        }
        return redirect()->guest(route($login));
    }

}
