<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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
    public function register()
    {
        $this->reportable(function (Throwable $e) {});

        /*Hanle validation exception*/
        $this->renderable(function (ValidationException $e, $request) {
            return $this->convertValidationExceptionToResponse($e, $request);
        });

        /*Handle ModelNotFoundException*/
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($this->isFrontend($request) && !$request->expectsJson()) {
                if ($request->is('admin/*')) {
                    return response()->view('errors.404', [], 404);
                }
                return response()->view('errors.web.404', [], 404);
            }
            return $this->returnErrorResponse("No existe ningún modelo con el identificador especificado");
        });

        /*Handle NotFoundHttpException*/
        $this->renderable(function (NotFoundHttpException $e, $request) {

            if ($this->isFrontend($request) && !$request->expectsJson()) {
                if ($request->is('admin/*')) {
                    return response()->view('errors.404', [], 404);
                }
                return response()->view('errors.web.404', [], 404);
            }
            if ($request->wantsJson())
                return $this->returnErrorResponse("The specified URL cannot be found");
        });

        /*Handle MethodNotAllowedHttpException*/
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($this->isFrontend($request) && !$request->expectsJson()) {
                if ($request->is('admin/*')) {
                    return response()->view('errors.404', [], 404);
                }
                return response()->view('errors.web.404', [], 404);
            }
            return $this->returnErrorResponse("The method specified for the request is not valid.");
        });

        /*Handle BadMethodCallException*/
        $this->renderable(function (\BadMethodCallException $e, $request) {
            if ($this->isFrontend($request) && !$request->expectsJson()) {
                if ($request->is('admin/*')) {
                    return response()->view('errors.404', [], 404);
                }
                return response()->view('errors.web.404', [], 404);
            }
            return $this->returnErrorResponse($e->getMessage());
        });
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->returnErrorResponse('You are not authorized', [], 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $errors = $e->validator->errors()->getMessages();
        $message = $e->getMessage();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json(['message' => "The data provided was not valid.", 'errors' => $errors], 400) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        $firstError = $e->validator->errors()->first();

        return $this->errorResponse([
            'status' => false,
            'message' => $firstError,
            'errors' => $errors

        ], 400);
    }

    /*function to check request type*/
    private function isFrontend($request)
    {
        return !($request->is('api/*')) || ($request->acceptsHtml() && !is_null($request->route()) && collect($request->route()->middleware())->contains('web'));
    }
}
