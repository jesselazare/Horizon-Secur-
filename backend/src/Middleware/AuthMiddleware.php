<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Services\AuthService;

final class AuthMiddleware
{
    public function handle(callable $next): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
            Response::error('Token d\'authentification requis.', 401);
        }

        $userId = AuthService::validateToken($matches[1]);

        if ($userId === null) {
            Response::error('Token invalide ou expiré.', 401);
        }

        $_SERVER['AUTH_USER_ID'] = (string) $userId;
        $next();
    }
}
