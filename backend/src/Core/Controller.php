<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function json(mixed $data, int $status = 200): void
    {
        Response::json($data, $status);
    }

    /** @return array<string, mixed> */
    protected function input(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === false || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    protected function extractRequiredString(array $input, string $field): ?string
    {
        if (!isset($input[$field]) || !is_string($input[$field])) {
            return null;
        }

        $value = trim($input[$field]);

        return $value === '' ? null : $value;
    }

    protected function extractRequiredInt(array $input, string $field): ?int
    {
        $raw = $input[$field] ?? $input['id'] ?? null;

        if ($raw === null || $raw === '') {
            return null;
        }

        if (!is_numeric($raw)) {
            Response::error('Identifiant invalide.', 422);
        }

        return (int) $raw;
    }

    protected function extractRequiredFloat(array $input, string $field = 'valeur'): ?float
    {
        $raw = $input[$field] ?? $input['valeur'] ?? null;

        if ($raw === null || $raw === '') {
            return null;
        }

        $normalized = str_replace(',', '.', (string) $raw);

        if (!is_numeric($normalized)) {
            Response::error('Valeur numérique invalide.', 422);
        }

        return (float) $normalized;
    }
}
