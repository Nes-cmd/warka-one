<?php

namespace App\Exceptions;

use App\Services\TelegramReport;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
}
