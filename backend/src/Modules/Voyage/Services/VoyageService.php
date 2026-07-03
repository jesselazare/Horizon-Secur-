<?php

declare(strict_types=1);

namespace App\Modules\Voyage\Services;

use App\Core\Response;
use App\Modules\Voyage\Repositories\VoyageRepository;

final class VoyageService
{
    public function __construct(
        private readonly VoyageRepository $repository = new VoyageRepository(),
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listOrSearch(?string $destination, ?float $budgetMax, ?string $dateDepart): array
    {
        $hasDestination = $destination !== null && $destination !== '';
        $hasBudget = $budgetMax !== null;
        $hasDate = $dateDepart !== null && $dateDepart !== '';

        if (!$hasDestination && !$hasBudget && !$hasDate) {
            return $this->repository->all();
        }

        if (!$hasDestination || !$hasBudget || !$hasDate) {
            Response::error(
                'Pour une recherche, les champs destination, budget_max et date_depart sont obligatoires (RG-01).',
                422
            );
        }

        if ($budgetMax < 0) {
            Response::error('Le budget maximum doit être positif.', 422);
        }

        if (!$this->isValidDate($dateDepart)) {
            Response::error('La date de départ doit être au format AAAA-MM-JJ.', 422);
        }

        return $this->repository->search($destination, $budgetMax, $dateDepart);
    }

    /** @return array<string, mixed> */
    public function getDetail(int $id): array
    {
        $voyage = $this->repository->find($id);

        if ($voyage === null) {
            Response::error('Voyage introuvable.', 404);
        }

        $reservations = $this->repository->countReservations($id);
        $capaciteMax = $voyage->capaciteMax;
        $placesRestantes = max(0, $capaciteMax - $reservations);

        return array_merge($voyage->toArray(), [
            'reservations_actuelles' => $reservations,
            'places_restantes' => $placesRestantes,
            'disponible' => $reservations < $capaciteMax,
        ]);
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTime::createFromFormat('Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
