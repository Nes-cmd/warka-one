<?php

namespace App\Exceptions;

use App\Services\TelegramReport;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (env('APP_ENV') == 'production') {
                $message = 'Error from Kerone System : at file ' . $e->getFile() . ' line number : ' . $e->getLine() . ' message : ' . $e->getMessage();
                TelegramReport::report($message);
            }
            Log::debug('Error : '. json_encode(request()->all()));
            Log::debug('Error : '. json_encode(request()->ip()));
            return redirect()->back();
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // For OAuth authorization requests, always redirect to React login (v2.login)
        // External applications should use the React login page
        if ($request->is('oauth/authorize') || $request->fullUrlIs('*oauth/authorize*')) {
            return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest(route('v2.login'));
        }
        
        // For other requests, check if they're coming from React routes
        // React routes are at root level (not /v1 or /admin)
        $path = $request->path();
        if (!str_starts_with($path, 'v1/') && !str_starts_with($path, 'admin/')) {
            return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest(route('v2.login'));
        }
        
        // Default to v1 login for Blade routes
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('v1.login'));
    }
}
