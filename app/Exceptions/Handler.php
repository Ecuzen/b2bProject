<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

// ...

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    protected function getControllerName()
    {
        $routeAction = request()->route()->getAction('controller');
        return explode('@', $routeAction)[0];
    }
    
    protected function getActionName()
    {
        $routeAction = request()->route()->getAction('controller');
        return explode('@', $routeAction)[1];
    }


    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof HttpException) {
            return response()->view('errors.404', [], 404);
        }
        /*if ($exception instanceof \Exception) {
            // Get the controller and action that triggered the exception
            $controller = $this->getControllerName($request);
            $action = $this->getActionName($request);
            
            // Custom error message
            if($controller == 'App\\Http\\Controllers\\Android')
            return response()->json(['status'=>'ERROR','message'=>'Error in '.$controller.'@'.$action.' with message '.$exception->getMessage()],500);
            return response()->json(['status'=>'ERROR','message'=>'Error in '.$controller.'@'.$action.' with message '.$exception->getMessage(),'msg'=>'Error in '.$controller.'@'.$action.' with message '.$exception->getMessage()],200);
        }*/
    
        return parent::render($request, $exception);
    }

}
