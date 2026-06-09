<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

$notFoundMessageByModel = function (?string $baseName): string {
    return match ($baseName) {
        'User' => 'User tidak ditemukan',
        'PenanggungJawab' => 'Penanggung jawab tidak ditemukan',
        'Akun' => 'Akun tidak ditemukan',
        'RiilHistory' => 'Riil history tidak ditemukan',
        'Transaksi' => 'Transaksi tidak ditemukan',
        'MutasiRekening' => 'Mutasi rekening tidak ditemukan',
        default => 'Data tidak ditemukan',
    };
};

return function (Exceptions $exceptions) use ($notFoundMessageByModel): void {
    $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
        return $request->is('api/*') || $request->expectsJson();
    });

    $exceptions->render(function (ValidationException $e, Request $request) {
        $errors = $e->errors();

        $allMessages = collect($errors)->flatten()->values();
        $firstMessage = (string) ($allMessages->first() ?? '');
        $remainingCount = max(0, $allMessages->count() - 1);

        if ($firstMessage === '') {
            $message = 'Validasi gagal';
        } elseif ($remainingCount > 0) {
            $message = $firstMessage.', dan '.$remainingCount.' kesalahan lainnya';
        } else {
            $message = $firstMessage;
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], 422);
    });

    $exceptions->render(function (AuthenticationException $e, Request $request) {
        return response()->json([
            'status' => 'unauthenticated',
            'message' => 'Anda belum login atau sesi Anda telah berakhir',
        ], 401);
    });

    $exceptions->render(function (ModelNotFoundException $e, Request $request) use ($notFoundMessageByModel) {
        $model = $e->getModel();
        $baseName = $model ? class_basename($model) : null;

        $message = $notFoundMessageByModel($baseName);

        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], 404);
    });

    $exceptions->render(function (HttpExceptionInterface $e, Request $request) use ($notFoundMessageByModel) {
        $statusCode = $e->getStatusCode();

        $rawMessage = (string) $e->getMessage();
        $message = $rawMessage;

        if ($statusCode === 404 && $rawMessage !== '') {
            if (preg_match('/^No query results for model \[([^\]]+)\]\s*(.*)$/', $rawMessage, $matches) === 1) {
                $modelClass = $matches[1] ?? null;
                $baseName = $modelClass ? class_basename($modelClass) : null;

                $message = $notFoundMessageByModel($baseName);
            }
        }

        return response()->json([
            'status' => $statusCode === 401 ? 'unauthenticated' : ($statusCode === 403 ? 'forbidden' : 'error'),
            'message' => $message !== '' ? $message : 'Terjadi kesalahan',
        ], $statusCode);
    });

    $exceptions->render(function (Throwable $e, Request $request) {
        if (! $request->is('api/*') && ! $request->expectsJson()) {
            return null;
        }

        $debug = (bool) config('app.debug');

        return response()->json([
            'status' => 'error',
            'message' => $debug ? $e->getMessage() : 'Terjadi kesalahan pada server',
        ], 500);
    });
};
