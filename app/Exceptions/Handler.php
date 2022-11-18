<?php

namespace App\Exceptions;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->renderable(function (Throwable $th, Request $request) {
            if ($request->is("api/*")) {
                if ($th instanceof ValidationException) {
                    return ResponseFormatter::error(
                        $th->getMessage(),
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        $th->errors()
                    );
                } elseif ($th instanceof NotFoundHttpException) {
                    return ResponseFormatter::error("NOT FOUND", Response::HTTP_NOT_FOUND, $th->getMessage());
                } elseif ($th instanceof UnauthorizedHttpException) {
                    return ResponseFormatter::error("Unauthorized", Response::HTTP_UNAUTHORIZED, $th->getMessage());
                } elseif ($th instanceof BadRequestHttpException) {
                    return ResponseFormatter::error("Bad Request", Response::HTTP_BAD_REQUEST, $th->getMessage());
                } elseif ($th instanceof AccessDeniedHttpException) {
                    return ResponseFormatter::error("Forbidden", Response::HTTP_FORBIDDEN, $th->getMessage());
                }
            }
        });
    }
}
