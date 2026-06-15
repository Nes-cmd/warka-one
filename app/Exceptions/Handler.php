<?php

namespace App\Exceptions;

use App\Services\TelegramReport;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueOAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
            if (! $this->shouldReportToTelegram($e)) {
                return;
            }

            $message = 'Error from Kerone System : at file ' . $e->getFile() . ' line number : ' . $e->getLine() . ' message : ' . $e->getMessage();
            TelegramReport::report($message);

            Log::debug('Error : '. json_encode(request()->all()));
            Log::debug('Error : '. json_encode(request()->ip()));
        });
    }

    /**
     * Determine if an exception should be sent to Telegram.
     */
    protected function shouldReportToTelegram(Throwable $e): bool
    {
        if (env('APP_ENV') !== 'production' || ! $this->shouldReport($e)) {
            return false;
        }

        if ($e instanceof OAuthServerException || $e instanceof LeagueOAuthServerException) {
            return false;
        }

        if ($e instanceof ValidationException || $e instanceof AuthenticationException) {
            return false;
        }

        // Skip expected HTTP client errors (4xx).
        if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
            return false;
        }

        return true;
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
        // For Filament/admin routes, let Filament handle its own authentication
        // Filament will redirect to its own login page
        if ($request->is('admin*') || $request->is('filament*')) {
            return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest('/admin/login');
        }
        
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
