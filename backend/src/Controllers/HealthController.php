<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HealthController extends Controller
{
    public function index(): void
    {
        $this->json([
            'status' => 'ok',
            'message' => 'API opérationnelle',
        ]);
    }
}
