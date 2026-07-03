<?php

declare(strict_types=1);

namespace App\Modules\Voyage\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Modules\Voyage\Services\VoyageService;
use PDOException;

final class VoyageController extends Controller
{
    private VoyageService $service;

    public function __construct()
    {
        $this->service = new VoyageService();
    }

    public function index(): void
    {
        $destination = isset($_GET['destination']) ? trim((string) $_GET['destination']) : null;
        $budgetRaw = $_GET['budget_max'] ?? null;
        $dateDepart = isset($_GET['date_depart']) ? trim((string) $_GET['date_depart']) : null;

        $budgetMax = null;

        if ($budgetRaw !== null && $budgetRaw !== '') {
            if (!is_numeric($budgetRaw)) {
                Response::error('Le budget maximum doit être numérique.', 422);
            }

            $budgetMax = (float) $budgetRaw;
        }

        try {
            $voyages = $this->service->listOrSearch($destination, $budgetMax, $dateDepart);
        } catch (PDOException) {
            Response::error('Impossible de récupérer les voyages.', 500);
        }

        $this->json([
            'count' => count($voyages),
            'voyages' => $voyages,
        ]);
    }

    public function show(): void
    {
        $id = $this->resolveIdFromQuery();

        if ($id === null) {
            Response::error('Paramètre id requis.', 400);
        }

        try {
            $voyage = $this->service->getDetail($id);
        } catch (PDOException) {
            Response::error('Impossible de récupérer le voyage.', 500);
        }

        $this->json($voyage);
    }

    private function resolveIdFromQuery(): ?int
    {
        $raw = $_GET['id'] ?? $_GET['id_voyage'] ?? null;

        if ($raw === null || $raw === '') {
            return null;
        }

        if (!is_numeric($raw)) {
            Response::error('Identifiant invalide.', 400);
        }

        return (int) $raw;
    }
}
