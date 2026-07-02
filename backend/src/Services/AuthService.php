<?php

declare(strict_types=1);

namespace App\Services;

final class AuthService
{
    private const TOKEN_TTL = 86400;

    public static function createToken(int $userId): string
    {
        $payload = json_encode([
            'sub' => $userId,
            'exp' => time() + self::TOKEN_TTL,
        ], JSON_THROW_ON_ERROR);

        $signature = hash_hmac('sha256', $payload, self::secret());

        return base64_encode($payload) . '.' . $signature;
    }

    public static function validateToken(string $token): ?int
    {
        $parts = explode('.', $token, 2);

        if (count($parts) !== 2) {
            return null;
        }

        [$encodedPayload, $signature] = $parts;
        $payload = base64_decode($encodedPayload, true);

        if ($payload === false) {
            return null;
        }

        $expected = hash_hmac('sha256', $payload, self::secret());

        if (!hash_equals($expected, $signature)) {
            return null;
        }

        try {
            /** @var array{sub?: int, exp?: int} $data */
            $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        if (!isset($data['sub'], $data['exp']) || !is_int($data['sub']) || !is_int($data['exp'])) {
            return null;
        }

        if ($data['exp'] < time()) {
            return null;
        }

        return $data['sub'];
    }

    private static function secret(): string
    {
        return $_ENV['APP_KEY'] ?? 'horizon-secur-dev-key';
    }
}
