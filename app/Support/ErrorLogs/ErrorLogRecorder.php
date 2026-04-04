<?php

namespace App\Support\ErrorLogs;

use App\Models\Report\ErrorLog;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Http\Request;
use Throwable;

class ErrorLogRecorder
{
    public static function record(Throwable $throwable, ?Request $request = null): void
    {
        try {
            ErrorLog::query()->create([
                'exception_class' => $throwable::class,
                'message' => $throwable->getMessage(),
                'level' => self::resolveLevel($throwable),
                'path' => $request?->path(),
                'method' => $request?->method(),
                'ip_address' => $request?->ip(),
                'user_identifier' => self::resolveUserIdentifier($request),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => self::formatTrace($throwable),
                'occurred_at' => now(),
            ]);
        } catch (Throwable) {
            // Avoid recursive failures while trying to record an error.
        }
    }

    protected static function resolveLevel(Throwable $throwable): string
    {
        $statusCode = $throwable instanceof HttpExceptionInterface
            ? $throwable->getStatusCode()
            : null;

        if ($statusCode && $statusCode < 500) {
            return 'warning';
        }

        return 'error';
    }

    protected static function formatTrace(Throwable $throwable): string
    {
        $traceLines = collect($throwable->getTrace())
            ->take(8)
            ->map(function (array $frame, int $index) {
                $file = $frame['file'] ?? '[internal]';
                $line = $frame['line'] ?? '?';
                $function = $frame['function'] ?? 'unknown';
                $class = $frame['class'] ?? '';
                $type = $frame['type'] ?? '';

                return sprintf('#%d %s:%s %s%s%s', $index, $file, $line, $class, $type, $function);
            })
            ->all();

        if ($traceLines === []) {
            return $throwable->getTraceAsString();
        }

        return implode("\n", $traceLines);
    }

    protected static function resolveUserIdentifier(?Request $request): ?string
    {
        $user = $request?->user();

        if ($user instanceof User) {
            return $user->username ?: $user->name;
        }

        return null;
    }
}
