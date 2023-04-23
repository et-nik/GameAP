<?php

namespace Gameap\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    private const MAP_EXCEPTION_HTTP_CODE = [
        ValidationException::class                                           => Response::HTTP_UNPROCESSABLE_ENTITY,
        \Gameap\Exceptions\GdaemonAPI\InvalidApiKeyException::class          => Response::HTTP_UNAUTHORIZED,
        \Gameap\Exceptions\GdaemonAPI\InvalidTokenExeption::class            => Response::HTTP_UNAUTHORIZED,
        \Gameap\Exceptions\Repositories\RecordExistExceptions::class         => Response::HTTP_UNPROCESSABLE_ENTITY,
        \Gameap\Exceptions\Repositories\RepositoryValidationException::class => Response::HTTP_BAD_REQUEST,
        \Illuminate\Validation\ValidationException::class                    => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];

    public function render($request, \Throwable $exception)
    {
        if ($request->expectsJson() || $request->isJson()) {
            return $this->renderJson($request, $exception);
        }

        if ($exception instanceof \Gameap\Exceptions\GdaemonAPI\InvalidSetupTokenExeption) {
            if (app()->has('debugbar')) {
                app('debugbar')->disable();
            }

            // Return bash
            return response()->make('echo "' . $exception->getMessage() . '"', 401);
        }

        if ($exception instanceof HttpException) {
            if ($request->acceptsHtml()) {
                return parent::render($request, $exception);
            }

            if ($request->acceptsJson()) {
                return response()->json([
                    'message'   => $exception->getMessage(),
                    'http_code' => $exception->getStatusCode(),
                ], $exception->getStatusCode(), $exception->getHeaders());
            }
        }

        return parent::render($request, $exception);
    }

    private function renderJson($request, \Throwable $exception)
    {
        foreach (self::MAP_EXCEPTION_HTTP_CODE as $instance => $httpCode) {
            if ($exception instanceof $instance) {
                return response()->json([
                    'message'   => $exception->getMessage(),
                    'http_code' => $httpCode,
                ], $httpCode);
            }
        }

        return parent::render($request, $exception);
    }
}
