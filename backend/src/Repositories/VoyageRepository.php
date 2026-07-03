<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Voyage;
use PDO;

final class VoyageRepository
{
    private const SELECT_COLUMNS = '
        Id_reservation AS id_voyage,
        Destination AS destination,
        Prix AS prix,
        DATE AS date_depart,
        titre,
        pays,
        capacite_max,
        image_url,
        description
    ';

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT ' . self::SELECT_COLUMNS . ' FROM voyage ORDER BY DATE ASC, Id_reservation ASC'
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn (array $row): array => Voyage::fromRow($row)->toArray(),
            $rows
        );
    }

    /**
     * Recherche de voyages selon les critères M1 (RG-01).
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(string $destination, float $budgetMax, string $dateDepart): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ' . self::SELECT_COLUMNS . '
             FROM voyage
             WHERE Destination LIKE :destination
               AND Prix <= :budget_max
               AND DATE = :date_depart
             ORDER BY Prix ASC'
        );

        $stmt->execute([
            'destination' => '%' . $destination . '%',
            'budget_max' => $budgetMax,
            'date_depart' => $dateDepart,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn (array $row): array => Voyage::fromRow($row)->toArray(),
            $rows
        );
    }

    public function find(int $id): ?Voyage
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ' . self::SELECT_COLUMNS . ' FROM voyage WHERE Id_reservation = :id'
        );

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : Voyage::fromRow($row);
    }

    public function countReservations(int $idVoyage): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) AS total FROM reservation WHERE Id_voyage = :id_voyage'
        );

        $stmt->execute(['id_voyage' => $idVoyage]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }
}
