<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function json(mixed $data, int $status = 200, bool $success = true): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        $payload = is_array($data) && array_key_exists('success', $data)
            ? $data
            : ['success' => $success, 'data' => $data];

        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $message, int $status = 400): void
    {
        self::json(['success' => false, 'message' => $message], $status, false);
    }
}
